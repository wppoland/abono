<?php
/**
 * PRO upsell content, generated from the plogins.com registry by
 * scripts/gen-pro-upsell.mjs. The admin upsell renders this; curate the
 * feature list to fit this plugin's settings screen (do not invent features).
 *
 * @package plogins-subscribe-pro
 */

defined('ABSPATH') || exit;

return [
    'name'       => 'Subscribe Pro',
    'url'        => 'https://plogins.com/plogins-subscribe-pro/pricing/',
    'sellable'   => true,
    'price_from' => 29,
    'currency'   => 'EUR',
    'price_pln'  => 129,
    'lead'       => [
        'en' => 'Welcome email, double opt-in, custom fields and Mailchimp, Brevo or Klaviyo sync ship in version 0.6.0. Feature-complete PRO.',
        'pl' => 'E-mail powitalny, double opt-in, własne pola i synchronizacja Mailchimp, Brevo lub Klaviyo są wdrożone w wydaniu 0.6.0. Feature-complete PRO.',
    ],
    'features'   => [
        [
            'en' => ['title' => 'Automatic welcome email', 'desc' => 'Send a welcome email automatically on each new subscriber (shipped).'],
            'pl' => ['title' => 'Automatyczny e-mail powitalny', 'desc' => 'Automatyczna wysyłka e-maila powitalnego do nowych subskrybentów (wdrożone).'],
        ],
        [
            'en' => ['title' => 'Double opt-in confirmation', 'desc' => 'Send a verification link and record subscribers only after they confirm (shipped).'],
            'pl' => ['title' => 'Weryfikacja double opt-in', 'desc' => 'Wysyłka linku potwierdzającego i aktywacja subskrypcji dopiero po kliknięciu (wdrożone).'],
        ],
        [
            'en' => ['title' => 'Custom fields and tags', 'desc' => 'Capture extra checkout fields and tag subscribers by source (shipped).'],
            'pl' => ['title' => 'Własne pola i tagi', 'desc' => 'Zbieranie dodatkowych pól w kasie i tagowanie subskrybentów według źródła (wdrożone).'],
        ],
        [
            'en' => ['title' => 'Mailchimp provider sync', 'desc' => 'Push consented subscribers to Mailchimp with default or per-source list routing (shipped).'],
            'pl' => ['title' => 'Synchronizacja Mailchimp', 'desc' => 'Przekazywanie subskrybentów ze zgodą do Mailchimp z domyślną lub per-źródłową listą odbiorców (wdrożone).'],
        ],
        [
            'en' => ['title' => 'Brevo provider sync', 'desc' => 'Push consented subscribers to Brevo with custom fields mapped to contact attributes (shipped in 0.5.0).'],
            'pl' => ['title' => 'Synchronizacja Brevo', 'desc' => 'Przekazywanie subskrybentów ze zgodą do Brevo z polami własnymi jako atrybutami kontaktu (wdrożone w 0.5.0).'],
        ],
        [
            'en' => ['title' => 'Klaviyo provider sync', 'desc' => 'Push consented subscribers to Klaviyo with custom fields mapped to profile properties (shipped in 0.6.0).'],
            'pl' => ['title' => 'Synchronizacja Klaviyo', 'desc' => 'Przekazywanie subskrybentów ze zgodą do Klaviyo z polami własnymi jako właściwościami profilu (wdrożone w 0.6.0).'],
        ],
    ],
];
