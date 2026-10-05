<?php

/**
 * The subscriber post type must be reachable only with manage_woocommerce.
 *
 * It was registered with capability_type 'post' and map_meta_cap true, so
 * every capability resolved to the ordinary post caps and an Editor could open
 * the Subscribers list and each subscriber's screen, reading every email the
 * plugin's own Export hides behind manage_woocommerce.
 *
 * This registers the post type against stubbed WordPress functions, rebuilds
 * the capability object the way get_post_type_capabilities() does, and checks
 * that no capability is left on a core post default.
 *
 * Run: php tests/subscriber-capabilities-check.php
 */

declare(strict_types=1);

namespace Subscribe\Contract {
    interface HasHooks
    {
        public function registerHooks(): void;
    }
}

namespace {
    define('ABSPATH', __DIR__);

    $registered = null;

    function post_type_exists(string $type): bool
    {
        return false;
    }

    function __(string $text, string $domain = ''): string
    {
        return $text;
    }

    function register_post_type(string $type, array $args): void
    {
        global $registered;
        $registered = $args;
    }

    require __DIR__ . '/../src/PostType/Subscriber.php';

    (new \Subscribe\PostType\Subscriber())->register();

    // Every capability core derives for a post type, singular/plural from
    // capability_type, as in get_post_type_capabilities().
    [$s, $p] = ['post', 'posts'];
    $defaults = [
        'edit_post'              => 'edit_' . $s,
        'read_post'              => 'read_' . $s,
        'delete_post'            => 'delete_' . $s,
        'edit_posts'             => 'edit_' . $p,
        'edit_others_posts'      => 'edit_others_' . $p,
        'delete_posts'           => 'delete_' . $p,
        'publish_posts'          => 'publish_' . $p,
        'read_private_posts'     => 'read_private_' . $p,
        'read'                   => 'read',
        'delete_private_posts'   => 'delete_private_' . $p,
        'delete_published_posts' => 'delete_published_' . $p,
        'delete_others_posts'    => 'delete_others_' . $p,
        'edit_private_posts'     => 'edit_private_' . $p,
        'edit_published_posts'   => 'edit_published_' . $p,
        'create_posts'           => 'edit_' . $p,
    ];

    $caps     = array_merge($defaults, $registered['capabilities'] ?? []);
    $failures = [];

    // With map_meta_cap true, core maps edit_post and friends back onto the
    // primitive post caps, and an Editor holds those.
    $ok = false === ($registered['map_meta_cap'] ?? null);
    printf("  %s map_meta_cap is false\n", $ok ? 'ok     ' : 'FAILED ');
    if (! $ok) {
        $failures[] = 'map_meta_cap';
    }

    foreach ($caps as $key => $cap) {
        $want = 'create_posts' === $key ? 'do_not_allow' : 'manage_woocommerce';
        $ok   = $cap === $want;
        printf("  %s %-24s %s\n", $ok ? 'ok     ' : 'FAILED ', $key, $cap);
        if (! $ok) {
            $failures[] = $key;
        }
    }

    echo "\n" . ($failures === [] ? "RESULT: pass\n" : 'RESULT: ' . count($failures) . " failed\n");
    exit($failures === [] ? 0 : 1);
}
