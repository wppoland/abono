<?php

declare(strict_types=1);

namespace Subscribe\Admin;

use Subscribe\Contract\HasHooks;
use Subscribe\PostType\Subscriber;

defined('ABSPATH') || exit;

/**
 * Exports the subscriber list to a CSV download.
 *
 * The export is triggered from a nonce-protected admin link on the Subscribers
 * list table and gated behind the manage_woocommerce capability. Output streams
 * a CSV with email, consent, source and timestamp columns.
 */
final class Export implements HasHooks
{
    private const ACTION = 'subscribe_export';
    private const NONCE  = 'subscribe_export_csv';

    /**
     * Subscribers whose meta is loaded at once while streaming the file.
     */
    private const BATCH = 200;

    public function __construct(private readonly Subscriber $subscribers)
    {
    }

    public function registerHooks(): void
    {
        add_action('admin_post_' . self::ACTION, [$this, 'handle']);
        add_action('admin_notices', [$this, 'renderButton']);
    }

    /**
     * Render an Export button above the Subscribers list table.
     */
    public function renderButton(): void
    {
        $screen = get_current_screen();

        if (null === $screen || 'edit-' . Subscriber::POST_TYPE !== $screen->id) {
            return;
        }

        if (! current_user_can('manage_woocommerce')) {
            return;
        }

        $url = wp_nonce_url(
            admin_url('admin-post.php?action=' . self::ACTION),
            self::NONCE,
            '_subscribe_nonce',
        );
        ?>
        <div class="notice notice-info subscribe-export-notice">
            <p>
                <?php esc_html_e('Export every subscriber (email, consent, source and date) to a CSV file.', 'plogins-subscribe'); ?>
                <a class="button button-primary" href="<?php echo esc_url($url); ?>">
                    <?php esc_html_e('Export to CSV', 'plogins-subscribe'); ?>
                </a>
            </p>
        </div>
        <?php
    }

    /**
     * Stream the CSV download.
     */
    public function handle(): void
    {
        if (! current_user_can('manage_woocommerce')) {
            wp_die(esc_html__('You are not allowed to export subscribers.', 'plogins-subscribe'), '', ['response' => 403]);
        }

        $nonce = isset($_GET['_subscribe_nonce'])
            ? sanitize_text_field(wp_unslash($_GET['_subscribe_nonce']))
            : '';

        if (! wp_verify_nonce($nonce, self::NONCE)) {
            wp_die(esc_html__('Security check failed. Please try again.', 'plogins-subscribe'), '', ['response' => 403]);
        }

        $headers = apply_filters(
            'subscribe/export_headers',
            [
                __('Email', 'plogins-subscribe'),
                __('Consent', 'plogins-subscribe'),
                __('Source', 'plogins-subscribe'),
                __('Subscribed at', 'plogins-subscribe'),
            ],
        );

        nocache_headers();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="subscribers-' . gmdate('Y-m-d') . '.csv"');

        // Output is pre-escaped CSV text; not HTML. Echoing directly avoids the
        // PHP filesystem functions (fopen/fputcsv/fclose) that Plugin Check flags.
        // Every line goes out as it is read, so the list is never held twice.
        echo $this->csvLine($headers) . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        foreach ($this->rows() as $row) {
            echo $this->csvLine($row) . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        exit;
    }

    /**
     * Build one RFC 4180 CSV line from a row of string values.
     *
     * @param array<int, string> $row
     */
    private function csvLine(array $row): string
    {
        $cells = array_map(
            static function (string $value): string {
                // Neutralize spreadsheet formula triggers (OWASP CSV-injection
                // mitigation): if a cell starts with =, +, -, @, tab or CR, a
                // subscriber-supplied value could execute as a formula when the
                // file is opened in Excel/Sheets. Prefix it with a single quote
                // before the RFC 4180 quote-wrapping below.
                if ('' !== $value && false !== strpbrk($value[0], "=+-@\t\r")) {
                    $value = "'" . $value;
                }

                // Escape double quotes and wrap every field in quotes.
                return '"' . str_replace('"', '""', $value) . '"';
            },
            $row,
        );

        return implode(',', $cells);
    }

    /**
     * Yield one CSV data row per stored subscriber, a batch at a time.
     *
     * The ID list is read once and frozen, so a subscriber added while the file
     * downloads cannot push a row into a later batch and have it written twice.
     * Meta is primed one batch at a time and dropped again afterwards: reading
     * it row by row costs a query per subscriber and grows the object cache by
     * one entry per subscriber for the length of the export.
     *
     * @return \Generator<int, array<int, string>>
     */
    private function rows(): \Generator
    {
        foreach (array_chunk($this->ids(), self::BATCH) as $chunk) {
            update_meta_cache('post', $chunk);

            foreach ($chunk as $id) {
                $email   = (string) get_post_meta($id, Subscriber::META_EMAIL, true);
                $consent = (bool) get_post_meta($id, Subscriber::META_CONSENT, true);
                $source  = (string) get_post_meta($id, Subscriber::META_SOURCE, true);
                $ts      = absint(get_post_meta($id, Subscriber::META_CONSENTED, true));

                yield apply_filters(
                    'subscribe/export_row',
                    [
                        $email,
                        $consent ? __('Yes', 'plogins-subscribe') : __('No', 'plogins-subscribe'),
                        $this->subscribers->sourceLabel($source),
                        $ts > 0 ? gmdate('Y-m-d H:i:s', $ts) : '',
                    ],
                    $id,
                );

                wp_cache_delete($id, 'post_meta');
            }

            // Push the batch to the browser instead of letting PHP hold the
            // whole file in an output buffer, which is the copy this rewrite
            // set out to remove.
            if (ob_get_level() > 0) {
                ob_flush();
            }

            flush();
        }
    }

    /**
     * Every subscriber ID, newest first. IDs are eight bytes a row; the
     * subscriber records behind them are not, which is why only the IDs are
     * read in one go.
     *
     * @return list<int>
     */
    private function ids(): array
    {
        $ids = get_posts(
            [
                'post_type'      => Subscriber::POST_TYPE,
                'post_status'    => ['publish', 'private'],
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'no_found_rows'  => true,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ],
        );

        return array_map('intval', is_array($ids) ? $ids : []);
    }
}
