<?php
// existing-checked: tests/export-bound-check.php - that check covers the CSV export, not the privacy eraser.
// Run: php tests/privacy-eraser-trash-check.php
// Repro for abono SubscribePrivacyService eraser. 
namespace {
define('ABSPATH', '/');
// Posts in the DB holding the requested email: one private, one the admin trashed.
$GLOBALS['posts'] = [101 => 'private', 102 => 'trash'];
$GLOBALS['deleted'] = [];
function sanitize_email($e) { return $e; }
function __($s) { return $s; }
// Mirrors class-wp-query.php:2655: 'any' drops every exclude_from_search status (trash, auto-draft) unless it is listed too.
class WP_Query { public array $posts = [];
    function __construct(array $q) { $st = (array) $q['post_status'];
        foreach ($GLOBALS['posts'] as $id => $s) {
            if (in_array($s, $st, true) || (in_array('any', $st, true) && ! in_array($s, ['trash', 'auto-draft'], true))) { $this->posts[] = $id; } } } }
class WP_Post {}
function wp_delete_post($id, $force) { $GLOBALS['deleted'][] = $id; unset($GLOBALS['posts'][$id]); return new WP_Post(); }
function get_post_meta() { return ''; }
require dirname(__DIR__) . '/src/Contract/HasHooks.php';
require dirname(__DIR__) . '/src/PostType/Subscriber.php';
require dirname(__DIR__) . '/src/Service/SubscribePrivacyService.php';
$r = (new Subscribe\Service\SubscribePrivacyService())->eraseSubscribers('a@example.com');
echo 'erased: ', implode(',', $GLOBALS['deleted']), '; left in DB: ', implode(',', array_keys($GLOBALS['posts'])) ?: 'none', "\n";
$ok = [] === $GLOBALS['posts'] && 2 === $r['items_removed'];
echo $ok ? "PASS\n" : "FAIL\n";
exit($ok ? 0 : 1);
}
