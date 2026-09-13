<?php

/**
 * The subscriber export must stream, and must still produce the same file.
 *
 * It used to read the whole list into one array, turn that into a second array
 * of finished lines, and join those into a single string before sending a byte.
 * A list long enough met the memory limit as a blank page with no download.
 *
 * Two things have to be true of the fix, and only one of them is about memory:
 *
 *  1. the bytes are unchanged. LegacyExport below is the pre-fix rows() and
 *     csvLine() copied verbatim out of the commit before this one, so the
 *     comparison is against the code that shipped, not against a restatement
 *     of it;
 *  2. memory does not grow with the length of the list, and the consent meta is
 *     primed in batches rather than read one subscriber at a time.
 *
 * Run: php tests/export-bound-check.php
 */

declare(strict_types=1);

namespace {
    define('ABSPATH', __DIR__);

    const EXPORT_TEST_SMALL      = 500;
    const EXPORT_TEST_LARGE      = 20000;
    const EXPORT_TEST_BATCH      = 200;
    // The id list plus one batch of meta, not the file: 20,000 ids in a PHP
    // array are most of this, and that is the deliberate part of the design.
    const EXPORT_TEST_MEMORY_CAP = 1048576;

    /** @var array<int, array<string, string>> $export_test_meta Meta by subscriber id. */
    $export_test_meta = [];

    /** @var array<int, bool> $export_test_primed Ids whose meta the current batch primed. */
    $export_test_primed = [];

    /** @var int $export_test_prime_calls */
    $export_test_prime_calls = 0;

    /** @var int $export_test_misses Meta reads for an id no batch had primed. */
    $export_test_misses = 0;

    /** @var int $export_test_memory_peak */
    $export_test_memory_peak = 0;

    /** @var bool $export_test_watch_memory */
    $export_test_watch_memory = false;

    /** @var int $export_test_legacy_peak Memory sampled inside the old code's build. */
    $export_test_legacy_peak = 0;

    /** @var list<string> $export_test_failures */
    $export_test_failures = [];

    // phpcs:disable
    function get_posts(array $args): array
    {
        global $export_test_meta;

        // Newest first, which is what the export asks for and what the fixture
        // is numbered by.
        $ids = array_keys($export_test_meta);
        rsort($ids);

        return $ids;
    }

    function update_meta_cache(string $type, array $ids)
    {
        global $export_test_primed, $export_test_prime_calls;

        ++$export_test_prime_calls;
        $export_test_primed = array_fill_keys(array_map('intval', $ids), true);

        return [];
    }

    function get_post_meta(int $id, string $key, bool $single = false)
    {
        global $export_test_meta, $export_test_primed, $export_test_misses,
           $export_test_memory_peak, $export_test_watch_memory;

        if ([] !== $export_test_primed && ! isset($export_test_primed[$id])) {
            ++$export_test_misses;
        }

        if ($export_test_watch_memory) {
            $export_test_memory_peak = max($export_test_memory_peak, memory_get_usage());
        }

        return $export_test_meta[$id][$key] ?? '';
    }

    function wp_cache_delete($key, string $group = ''): bool
    {
        global $export_test_primed;
        unset($export_test_primed[(int) $key]);
        return true;
    }

    function absint($value): int
    {
        return abs((int) $value);
    }

    function apply_filters(string $hook, $value, ...$args)
    {
        return $value;
    }

    function __(string $text, string $domain = ''): string
    {
        return $text;
    }

    function ob_get_level_stub(): int
    {
        return 0;
    }
    // phpcs:enable

    function export_test_fail(string $message): void
    {
        global $export_test_failures;
        $export_test_failures[] = $message;
    }

    function export_test_assert(bool $passed, string $message): void
    {
        if (! $passed) {
            export_test_fail($message);
        }
    }
}

namespace Subscribe\PostType {
    final class Subscriber
    {
        public const POST_TYPE      = 'subscribe_subscriber';
        public const META_EMAIL     = '_subscribe_email';
        public const META_CONSENT   = '_subscribe_consent';
        public const META_SOURCE    = '_subscribe_source';
        public const META_CONSENTED = '_subscribe_consented_at';
        public const SOURCE_CHECKOUT = 'checkout';

        public function sourceLabel(string $source): string
        {
            switch ($source) {
                case self::SOURCE_CHECKOUT:
                    return 'Checkout';
                case '':
                    return '-';
                default:
                    return ucwords(str_replace(['_', '-'], ' ', $source));
            }
        }
    }
}

namespace Subscribe\Contract {
    interface HasHooks
    {
        public function registerHooks(): void;
    }
}

namespace Subscribe\Admin {

    use Subscribe\PostType\Subscriber;

    /**
     * rows(), csvLine() and the join, copied verbatim from src/Admin/Export.php
     * as it stood in eb309a2, the commit before the streaming rewrite. Kept
     * frozen on purpose: it is the reference the new output is compared to.
     */
    final class LegacyExport
    {
        public function __construct(private readonly Subscriber $subscribers)
        {
        }

        public function file(): string
        {
            $rows  = $this->rows();
            $lines = [];

            $headers = \apply_filters(
                'subscribe/export_headers',
                [
                    \__('Email', 'plogins-subscribe'),
                    \__('Consent', 'plogins-subscribe'),
                    \__('Source', 'plogins-subscribe'),
                    \__('Subscribed at', 'plogins-subscribe'),
                ],
            );

            $lines[] = $this->csvLine($headers);

            foreach ($rows as $row) {
                $lines[] = $this->csvLine($row);
                $GLOBALS['export_test_legacy_peak'] = max(
                    $GLOBALS['export_test_legacy_peak'],
                    memory_get_usage(),
                );
            }

            $file = implode("\r\n", $lines) . "\r\n";

            // The join is the third copy, and it is alive alongside the second.
            $GLOBALS['export_test_legacy_peak'] = max(
                $GLOBALS['export_test_legacy_peak'],
                memory_get_usage(),
            );

            return $file;
        }

        /** @param array<int, string> $row */
        private function csvLine(array $row): string
        {
            $cells = array_map(
                static function (string $value): string {
                    if ('' !== $value && false !== strpbrk($value[0], "=+-@\t\r")) {
                        $value = "'" . $value;
                    }

                    return '"' . str_replace('"', '""', $value) . '"';
                },
                $row,
            );

            return implode(',', $cells);
        }

        /** @return array<int, array{0: string, 1: string, 2: string, 3: string}> */
        private function rows(): array
        {
            $ids = \get_posts(
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

            $rows = [];

            foreach ($ids as $id) {
                $id      = (int) $id;
                $email   = (string) \get_post_meta($id, Subscriber::META_EMAIL, true);
                $consent = (bool) \get_post_meta($id, Subscriber::META_CONSENT, true);
                $source  = (string) \get_post_meta($id, Subscriber::META_SOURCE, true);
                $ts      = \absint(\get_post_meta($id, Subscriber::META_CONSENTED, true));

                $rows[] = \apply_filters(
                    'subscribe/export_row',
                    [
                        $email,
                        $consent ? \__('Yes', 'plogins-subscribe') : \__('No', 'plogins-subscribe'),
                        $this->subscribers->sourceLabel($source),
                        $ts > 0 ? gmdate('Y-m-d H:i:s', $ts) : '',
                    ],
                    $id,
                );
            }

            return $rows;
        }
    }
}

namespace {
    require __DIR__ . '/../src/Admin/Export.php';

    /**
     * Fill the fixture with $count subscribers, including the values a CSV has
     * to be careful with: a quote, a leading equals sign, a comma, a newline.
     */
    function export_test_fixture(int $count): void
    {
        global $export_test_meta;

        $export_test_meta = [];
        $sources          = ['checkout', 'popup_footer', '', 'widget-sidebar'];
        $nasty            = ['=cmd@example.com', 'a"b@example.com', 'comma,man@example.com', "line\nbreak@example.com"];

        for ($i = 1; $i <= $count; $i++) {
            $email = 0 === $i % 250
                ? $nasty[($i / 250) % count($nasty)]
                : 'subscriber' . $i . '@example.com';

            $export_test_meta[1000 + $i] = [
                '_subscribe_email'        => $email,
                '_subscribe_consent'      => 0 === $i % 3 ? '' : '1',
                '_subscribe_source'       => $sources[$i % count($sources)],
                '_subscribe_consented_at' => 0 === $i % 7 ? '0' : (string) (1700000000 + $i),
            ];
        }
    }

    /**
     * Run the real, private rows() generator and build what handle() would echo.
     */
    function export_test_stream(\Subscribe\Admin\Export $export, ?\HashContext $hash = null): string
    {
        $reflection = new \ReflectionMethod($export, 'rows');
        $reflection->setAccessible(true);

        $line = new \ReflectionMethod($export, 'csvLine');
        $line->setAccessible(true);

        $headers = [
            __('Email', 'plogins-subscribe'),
            __('Consent', 'plogins-subscribe'),
            __('Source', 'plogins-subscribe'),
            __('Subscribed at', 'plogins-subscribe'),
        ];

        $out  = '';
        $head = $line->invoke($export, $headers) . "\r\n";

        if (null === $hash) {
            $out .= $head;
        } else {
            hash_update($hash, $head);
        }

        $rows = $reflection->invoke($export);

        if (! $rows instanceof \Generator) {
            export_test_fail('rows() returns the whole list at once instead of yielding a batch at a time');

            return '';
        }

        foreach ($rows as $row) {
            $text = $line->invoke($export, $row) . "\r\n";

            if (null === $hash) {
                $out .= $text;
            } else {
                hash_update($hash, $text);
            }
        }

        return $out;
    }

    $subscribers = new \Subscribe\PostType\Subscriber();
    $export      = new \Subscribe\Admin\Export($subscribers);
    $legacy      = new \Subscribe\Admin\LegacyExport($subscribers);

    // ------------------------------------------------- 1. the bytes are the same
    export_test_fixture(EXPORT_TEST_SMALL);

    $streamed = export_test_stream($export);
    $before   = $legacy->file();

    export_test_assert(
        $streamed === $before,
        sprintf(
            'the streamed file is not the file the old code produced (%d bytes against %d, first difference at %d)',
            strlen($streamed),
            strlen($before),
            (int) strspn($streamed ^ $before, "\0"),
        ),
    );

    export_test_assert(
        substr_count($streamed, "\r\n") === EXPORT_TEST_SMALL + 1,
        sprintf('the file holds %d lines, the fixture holds %d subscribers plus a header', substr_count($streamed, "\r\n"), EXPORT_TEST_SMALL),
    );

    // --------------------------------- 2. memory, and the meta read in batches
    export_test_fixture(EXPORT_TEST_LARGE);

    $export_test_prime_calls  = 0;
    $export_test_misses       = 0;
    $export_test_primed       = [];
    $export_test_watch_memory = true;

    $base                    = memory_get_usage();
    $export_test_memory_peak = $base;

    // Hashed rather than concatenated: a test that builds the file in a string
    // is the defect it is measuring.
    $hash = hash_init('md5');
    export_test_stream($export, $hash);
    $streamedDigest = hash_final($hash);

    $export_test_watch_memory = false;
    $growth                   = $export_test_memory_peak - $base;

    export_test_assert(
        $growth <= EXPORT_TEST_MEMORY_CAP,
        sprintf(
            'memory grew by %d bytes while writing %d subscribers; the list is being held, not streamed',
            $growth,
            EXPORT_TEST_LARGE,
        ),
    );

    $expectedPrimes = (int) ceil(EXPORT_TEST_LARGE / EXPORT_TEST_BATCH);

    export_test_assert(
        $export_test_prime_calls === $expectedPrimes,
        sprintf(
            'the consent meta was primed %d times for %d subscribers, expected %d batches of %d',
            $export_test_prime_calls,
            EXPORT_TEST_LARGE,
            $expectedPrimes,
            EXPORT_TEST_BATCH,
        ),
    );

    export_test_assert(
        0 === $export_test_misses,
        sprintf('%d meta reads fell outside the batch that primed them', $export_test_misses),
    );

    // The same list through the old code, for the figure the changelog quotes.
    $legacyBase              = memory_get_usage();
    $export_test_legacy_peak = $legacyBase;
    $legacyFile              = $legacy->file();

    export_test_assert(
        md5($legacyFile) === $streamedDigest,
        'over 20,000 subscribers the streamed file and the old file are not the same bytes',
    );

    $legacyGrowth = $export_test_legacy_peak - $legacyBase;
    $fileSize     = strlen($legacyFile);

    unset($legacyFile);

    export_test_assert(
        $growth * 2 < $legacyGrowth,
        sprintf(
            'streaming held %d bytes against the old code\'s %d; that is not the difference the rewrite is for',
            $growth,
            $legacyGrowth,
        ),
    );

    // ------------------------------------------------------------------ report
    if ([] !== $export_test_failures) {
        foreach ($export_test_failures as $failure) {
            fwrite(STDERR, '  ' . $failure . "\n");
        }

        fwrite(STDERR, sprintf("export-bound-check: FAIL, %d problem(s)\n", count($export_test_failures)));
        exit(1);
    }

    printf(
        "export-bound-check: OK, %d subscribers, %d byte file, %d bytes of memory streaming against %d holding it, %d meta batches\n",
        EXPORT_TEST_LARGE,
        $fileSize,
        $growth,
        $legacyGrowth,
        $export_test_prime_calls,
    );
}
