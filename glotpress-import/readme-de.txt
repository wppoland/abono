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

Fügt dem WooCommerce-Checkout ein Kontrollkästchen zur Newsletter-Anmeldung hinzu und erfasst E-Mail-Adresse, Einwilligung und Anmeldedatum jedes Abonnenten.

== Description ==

Subscribe fügt deinem WooCommerce-Checkout ein Kontrollkästchen zur Newsletter-Anmeldung hinzu. Wenn ein
Kunde es ankreuzt und die Bestellung aufgibt, wird seine E-Mail-Adresse auf deiner eigenen Website gespeichert,
zusammen mit der Einwilligungs-Kennzeichnung, der Herkunft der Anmeldung und dem Datum. Du prüfst die
Liste unter WooCommerce und exportierst sie, wann immer du sie brauchst.

Das Kontrollkästchen ist standardmäßig nicht aktiviert, was die meisten DSGVO-Setups brauchen: Der
Kunde muss eine aktive Entscheidung treffen, um sich anzumelden. Du kannst den Beschriftungstext bearbeiten, und
es wird nie etwas an einen externen Dienst gesendet, sodass die E-Mail-Adressen in deiner
Datenbank bleiben und nirgendwo sonst.

Das Plugin ist so gebaut, dass sich der Quellcode leicht lesen und forken lässt. Wenn du auf einen Fehler stößt
oder eine Änderung vorschlagen möchtest, findest du den Code und den Issue-Tracker unter
https://github.com/wppoland/plogins-subscribe.

= Documentation and links =

* <strong>Dokumentation</strong> - https://plogins.com/de/plogins-subscribe/docs/
* <strong>Plugin-Seite</strong> - https://plogins.com/de/plogins-subscribe/
* <strong>Quellcode</strong> - https://github.com/wppoland/plogins-subscribe
* <strong>Fehlerberichte und Funktionswünsche</strong> - https://github.com/wppoland/plogins-subscribe/issues


= What it does =

* Fügt dem klassischen WooCommerce-Checkout ein Kontrollkästchen zur Newsletter-Anmeldung hinzu.
* Erfasst die Anmeldung nur, wenn der Kunde das Kästchen ankreuzt; standardmäßig ist es nicht aktiviert und die Beschriftung ist bearbeitbar.
* Speichert jeden Abonnenten als privaten benutzerdefinierten Beitragstyp mit E-Mail-Adresse, Einwilligung, Quelle und Anmeldedatum.
* Listet Abonnenten unter WooCommerce > Abonnenten in wp-admin auf.
* Exportiert die gesamte Liste mit einem Klick in eine CSV-Datei (sicher gegen CSV-Injection).
* Überspringt Duplikate, sodass dieselbe E-Mail-Adresse nie zweimal erfasst wird.
* Behält alles in deiner eigenen Datenbank. Es ist kein E-Mail-Dienst eines Drittanbieters beteiligt.
* Liefert eine Übersetzungsvorlage (.pot) mit und entfernt seine Daten bei der Deinstallation.
* Deklariert HPOS-Kompatibilität und funktioniert mit WooCommerce 8.0 und höher.

== Installation ==

1. Lade das Plugin nach `/wp-content/plugins/subscribe` hoch oder installiere es über Plugins > Installieren.
2. Aktiviere es. WooCommerce muss zuerst installiert und aktiv sein.
3. Öffne WooCommerce > Subscribe, um die Anmeldung zu aktivieren, die Beschriftung des Kontrollkästchens festzulegen und zu wählen, ob es vorausgewählt startet.
4. Die Personen, die sich angemeldet haben, findest du unter WooCommerce > Abonnenten; nutze dort den Button „In CSV exportieren“, um sie herunterzuladen.

== Frequently Asked Questions ==

= Does it need WooCommerce? =

Ja. WooCommerce muss installiert und aktiv sein, sonst zeigt das Plugin einen Hinweis an und tut nichts.

= Is the checkbox ticked by default? =

Nein. Es startet nicht aktiviert, sodass der Kunde sich bewusst anmeldet, was die DSGVO-Einwilligung in der Regel verlangt. Es gibt eine Einstellung, um es vorab anzukreuzen, falls deine lokalen Vorschriften das erlauben, aber die ist standardmäßig aus.

= Where do the subscribers end up? =

In deiner WordPress-Datenbank, als private „Abonnent“-Datensätze im WooCommerce-Menü. Jeder enthält die E-Mail-Adresse, die Einwilligungs-Kennzeichnung, die Quelle und das Anmeldedatum. Du kannst die gesamte Liste als CSV exportieren.

= Does it push subscribers to Mailchimp or anything like that? =

Nein. Es gibt keine Integration mit einer externen E-Mail-Plattform. Die Adressen bleiben auf deiner Website und gehen dorthin, wohin du sie bringst.

= Does the checkbox show on the block-based checkout? =

Das Opt-in wird im klassischen Checkout (Shortcode) gerendert. Das Plugin deklariert Kompatibilität mit den Warenkorb- und Kassenblöcken, sodass es keine Warnung auslöst, aber das Kontrollkästchen selbst erscheint derzeit im klassischen Checkout.


= Does this plugin work on WordPress Multisite? =

Ja. Dieses Plugin ist mit WordPress Multisite kompatibel. Aktiviere es netzwerkweit oder auf einzelnen Websites; jede Website behält ihre eigenen Einstellungen und Daten.

== Screenshots ==

1. Das Kontrollkästchen zur Newsletter-Anmeldung im WooCommerce-Checkout.
2. Die Abonnentenliste mit dem Button „In CSV exportieren“.

== External Services ==

Subscribe verbindet sich mit keinen externen Diensten. Das Opt-in-Kontrollkästchen, die Einwilligungsdatensätze und der CSV-Export laufen alle auf deiner eigenen Website, und es werden keine E-Mail-Adressen oder Bestelldaten irgendwohin außerhalb gesendet. Jeder Abonnent wird in deiner WordPress-Datenbank als privater benutzerdefinierter Beitragstyp `subscribe_subscriber` gespeichert, der die E-Mail-Adresse, die Einwilligungs-Kennzeichnung, die Quelle und den Anmeldezeitstempel enthält; seine Einstellungen liegen in der Option `subscribe_settings`. Das Plugin versendet keine E-Mails und ist nicht an Mailchimp oder eine andere Mailing-Plattform gebunden, sodass ganz bei dir liegt, was du mit der exportierten Liste machst.

== Translations ==

Plogins Subscribe enthält polnische, deutsche und spanische Übersetzungen für die Plugin-Oberfläche. Die Textdomain ist `plogins-subscribe`, sodass Sprachpakete von WordPress.org diese mitgelieferten Übersetzungen ebenfalls überschreiben oder erweitern können.

== Changelog ==

= 1.0.2 =
* Mitgelieferte polnische, deutsche und spanische Übersetzungen für die Plugin-Oberfläche hinzugefügt.

= 1.0.1 =
* Erste stabile Version.

= 0.1.3 =
* Umbenannt in Plogins Subscribe for WooCommerce für einen unverwechselbareren Plugin-Namen.

= 0.1.2 =
* Fügt die Aktion `subscribe/checkout_after_optin` für zusätzliche Checkout-Felder hinzu (z. B. benutzerdefinierte Subscribe-Pro-Felder).
* Fügt beim Erstellen den Filter `subscribe/subscriber_meta` für zusätzliche Abonnenten-Beitrags-Metadaten hinzu.
* Fügt die Filter `subscribe/export_headers` und `subscribe/export_row` für CSV-Export-Erweiterungen hinzu.

= 0.1.1 =
* Fügt die Hooks `subscribe/subscriber_consent_default` und `subscribe/confirm_url` für Premium-Double-Opt-in-Abläufe hinzu.
* Übergibt die Bestätigungs-URL an `subscribe/subscriber_created`.

= 0.1.0 =
* Erste Version: Checkout-Opt-in-Kontrollkästchen, private Abonnentendatensätze mit Einwilligung, Quelle und Datum sowie CSV-Export.
