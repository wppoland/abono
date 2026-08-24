<?php

declare(strict_types=1);

namespace Subscribe\Service;

use Subscribe\Contract\HasHooks;
use Subscribe\PostType\Subscriber;
use WP_Query;

defined('ABSPATH') || exit;

/**
 * Personal data exporter and eraser for newsletter subscribers.
 */
final class SubscribePrivacyService implements HasHooks
{
    private const PAGE_SIZE = 100;

    public function registerHooks(): void
    {
        add_filter('wp_privacy_personal_data_exporters', [$this, 'registerExporters']);
        add_filter('wp_privacy_personal_data_erasers', [$this, 'registerErasers']);
    }

    /**
     * @param array<string, array<string, mixed>> $exporters
     * @return array<string, array<string, mixed>>
     */
    public function registerExporters(array $exporters): array
    {
        $exporters['subscribe-subscribers'] = [
            'exporter_friendly_name' => __('Newsletter Subscriptions', 'plogins-subscribe'),
            'callback'               => [$this, 'exportSubscribers'],
        ];

        return $exporters;
    }

    /**
     * @param array<string, array<string, mixed>> $erasers
     * @return array<string, array<string, mixed>>
     */
    public function registerErasers(array $erasers): array
    {
        $erasers['subscribe-subscribers'] = [
            'eraser_friendly_name' => __('Newsletter Subscriptions', 'plogins-subscribe'),
            'callback'             => [$this, 'eraseSubscribers'],
        ];

        return $erasers;
    }

    /**
     * @return array{data: list<array<string, mixed>>, done: bool}
     */
    public function exportSubscribers(string $email, int $page = 1): array
    {
        $page    = max(1, $page);
        $postIds = $this->findSubscriberPostIds($email, $page);

        $items = [];
        foreach ($postIds as $postId) {
            $source      = (string) get_post_meta($postId, Subscriber::META_SOURCE, true);
            $consentedAt = (string) get_post_meta($postId, Subscriber::META_CONSENTED, true);

            $items[] = [
                'group_id'    => 'subscribe-subscribers',
                'group_label' => __('Newsletter Subscriptions', 'plogins-subscribe'),
                'item_id'     => 'subscriber-' . $postId,
                'data'        => [
                    ['name' => __('Email Address', 'plogins-subscribe'), 'value' => $email],
                    ['name' => __('Opt-in Source', 'plogins-subscribe'), 'value' => $source],
                    ['name' => __('Consented At', 'plogins-subscribe'), 'value' => $consentedAt],
                ],
            ];
        }

        return [
            'data' => $items,
            'done' => count($postIds) < self::PAGE_SIZE,
        ];
    }

    /**
     * @return array{items_removed: int, items_retained: int, messages: list<string>, done: bool}
     */
    public function eraseSubscribers(string $email, int $page = 1): array
    {
        $page    = max(1, $page);
        $postIds = $this->findSubscriberPostIds($email, $page);

        $removed = 0;
        foreach ($postIds as $postId) {
            $deleted = wp_delete_post($postId, true);
            if ($deleted instanceof \WP_Post) {
                $removed++;
            }
        }

        return [
            'items_removed'  => $removed,
            'items_retained' => 0,
            'messages'       => [],
            'done'           => count($postIds) < self::PAGE_SIZE,
        ];
    }

    /**
     * @return list<int>
     */
    private function findSubscriberPostIds(string $email, int $page): array
    {
        $query = new WP_Query([
            'post_type'      => Subscriber::POST_TYPE,
            'post_status'    => 'any',
            'posts_per_page' => self::PAGE_SIZE,
            'paged'          => $page,
            'fields'         => 'ids',
            'meta_query'     => [
                [
                    'key'     => Subscriber::META_EMAIL,
                    'value'   => sanitize_email($email),
                    'compare' => '=',
                ],
            ],
        ]);

        /** @var list<int> $posts */
        $posts = is_array($query->posts) ? array_map('intval', $query->posts) : [];

        return $posts;
    }
}
