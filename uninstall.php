<?php
/**
 * Uninstall cleanup for Subscribe.
 *
 * Runs when the plugin is deleted from wp-admin. Removes the plugin's options.
 * Stored subscribers (the subscribe_subscriber custom post type) are intentionally
 * left in place: they are merchant data with recorded consent that should survive
 * a reinstall and can be removed manually if desired.
 *
 * @package Subscribe
 */

declare(strict_types=1);

defined('WP_UNINSTALL_PLUGIN') || exit;

/**
 * Remove this plugin's options from the current site.
 */
function subscribe_uninstall_site(): void
{
    delete_option('subscribe_settings');
    delete_option('subscribe_db_version');
}

// Options are per site, so a network uninstall has to visit every site.
if (is_multisite()) {
    foreach (get_sites(['fields' => 'ids', 'number' => 0]) as $subscribe_site_id) {
        switch_to_blog((int) $subscribe_site_id);
        subscribe_uninstall_site();
        restore_current_blog();
    }
    unset($subscribe_site_id);
} else {
    subscribe_uninstall_site();
}

// The PRO banner's dismissal is stored per user, so it belongs to the
// plugin rather than to the site content. User meta is global, not
// per-site, which is why this uses delete_metadata's \$delete_all rather
// than a loop over the users of one blog.
delete_metadata('user', 0, 'subscribe_pro_banner_dismissed', '', true);
