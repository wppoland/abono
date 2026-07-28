=== Plogins Subscribe - Newsletter Signup for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, newsletter, opt-in, gdpr, checkout
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Requires Plugins: woocommerce
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Dodaje pole wyboru zapisu do newslettera przy kasie WooCommerce i rejestruje adres e-mail, zgodę oraz datę zapisu każdego subskrybenta.

== Description ==

Subscribe umieszcza pole wyboru zapisu do newslettera przy kasie WooCommerce. Gdy
klient je zaznaczy i złoży zamówienie, jego adres e-mail zostaje zapisany w Twojej witrynie
wraz z flagą zgody, informacją, skąd pochodzi zapis, oraz datą. Listę przeglądasz
w menu WooCommerce i eksportujesz ją, kiedy tylko potrzebujesz.

Pole wyboru jest domyślnie niezaznaczone, czego wymaga większość konfiguracji RODO:
klient musi świadomie wyrazić zgodę. Możesz edytować tekst etykiety, a
nic nigdy nie jest wysyłane do usługi zewnętrznej, więc adresy e-mail pozostają w Twojej
bazie danych i nigdzie indziej.

Wtyczka jest napisana tak, aby kod był łatwy do czytania i forkowania. Jeśli natrafisz na błąd
lub chcesz zaproponować zmianę, kod źródłowy i system zgłoszeń znajdziesz pod adresem
https://github.com/wppoland/plogins-subscribe.

= Documentation and links =

* <strong>Dokumentacja</strong> - https://plogins.com/pl/plogins-subscribe/docs/
* <strong>Strona wtyczki</strong> - https://plogins.com/pl/plogins-subscribe/
* <strong>Kod źródłowy</strong> - https://github.com/wppoland/plogins-subscribe
* <strong>Zgłoszenia błędów i propozycje funkcji</strong> - https://github.com/wppoland/plogins-subscribe/issues


= What it does =

* Dodaje pole wyboru zapisu do newslettera do klasycznej kasy WooCommerce.
* Rejestruje zapis tylko wtedy, gdy klient zaznaczy pole; domyślnie jest ono odznaczone, a etykietę można edytować.
* Zapisuje każdego subskrybenta jako prywatny wpis niestandardowego typu treści z adresem e-mail, zgodą, źródłem i datą zapisu.
* Wyświetla listę subskrybentów w WooCommerce > Subskrybenci w wp-admin.
* Eksportuje całą listę do pliku CSV jednym kliknięciem (bezpieczne pod kątem wstrzyknięć CSV).
* Pomija duplikaty, więc ten sam adres e-mail nigdy nie jest zapisywany dwukrotnie.
* Przechowuje wszystko w Twojej własnej bazie danych. Nie korzysta z żadnej zewnętrznej usługi e-mail.
* Dołącza szablon tłumaczenia (.pot) i usuwa swoje dane przy odinstalowaniu.
* Deklaruje zgodność z HPOS i działa z WooCommerce 8.0 i nowszymi.

== Installation ==

1. Wgraj wtyczkę do `/wp-content/plugins/subscribe` lub zainstaluj ją przez Wtyczki > Dodaj nową.
2. Włącz ją. WooCommerce musi być najpierw zainstalowane i aktywne.
3. Otwórz WooCommerce > Subscribe, aby włączyć zapis, ustaw etykietę pola wyboru i zdecyduj, czy ma być domyślnie zaznaczone.
4. Osoby, które wyraziły zgodę, znajdziesz w WooCommerce > Subskrybenci; użyj tam przycisku „Eksportuj do CSV”, aby je pobrać.

== Frequently Asked Questions ==

= Does it need WooCommerce? =

Tak. WooCommerce musi być zainstalowane i aktywne, w przeciwnym razie wtyczka wyświetla powiadomienie i nic nie robi.

= Is the checkbox ticked by default? =

Nie. Domyślnie jest odznaczone, więc klient świadomie wyraża zgodę, czego zwykle wymaga zgoda RODO. Istnieje ustawienie pozwalające zaznaczyć je z góry, jeśli pozwalają na to lokalne przepisy, ale jest ono domyślnie wyłączone.

= Where do the subscribers end up? =

W Twojej bazie danych WordPress, jako prywatne rekordy „Subskrybent” w menu WooCommerce. Każdy przechowuje adres e-mail, flagę zgody, źródło i datę zapisu. Całość możesz wyeksportować do CSV.

= Does it push subscribers to Mailchimp or anything like that? =

Nie. Nie ma integracji z żadną zewnętrzną platformą e-mail. Adresy pozostają w Twojej witrynie i trafiają tam, gdzie sam zdecydujesz.

= Does the checkbox show on the block-based checkout? =

Zgoda renderuje się w klasycznej kasie (z shortcode). Wtyczka deklaruje zgodność z blokami koszyka i kasy, więc nie wywołuje ostrzeżenia, ale samo pole wyboru pojawia się obecnie w klasycznej kasie.


= Does this plugin work on WordPress Multisite? =

Tak. Ta wtyczka jest zgodna z WordPress Multisite. Włącz ją dla całej sieci lub w pojedynczych witrynach; każda witryna zachowuje własne ustawienia i dane.

== Screenshots ==

1. Pole wyboru zapisu do newslettera w kasie WooCommerce.
2. Lista subskrybentów z przyciskiem „Eksportuj do CSV”.

== External Services ==

Subscribe nie łączy się z żadnymi usługami zewnętrznymi. Pole wyboru zgody, rekordy zgody i eksport CSV działają w Twojej własnej witrynie, a żadne adresy e-mail ani dane zamówień nie są nigdzie poza nią wysyłane. Każdy subskrybent jest przechowywany w Twojej bazie danych WordPress jako prywatny wpis niestandardowego typu treści `subscribe_subscriber`, zawierający adres e-mail, flagę zgody, źródło i znacznik czasu zapisu; jego ustawienia znajdują się w opcji `subscribe_settings`. Wtyczka nie wysyła e-maili i nie jest powiązana z Mailchimp ani żadną inną platformą mailingową, więc to, co zrobisz z wyeksportowaną listą, zależy wyłącznie od Ciebie.

== Translations ==

Plogins Subscribe zawiera polskie, niemieckie i hiszpańskie tłumaczenia interfejsu wtyczki. Domena tekstowa to `plogins-subscribe`, dzięki czemu paczki językowe z WordPress.org mogą również nadpisywać lub rozszerzać dołączone tłumaczenia.

== Changelog ==

= 1.0.2 =
* Dodano dołączone polskie, niemieckie i hiszpańskie tłumaczenia interfejsu wtyczki.

= 1.0.1 =
* Pierwsza stabilna wersja.

= 0.1.3 =
* Zmieniono nazwę na Plogins Subscribe for WooCommerce, aby uzyskać bardziej charakterystyczną nazwę wtyczki.

= 0.1.2 =
* Dodano akcję `subscribe/checkout_after_optin` dla dodatkowych pól kasy (np. niestandardowych pól Subscribe Pro).
* Dodano filtr `subscribe/subscriber_meta` dla dodatkowej meta wpisu subskrybenta przy tworzeniu.
* Dodano filtry `subscribe/export_headers` i `subscribe/export_row` dla rozszerzeń eksportu CSV.

= 0.1.1 =
* Dodano haki `subscribe/subscriber_consent_default` i `subscribe/confirm_url` dla przepływów podwójnej zgody w wersji premium.
* Przekazuje adres URL potwierdzenia do `subscribe/subscriber_created`.

= 0.1.0 =
* Pierwsze wydanie: pole wyboru zgody przy kasie, prywatne rekordy subskrybentów przechowujące zgodę, źródło i datę oraz eksport CSV.
