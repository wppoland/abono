<?php

declare(strict_types=1);

namespace Subscribe\Admin;

defined('ABSPATH') || exit;

/**
 * PRO upgrade promotion, shown ONLY on the Subscribe settings screen: a dismissible
 * top banner, a sidebar promo panel, and a "what PRO adds" locked-card list.
 *
 * It is pure advertising: no disabled form fields, nothing blocks a free
 * workflow, it is scoped to this one screen and the banner is dismissible per
 * user. That keeps it inside the WordPress.org guidelines (no admin hijacking,
 * no trialware). Content comes from config/pro-upsell.php, generated from the
 * plogins.com registry, so the feature copy always matches the real PRO edition.
 */
final class ProUpsell
{
    private const META   = 'subscribe_pro_banner_dismissed';
    private const ACTION = 'subscribe_dismiss_pro';

    /** @var array<string, mixed>|null */
    private ?array $data = null;

    public function registerHooks(): void
    {
        add_action('admin_post_' . self::ACTION, [$this, 'handleDismiss']);
    }

    /** @return array<string, mixed> */
    private function data(): array
    {
        if ($this->data === null) {
            $file = SUBSCRIBE_DIR . 'config/pro-upsell.php';
            $this->data = is_readable($file) ? (array) require $file : [];
        }
        return $this->data;
    }

    /** Whether the PRO edition can actually be bought yet. */
    private function sellable(): bool
    {
        return (bool) ($this->data()['sellable'] ?? false);
    }

    /** Whether to render the promo at all (filterable for white-label builds). */
    public function enabled(): bool
    {
        /**
         * Filters whether the Subscribe PRO promo is shown on the settings screen.
         *
         * @param bool $show Default true.
         */
        return (bool) apply_filters('subscribe/show_pro_cta', true) && $this->features() !== [];
    }

    private function url(): string
    {
        $default = (string) ($this->data()['url'] ?? 'https://plogins.com/plogins-subscribe-pro/');
        /**
         * Filters the URL the PRO call-to-action buttons point at.
         *
         * @param string $url Default the Subscribe PRO page.
         */
        return (string) apply_filters('subscribe/pro_url', $default);
    }

    private function isPolish(): bool
    {
        return str_starts_with((string) get_locale(), 'pl');
    }

    private function priceLabel(): string
    {
        if (! $this->sellable()) {
            return $this->isPolish() ? __('Wkrótce', 'plogins-subscribe') : __('Coming soon', 'plogins-subscribe');
        }
        $d = $this->data();
        if ($this->isPolish() && ! empty($d['price_pln'])) {
            /* translators: %d: yearly price in PLN */
            return sprintf(__('od %d zł/rok', 'plogins-subscribe'), (int) $d['price_pln']);
        }
        if (! empty($d['price_from'])) {
            $cur = ($d['currency'] ?? 'EUR') === 'EUR' ? '€' : (string) $d['currency'] . ' ';
            /* translators: 1: currency symbol, 2: yearly price */
            return sprintf(__('from %1$s%2$d/yr', 'plogins-subscribe'), $cur, (int) $d['price_from']);
        }
        return '';
    }

    /** The call-to-action label: buy when sellable, otherwise a soft notify. */
    private function ctaLabel(): string
    {
        return $this->sellable()
            ? __('Upgrade to PRO', 'plogins-subscribe')
            : ($this->isPolish() ? __('Powiadom mnie', 'plogins-subscribe') : __('Get notified', 'plogins-subscribe'));
    }

    /** @return array<int, array{title: string, desc: string}> */
    private function features(): array
    {
        $lang = $this->isPolish() ? 'pl' : 'en';
        $out  = [];
        foreach ((array) ($this->data()['features'] ?? []) as $f) {
            $x = is_array($f) ? ($f[$lang] ?? $f['en'] ?? null) : null;
            if (is_array($x) && ! empty($x['title'])) {
                $out[] = ['title' => (string) $x['title'], 'desc' => (string) ($x['desc'] ?? '')];
            }
        }
        return $out;
    }

    public function bannerDismissed(): bool
    {
        return (bool) get_user_meta(get_current_user_id(), self::META, true);
    }

    private function dismissUrl(): string
    {
        return wp_nonce_url(admin_url('admin-post.php?action=' . self::ACTION), self::ACTION);
    }

    public function handleDismiss(): void
    {
        if (! current_user_can('manage_woocommerce')) {
            wp_die(esc_html__('Permission denied.', 'plogins-subscribe'));
        }
        check_admin_referer(self::ACTION);
        update_user_meta(get_current_user_id(), self::META, 1);
        wp_safe_redirect(wp_get_referer() ?: admin_url('admin.php?page=subscribe-settings'));
        exit;
    }

    /* ------------------------------------------------------------------ */
    /* Render pieces                                                       */
    /* ------------------------------------------------------------------ */

    /** Dismissible strip at the top of the settings screen. */
    public function banner(): void
    {
        if (! $this->enabled() || $this->bannerDismissed()) {
            return;
        }
        $name     = (string) ($this->data()['name'] ?? 'Subscribe Pro');
        $price    = $this->priceLabel();
        $subtitle = implode(', ', array_slice(array_map(
            static fn (array $f): string => $f['title'],
            $this->features(),
        ), 0, 3));
        ?>
        <div class="subscribe-pro-banner" role="note">
            <span class="subscribe-pro-banner__tag">PRO</span>
            <p class="subscribe-pro-banner__text">
                <strong><?php
                /* translators: %s: PRO edition name */
                printf(esc_html__('Do more with %s', 'plogins-subscribe'), esc_html($name)); ?></strong>
                <?php if ($subtitle !== '') : ?><span class="subscribe-pro-banner__sub"><?php echo esc_html($subtitle); ?></span><?php endif; ?>
                <?php if ($price !== '') : ?><span class="subscribe-pro-banner__price"><?php echo esc_html($price); ?></span><?php endif; ?>
            </p>
            <a class="button button-primary subscribe-pro-banner__cta" href="<?php echo esc_url($this->url()); ?>" target="_blank" rel="noopener noreferrer">
                <?php echo esc_html($this->ctaLabel()); ?>
            </a>
            <a class="subscribe-pro-banner__dismiss" href="<?php echo esc_url($this->dismissUrl()); ?>" aria-label="<?php esc_attr_e('Dismiss this notice', 'plogins-subscribe'); ?>">&times;</a>
        </div>
        <?php
    }

    /** Sidebar promo panel (sits in the settings two-column layout). */
    public function aside(): void
    {
        if (! $this->enabled()) {
            return;
        }
        $name     = (string) ($this->data()['name'] ?? 'Subscribe Pro');
        $price    = $this->priceLabel();
        $features = $this->features();
        ?>
        <aside class="subscribe-card subscribe-pro-aside" aria-labelledby="subscribe-pro-aside-h">
            <p class="subscribe-pro-aside__eyebrow"><?php echo esc_html($name); ?></p>
            <h2 id="subscribe-pro-aside-h" class="subscribe-pro-aside__heading"><?php esc_html_e('Unlock every PRO feature', 'plogins-subscribe'); ?></h2>
            <ul class="subscribe-pro-aside__list">
                <?php foreach ($features as $f) : ?>
                    <li>
                        <span class="subscribe-pro-aside__lock" aria-hidden="true"></span>
                        <span><?php echo esc_html($f['title']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a class="button button-primary button-hero subscribe-pro-aside__cta" href="<?php echo esc_url($this->url()); ?>" target="_blank" rel="noopener noreferrer">
                <?php echo esc_html($this->ctaLabel()); ?>
            </a>
            <?php if ($price !== '') : ?>
                <p class="subscribe-pro-aside__price"><?php echo esc_html($price); ?><?php if ($this->sellable()) : ?> · <?php esc_html_e('one licence, every PRO feature', 'plogins-subscribe'); ?><?php endif; ?></p>
            <?php endif; ?>
        </aside>
        <?php
    }

    /** "What PRO adds" locked-card grid, appended after the settings form. */
    public function cards(): void
    {
        if (! $this->enabled()) {
            return;
        }
        $features = $this->features();
        $name     = (string) ($this->data()['name'] ?? 'Subscribe Pro');
        ?>
        <section class="subscribe-pro-cards" aria-labelledby="subscribe-pro-cards-h">
            <h2 id="subscribe-pro-cards-h" class="subscribe-pro-cards__title">
                <?php
                /* translators: %s: PRO edition name */
                printf(esc_html__('What %s adds', 'plogins-subscribe'), esc_html($name)); ?>
            </h2>
            <div class="subscribe-pro-cards__grid">
                <?php foreach ($features as $f) : ?>
                    <article class="subscribe-pro-card">
                        <span class="subscribe-pro-card__badge">PRO</span>
                        <span class="subscribe-pro-card__lock" aria-hidden="true"></span>
                        <h3 class="subscribe-pro-card__title"><?php echo esc_html($f['title']); ?></h3>
                        <?php if ($f['desc'] !== '') : ?>
                            <p class="subscribe-pro-card__desc"><?php echo esc_html($f['desc']); ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}
