<?php

declare(strict_types=1);

namespace Subscribe\Service;

use Subscribe\Contract\HasHooks;
use Subscribe\PostType\Subscriber;

defined('ABSPATH') || exit;

/**
 * Adds the newsletter opt-in checkbox to the classic WooCommerce checkout and
 * records the subscriber when the order is placed and the box was ticked.
 *
 * The checkbox is unticked by default (configurable) for explicit GDPR consent.
 * Recording is idempotent, the Subscriber CPT de-duplicates by email, so a
 * repeat customer never creates a duplicate record.
 */
final class Checkout implements HasHooks
{
    private const FIELD = 'subscribe_optin';

    private const NONCE_FIELD = 'subscribe_optin_nonce';

    private const NONCE_ACTION = 'subscribe_optin';

    /** Whether the verified submission ticked the box; set before the order exists. */
    private bool $optedIn = false;

    public function __construct(
        private readonly SettingsStore $settings,
        private readonly Subscriber $subscribers,
    ) {
    }

    public function registerHooks(): void
    {
        if (! $this->settings->isEnabled() || ! (bool) $this->settings->get('checkout', true)) {
            return;
        }

        add_action('wp_enqueue_scripts', [$this, 'enqueueAssets']);
        add_action('woocommerce_checkout_after_terms_and_conditions', [$this, 'renderCheckbox']);

        // Read the box while the shopper is still the one the nonce was made for
        // (checkout may log in a newly created account before the order exists),
        // then persist the opt-in once the order is created.
        add_action('woocommerce_checkout_process', [$this, 'readOptIn']);
        add_action('woocommerce_checkout_order_processed', [$this, 'capture'], 10, 2);
    }

    /**
     * Enqueue the opt-in row styles and the presentation-only postmark script,
     * only on the checkout where the field renders.
     */
    public function enqueueAssets(): void
    {
        if (! function_exists('is_checkout') || ! is_checkout()) {
            return;
        }

        wp_enqueue_style(
            'subscribe-checkout',
            SUBSCRIBE_URL . 'assets/css/checkout.css',
            [],
            \Subscribe\VERSION,
        );

        wp_enqueue_script(
            'subscribe-checkout',
            SUBSCRIBE_URL . 'assets/js/checkout.js',
            [],
            \Subscribe\VERSION,
            true,
        );
    }

    /**
     * Render the consent checkbox. Output is fully escaped.
     */
    public function renderCheckbox(): void
    {
        $checked = (bool) $this->settings->get('default_checked', false);
        ?>
        <p class="form-row subscribe-optin" id="subscribe_optin_field">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox subscribe-optin__label">
                <input
                    type="checkbox"
                    class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox subscribe-optin__input"
                    name="<?php echo esc_attr(self::FIELD); ?>"
                    id="<?php echo esc_attr(self::FIELD); ?>"
                    value="1"
                    <?php checked($checked, true); ?>
                />
                <span class="subscribe-optin__text"><?php echo esc_html($this->settings->label()); ?></span>
                <span class="subscribe-optin__mark" aria-hidden="true"><?php echo esc_html__('Subscribed', 'abono'); ?></span>
            </label>
        </p>
        <?php
        wp_nonce_field(self::NONCE_ACTION, self::NONCE_FIELD, false);

        /**
         * Fires after the checkout opt-in checkbox markup.
         *
         * Add-ons (e.g. Abono Pro custom fields) may output extra inputs here.
         */
        do_action('subscribe/checkout_after_optin');
    }

    /**
     * Read the opt-in from the checkout submission, only when our own nonce
     * verifies. A present but invalid nonce stops the checkout with a notice.
     */
    public function readOptIn(): void
    {
        $this->optedIn = false;

        if (! isset($_POST[self::NONCE_FIELD])) {
            return;
        }

        if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[self::NONCE_FIELD])), self::NONCE_ACTION)) {
            wc_add_notice(__('This page has expired. Reload it and try again.', 'abono'), 'error');

            return;
        }

        $this->optedIn = isset($_POST[self::FIELD])
            && '1' === sanitize_text_field(wp_unslash($_POST[self::FIELD]));
    }

    /**
     * Record the subscriber when the box was ticked.
     *
     * @param mixed $order Order object passed by WooCommerce (unused).
     */
    public function capture(int $orderId, mixed $order = null): void
    {
        unset($order);

        if (! $this->optedIn) {
            return;
        }

        $email = $this->orderEmail($orderId);

        if ('' === $email) {
            return;
        }

        // Idempotency: skip if already subscribed.
        if ($this->subscribers->exists($email)) {
            return;
        }

        $this->subscribers->create($email, Subscriber::SOURCE_CHECKOUT);
    }

    /**
     * Resolve the customer's billing email from the order.
     */
    private function orderEmail(int $orderId): string
    {
        $order = function_exists('wc_get_order') ? wc_get_order($orderId) : null;

        if (! $order instanceof \WC_Order) {
            return '';
        }

        $email = sanitize_email((string) $order->get_billing_email());

        return is_email($email) ? $email : '';
    }
}
