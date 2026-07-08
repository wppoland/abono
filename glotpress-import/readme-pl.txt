=== Plogins Subscribe - Newsletter Signup for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, newsletter, opt-in, gdpr, checkout
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Wymaga wtyczek: woocommerce
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Dodaje pole wyboru subskrypcji biuletynu do kasy WooCommerce i rejestruje adres e-mail, zgodę i datę rejestracji każdego subskrybenta.

== Description ==

Subskrybuj umieszcza pole wyboru subskrypcji biuletynu przy kasie WooCommerce. Kiedy A
Klient zaznacza to i składa zamówienie, a jego e-mail zostaje zapisany na Twojej stronie
wraz z flagą zgody, skąd pochodzi wyrażenie zgody i datą. Recenzujesz
listę w WooCommerce i eksportuj ją, kiedy tylko jej potrzebujesz.

Pole wyboru jest domyślnie niezaznaczone, a tego wymaga większość konfiguracji RODO:
Aby wyrazić zgodę, klient musi dokonać aktywnego wyboru. Możesz edytować tekst etykiety i
nic nie jest nigdy wysyłane do usługi zewnętrznej, więc adresy e-mail pozostają w Twoim
bazy danych i nigdzie indziej.

Wtyczka została zbudowana tak, aby źródło było łatwe do odczytania i rozwidlenia. Jeśli trafisz na błąd
lub chcesz zasugerować zmianę, moduł śledzenia kodu i problemów jest dostępny na stronie
https://github.com/wppoland/plogins-subscribe.

= Documentation and links =

* <strong>Dokumentacja</strong> - https://plogins.com/pl/plogins-subscribe/docs/
* <strong>Strona wtyczki</strong> - https://plogins.com/pl/plogins-subscribe/
* <strong>Kod źródłowy</strong> - https://github.com/wppoland/plogins-subscribe
* <strong>Raporty o błędach i prośby o nowe funkcje</strong> - https://github.com/wppoland/plogins-subscribe/issues


= What it does =

* Dodaje pole wyboru subskrypcji biuletynu do klasycznej kasy WooCommerce.
* Rejestruje zgodę tylko wtedy, gdy klient zaznaczy to pole; domyślnie jest ono odznaczone i etykietę można edytować.
* Zapisuje każdego subskrybenta jako prywatny, niestandardowy rekord typu post z adresem e-mail, zgodą, źródłem i datą rejestracji.
* Wyświetla listę subskrybentów w obszarze WooCommerce > Subskrybenci w wp-admin.
* Eksportuje całą listę do pliku CSV jednym kliknięciem (bezpieczne wstrzykiwanie CSV).
* Pomija duplikaty, więc ten sam e-mail nigdy nie jest nagrywany dwukrotnie.
* Przechowuje wszystko we własnej bazie danych. Nie jest zaangażowana żadna usługa e-mail innej firmy.
* Dostarcza szablon tłumaczenia (.pot) i usuwa jego dane po odinstalowaniu.
* Deklaruje kompatybilność z HPOS i współpracuje z WooCommerce 8.0 i nowszymi wersjami.

== Installation ==

1. Prześlij wtyczkę do `/wp-content/plugins/subscribe` lub zainstaluj ją, wybierając Wtyczki > Dodaj nową.
2. Aktywuj. WooCommerce musi być najpierw zainstalowany i aktywny.
3. Otwórz WooCommerce > Subskrybuj, aby włączyć opcję zgody, ustaw etykietę pola wyboru i wybierz, czy ma ono być zaznaczone.
4. Znajdź osoby, które wyraziły zgodę w obszarze WooCommerce > Subskrybenci i użyj znajdującego się tam przycisku Eksportuj do pliku CSV, aby je pobrać.

== Frequently Asked Questions ==

= Does it need WooCommerce? =

Tak. WooCommerce musi być zainstalowany i aktywny, w przeciwnym razie wtyczka wyświetli powiadomienie i nic nie zrobi.

= Is the checkbox ticked by default? =

Nie. Zaczyna się niezaznaczone, więc klient wyraża zgodę celowo, czego zazwyczaj wymaga zgoda RODO. Istnieje możliwość wstępnego zaznaczenia tej opcji, jeśli pozwalają na to lokalne przepisy, ale nie jest to dostępne od razu po wyjęciu z pudełka.

= Where do the subscribers end up? =

W Twojej bazie danych WordPress, jako prywatne rekordy „Abonent” w menu WooCommerce. Każdy zawiera adres e-mail, flagę zgody, źródło i datę rejestracji. Możesz wyeksportować partię do pliku CSV.

= Does it push subscribers to Mailchimp or anything like that? =

Nie. Nie ma integracji z żadną zewnętrzną platformą e-mail. Adresy pozostają na Twojej stronie i trafiają tam, gdzie zdecydujesz się je zabrać.

= Does the checkbox show on the block-based checkout? =

Opcja opt-in renderuje się w klasycznej kasie (z krótkim kodem). Wtyczka deklaruje kompatybilność z blokami koszyka i kasy, więc nie wywołuje ostrzeżenia, ale samo pole wyboru aktualnie pojawia się na klasycznej kasie.


= Does this plugin work on WordPress Multisite? =

Tak. Ta wtyczka jest kompatybilna z WordPress Multisite. Aktywuj go w sieci lub aktywuj na poszczególnych stronach; każda witryna przechowuje własne ustawienia i dane.

== Screenshots ==

1. Pole wyboru subskrypcji biuletynu w kasie WooCommerce.
2. Lista Abonentów z przyciskiem Eksportuj do CSV.

== External Services ==

Subskrypcja nie łączy się z żadnymi usługami zewnętrznymi. Pole wyboru zgody, rekordy zgody i eksport CSV działają na Twojej własnej stronie i żadne adresy e-mail ani dane dotyczące zamówień nie są nigdzie wysyłane poza nią. Każdy subskrybent jest przechowywany w Twojej bazie danych WordPress jako prywatny, niestandardowy rekord typu postu „subscribe_subscriber”, zawierający adres e-mail, flagę zgody, źródło i znacznik czasu rejestracji; jego ustawienia znajdują się w opcji „subscribe_settings”. Wtyczka nie wysyła wiadomości e-mail i nie jest powiązana z Mailchimp ani żadną inną platformą mailingową, więc to, co zrobisz z wyeksportowaną listą, zależy wyłącznie od Ciebie.

== Changelog ==

= 1.0.1 =
* Pierwsza stabilna wersja.

= 0.1.3 =
* Zmieniono nazwę na Plogins Subskrybuj WooCommerce, aby uzyskać bardziej charakterystyczną nazwę wtyczki.

= 0.1.2 =
* Dodaje akcję `subskrybuj/checkout_after_optin` dla dodatkowych pól kasy (np. niestandardowych pól Subskrybuj Pro).
* Dodaje filtr `subscribe/subscriber_meta` dla dodatkowej meta postu subskrybenta podczas tworzenia.
* Dodaje filtry `subscribe/export_headers` i `subscribe/export_row` dla rozszerzeń eksportu CSV.

= 0.1.1 =
* Dodaje haczyki `subscribe/subscriber_consent_default` i `subscribe/confirm_url` dla przepływów podwójnej zgody premium.
* Przekazuje adres URL potwierdzenia do `subscribe/subscriber_created`.

= 0.1.0 =
* Pierwsza wersja: pole wyboru umożliwiające dokonanie zakupu, prywatne zapisy subskrybentów zawierające zgodę, źródło i datę oraz eksport CSV.
