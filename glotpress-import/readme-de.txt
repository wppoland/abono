=== Plogins Subscribe - Newsletter Signup for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, newsletter, opt-in, gdpr, checkout
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Erfordert Plugins: woocommerce
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Fügt dem WooCommerce-Checkout ein Kontrollkästchen für die Newsletter-Anmeldung hinzu und zeichnet die E-Mail-Adresse, die Einwilligung und das Anmeldedatum jedes Abonnenten auf.

== Description ==

„Abonnieren“ fügt in deinem WooCommerce-Checkout ein Kontrollkästchen zur Newsletter-Anmeldung ein. Wenn ein
Wenn der Kunde es ankreuzt und die Bestellung aufgibt, wird seine E-Mail-Adresse auf deiner eigenen Website gespeichert
zusammen mit der Zustimmungsflagge, woher das Opt-in kam und dem Datum. Sie bewerten
Klicke auf die Liste unter WooCommerce und exportiere sie, wann immer Sie sie benötigen.

Das Kontrollkästchen ist standardmäßig deaktiviert, was die meisten DSGVO-Setups benötigen: das
Der Kunde muss eine aktive Entscheidung treffen, um sich anzumelden. Du kannst den Etikettentext bearbeiten und
Es wird nie etwas an einen externen Dienst gesendet, daher bleiben die E-Mail-Adressen bei dir
Datenbank und nirgendwo sonst.

Das Plugin ist so konzipiert, dass die Quelle leicht zu lesen und zu verzweigen ist. Wenn du auf einen Fehler stoßen
oder eine Änderung vorschlagen möchten, findest du den Code- und Issue-Tracker live unter
https://github.com/wppoland/plogins-subscribe.

= Documentation and links =

* <strong>Dokumentation</strong> - https://plogins.com/de/plogins-subscribe/docs/
* <strong>Plugin-Seite</strong> - https://plogins.com/de/plogins-subscribe/
* <strong>Quellcode</strong> – https://github.com/wppoland/plogins-subscribe
* <strong>Fehlerberichte und Funktionsanfragen</strong> – https://github.com/wppoland/plogins-subscribe/issues


= What it does =

* Fügt dem klassischen WooCommerce-Checkout ein Kontrollkästchen zur Newsletter-Anmeldung hinzu.
* Zeichnet das Opt-in nur auf, wenn der Kunde das Kästchen ankreuzt; Es ist standardmäßig deaktiviert und die Beschriftung kann bearbeitet werden.
* Speichert jeden Abonnenten als privaten benutzerdefinierten Beitragstyp-Datensatz mit E-Mail-Adresse, Einwilligung, Quelle und Anmeldedatum.
* Listet Abonnenten unter WooCommerce > Abonnenten in wp-admin auf.
* Exportiert die gesamte Liste mit einem Klick in eine CSV-Datei (CSV-Injection Safe).
* Überspringt Duplikate, sodass dieselbe E-Mail nie zweimal aufgezeichnet wird.
* Behält alles in deiner eigenen Datenbank. Es ist kein E-Mail-Dienst eines Drittanbieters beteiligt.
* Versendet eine Übersetzungsvorlage (.pot) und entfernt deren Daten bei der Deinstallation.
* Erklärt HPOS-Kompatibilität und funktioniert mit WooCommerce 8.0 und höher.

== Installation ==

1. Lade das Plugin nach „/wp-content/plugins/subscribe“ hoch oder installiere es über Plugins > Neu hinzufügen.
2. Aktiviere es. WooCommerce muss zuerst installiert und aktiv sein.
3. Öffne WooCommerce > Abonnieren, um das Opt-in zu aktivieren, lege die Beschriftung des Kontrollkästchens fest und lege fest, ob es aktiviert werden soll.
4. Suche unter WooCommerce > Abonnenten nach den Personen, die sich angemeldet haben, und nutze dort die Schaltfläche „In CSV exportieren“, um sie herunterzuladen.

== Frequently Asked Questions ==

= Does it need WooCommerce? =

Ja. WooCommerce muss installiert und aktiv sein, ansonsten zeigt das Plugin einen Hinweis an und tut nichts.

= Is the checkbox ticked by default? =

Nein. Das Kontrollkästchen ist zu Beginn nicht aktiviert, sodass der Kunde sich absichtlich dafür entscheidet, was im Allgemeinen für die DSGVO-Einwilligung erforderlich ist. Es gibt eine Einstellung, um es vorab anzukreuzen, wenn deine lokalen Regeln dies zulassen, aber das ist standardmäßig deaktiviert.

= Where do the subscribers end up? =

In deiner WordPress-Datenbank als private „Abonnenten“-Einträge im WooCommerce-Menü. Jeder enthält die E-Mail-Adresse, das Einwilligungskennzeichen, die Quelle und das Anmeldedatum. Du kannst das Los in CSV exportieren.

= Does it push subscribers to Mailchimp or anything like that? =

Nein. Es gibt keine Integration mit einer externen E-Mail-Plattform. Die Adressen bleiben auf deiner Website und werden dort angezeigt, wo Sie sie verwenden möchten.

= Does the checkbox show on the block-based checkout? =

Das Opt-in wird beim klassischen Checkout (Shortcode) gerendert. Das Plugin erklärt die Kompatibilität mit den Warenkorb- und Checkout-Blöcken, sodass es keine Warnung auslöst, aber das Kontrollkästchen selbst erscheint derzeit im klassischen Checkout.


= Does this plugin work on WordPress Multisite? =

Ja. Dieses Plugin ist mit WordPress Multisite kompatibel. Aktiviere es im Netzwerk oder auf einzelnen Websites. Jede Site behält ihre eigenen Einstellungen und Daten.

== Screenshots ==

1. Das Kontrollkästchen für die Newsletter-Anmeldung im WooCommerce-Checkout.
2. Die Abonnentenliste mit der Schaltfläche „In CSV exportieren“.

== External Services ==

„Abonnieren“ stellt keine Verbindung zu externen Diensten her. Das Opt-in-Kontrollkästchen, die Einwilligungsdatensätze und der CSV-Export laufen alle auf deiner eigenen Website, und es werden keine E-Mail-Adressen oder Bestelldaten irgendwohin gesendet. Jeder Abonnent wird in deiner WordPress-Datenbank als privater benutzerdefinierter Beitragstyp-Datensatz „subscribe_subscriber“ gespeichert, der die E-Mail-Adresse, das Zustimmungsflag, die Quelle und den Anmeldezeitstempel enthält. Seine Einstellungen befinden sich in der Option „subscribe_settings“. Das Plugin versendet keine E-Mails und ist nicht an Mailchimp oder eine andere Mailing-Plattform gebunden. Was du mit der exportierten Liste machen, liegt also ganz bei dir.

== Changelog ==

= 1.0.1 =
* Erste stabile Version.

= 0.1.3 =
* Umbenannt in „Plogins Subscribe for WooCommerce“, um einen eindeutigeren Plugin-Namen zu erhalten.

= 0.1.2 =
* Fügt die Aktion „subscribe/checkout_after_optin“ für zusätzliche Checkout-Felder hinzu (z. B. benutzerdefinierte Subscribe Pro-Felder).
* Fügt beim Erstellen den Filter „subscribe/subscriber_meta“ für zusätzliche Metadaten für Abonnentenbeiträge hinzu.
* Fügt die Filter „subscribe/export_headers“ und „subscribe/export_row“ für CSV-Exporterweiterungen hinzu.

= 0.1.1 =
* Fügt die Hooks „subscribe/subscriber_consent_default“ und „subscribe/confirm_url“ für Premium-Double-Opt-In-Flows hinzu.
* Übergibt die Bestätigungs-URL an „subscribe/subscriber_created“.

= 0.1.0 =
* Erste Version: Checkout-Opt-in-Kontrollkästchen, private Abonnentendatensätze mit Speicherung von Einwilligung, Quelle und Datum sowie CSV-Export.
