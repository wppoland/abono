<?php

/**
 * The subscriber meta allowlist must accept exactly the keys its only producer
 * can build, and reject everything else.
 *
 * Subscribe Pro builds custom-field meta keys with sanitize_key(), which core
 * defines as preg_replace('/[^a-z0-9_\-]/', '', ...): hyphens survive by
 * design. The allowlist here used [a-z0-9_]+ and silently `continue`d on any
 * key containing one, so a merchant field keyed `vat-id` rendered at checkout,
 * was enforced as required, and was then discarded with nothing stored and no
 * error. Underscore-only keys worked, so the loss looked arbitrary.
 *
 * The rejection cases are not padding: this allowlist is what stops an
 * arbitrary filter callback writing arbitrary post meta.
 *
 * Run: php tests/subscriber-meta-allowlist-check.php
 */

declare(strict_types=1);

$src = (string) file_get_contents(__DIR__ . '/../src/PostType/Subscriber.php');

if (! preg_match("/preg_match\(\s*'([^']+)'\s*,\s*\\\$key\s*\)/", $src, $m)) {
    fwrite(STDERR, "Could not find the allowlist pattern in Subscriber.php\n");
    exit(1);
}

$pattern = $m[1];
echo "  pattern: {$pattern}\n\n";

$cases = [
    // [key, should be accepted, why it matters]
    ['_subscribe_field_company',    true,  'plain underscore key, the common case'],
    ['_subscribe_field_vat-id',     true,  'sanitize_key keeps hyphens, so Pro can build this'],
    ['_subscribe_field_phone-number', true, 'the reported failure'],
    ['_subscribe_pro_tags',         true,  'the other allowed prefix'],
    ['_subscribe_pro_utm-source',   true,  'hyphen under the pro prefix too'],
    ['_subscribe_field_a1-b2_c3',   true,  'digits, hyphen and underscore together'],

    ['_subscribe_field_',           false, 'prefix with no name is not a key'],
    ['_other_plugin_secret',        false, 'a foreign prefix must never be written'],
    ['_subscribe_field_Upper',      false, 'sanitize_key lowercases, so this cannot come from Pro'],
    ['_subscribe_field_semi;colon', false, 'punctuation sanitize_key would have stripped'],
    ['_subscribe_field_sp ace',     false, 'whitespace, likewise'],
    ['subscribe_field_noprefix',    false, 'missing the leading underscore'],
    ['_wp_attached_file',           false, 'a core meta key must never be reachable'],
];

$failures = [];

foreach ($cases as [$key, $want, $why]) {
    $got = (bool) preg_match($pattern, $key);
    $ok  = $got === $want;

    printf("  %s %-34s %-8s %s\n", $ok ? 'ok     ' : 'FAILED ', $key, $want ? 'accept' : 'reject', $why);

    if (! $ok) {
        $failures[] = $key;
    }
}

// sanitize_key's own character class is the contract. Anything it can emit
// after the prefix must be accepted, or the two disagree again.
$sanitizeKeyAlphabet = 'abcdefghijklmnopqrstuvwxyz0123456789_-';
$synthetic = '_subscribe_field_' . $sanitizeKeyAlphabet;
$ok = (bool) preg_match($pattern, $synthetic);
printf("\n  %s every character sanitize_key() can emit is accepted\n", $ok ? 'ok     ' : 'FAILED ');
if (! $ok) {
    $failures[] = 'sanitize_key alphabet';
}

echo "\n" . ($failures === [] ? "RESULT: pass\n" : 'RESULT: ' . count($failures) . " failed\n");
exit($failures === [] ? 0 : 1);
