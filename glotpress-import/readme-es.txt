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

Añade una casilla de suscripción al boletín a la página de pago de WooCommerce y registra el correo electrónico, el consentimiento y la fecha de suscripción de cada suscriptor.

== Description ==

Subscribe coloca una casilla de suscripción al boletín en tu página de pago de WooCommerce. Cuando un
cliente la marca y realiza el pedido, su correo electrónico se guarda en tu propio sitio
junto con el indicador de consentimiento, el origen de la suscripción y la fecha. Revisas la
lista en WooCommerce y la exportas cuando la necesitas.

La casilla está desmarcada de forma predeterminada, que es lo que necesitan la mayoría de las configuraciones del RGPD: el
cliente tiene que tomar una decisión activa para suscribirse. Puedes editar el texto de la etiqueta y
nunca se envía nada a un servicio externo, así que las direcciones de correo electrónico permanecen en tu
base de datos y en ningún otro sitio.

El plugin está pensado para que el código fuente sea fácil de leer y de bifurcar. Si encuentras un error
o quieres sugerir un cambio, el código y el gestor de incidencias están en
https://github.com/wppoland/plogins-subscribe.

= Documentation and links =

* <strong>Documentación</strong> - https://plogins.com/es/plogins-subscribe/docs/
* <strong>Página del plugin</strong> - https://plogins.com/es/plogins-subscribe/
* <strong>Código fuente</strong> - https://github.com/wppoland/plogins-subscribe
* <strong>Informes de errores y peticiones de funciones</strong> - https://github.com/wppoland/plogins-subscribe/issues


= What it does =

* Añade una casilla de suscripción al boletín a la página de pago clásica de WooCommerce.
* Registra la suscripción solo cuando el cliente marca la casilla; está desmarcada de forma predeterminada y la etiqueta es editable.
* Guarda a cada suscriptor como un registro privado de tipo de contenido personalizado con correo electrónico, consentimiento, origen y fecha de suscripción.
* Muestra la lista de suscriptores en WooCommerce > Suscriptores en wp-admin.
* Exporta la lista completa a un archivo CSV con un solo clic (segura ante inyección de CSV).
* Omite los duplicados, así que el mismo correo electrónico nunca se registra dos veces.
* Mantiene todo en tu propia base de datos. No interviene ningún servicio de correo electrónico de terceros.
* Incluye una plantilla de traducción (.pot) y elimina sus datos al desinstalarse.
* Declara compatibilidad con HPOS y funciona con WooCommerce 8.0 y versiones posteriores.

== Installation ==

1. Sube el plugin a `/wp-content/plugins/subscribe` o instálalo desde Plugins > Añadir nuevo.
2. Actívalo. WooCommerce debe estar instalado y activo primero.
3. Abre WooCommerce > Subscribe para activar la suscripción, define la etiqueta de la casilla y elige si empieza marcada.
4. Encuentra a las personas que se suscribieron en WooCommerce > Suscriptores y usa ahí el botón «Exportar a CSV» para descargarlas.

== Frequently Asked Questions ==

= Does it need WooCommerce? =

Sí. WooCommerce debe estar instalado y activo; de lo contrario, el plugin muestra un aviso y no hace nada.

= Is the checkbox ticked by default? =

No. Empieza desmarcada, así que el cliente se suscribe a propósito, que es lo que suele exigir el consentimiento del RGPD. Hay un ajuste para marcarla de antemano si tus normas locales lo permiten, pero está desactivado de fábrica.

= Where do the subscribers end up? =

En tu base de datos de WordPress, como registros privados «Suscriptor» en el menú de WooCommerce. Cada uno guarda el correo electrónico, el indicador de consentimiento, el origen y la fecha de suscripción. Puedes exportar el conjunto a CSV.

= Does it push subscribers to Mailchimp or anything like that? =

No. No hay integración con ninguna plataforma de correo electrónico externa. Las direcciones permanecen en tu sitio y van adonde tú decidas llevarlas.

= Does the checkbox show on the block-based checkout? =

El opt-in se muestra en la página de pago clásica (shortcode). El plugin declara compatibilidad con los bloques de carrito y de pago, así que no genera una advertencia, pero la propia casilla aparece por ahora en la página de pago clásica.


= Does this plugin work on WordPress Multisite? =

Sí. Este plugin es compatible con WordPress Multisite. Actívalo para toda la red o en sitios concretos; cada sitio conserva sus propios ajustes y datos.

== Screenshots ==

1. La casilla de suscripción al boletín en la página de pago de WooCommerce.
2. La lista de suscriptores con el botón «Exportar a CSV».

== External Services ==

Subscribe no se conecta a ningún servicio externo. La casilla de suscripción, los registros de consentimiento y la exportación a CSV se ejecutan en tu propio sitio, y no se envían direcciones de correo electrónico ni datos de pedidos a ningún lugar fuera de él. Cada suscriptor se almacena en tu base de datos de WordPress como un registro privado de tipo de contenido personalizado `subscribe_subscriber` que contiene el correo electrónico, el indicador de consentimiento, el origen y la marca de tiempo de la suscripción; sus ajustes se guardan en la opción `subscribe_settings`. El plugin no envía correo electrónico y no está vinculado a Mailchimp ni a ninguna otra plataforma de correo, así que lo que hagas con la lista exportada depende por completo de ti.

== Translations ==

Plogins Subscribe incluye traducciones al polaco, al alemán y al español para la interfaz del plugin. El dominio de texto es `plogins-subscribe`, por lo que los paquetes de idioma de WordPress.org también pueden sustituir o ampliar estas traducciones incluidas.

== Changelog ==

= 1.0.2 =
* Se han añadido traducciones incluidas al polaco, al alemán y al español para la interfaz del plugin.

= 1.0.1 =
* Primera versión estable.

= 0.1.3 =
* Renombrado a Plogins Subscribe for WooCommerce para tener un nombre de plugin más distintivo.

= 0.1.2 =
* Añade la acción `subscribe/checkout_after_optin` para campos de pago adicionales (por ejemplo, campos personalizados de Subscribe Pro).
* Añade el filtro `subscribe/subscriber_meta` para metainformación adicional de la entrada del suscriptor al crearla.
* Añade los filtros `subscribe/export_headers` y `subscribe/export_row` para extensiones de exportación a CSV.

= 0.1.1 =
* Añade los hooks `subscribe/subscriber_consent_default` y `subscribe/confirm_url` para flujos premium de doble suscripción.
* Pasa la URL de confirmación a `subscribe/subscriber_created`.

= 0.1.0 =
* Primera versión: casilla de suscripción en la página de pago, registros privados de suscriptores que almacenan el consentimiento, el origen y la fecha, y exportación a CSV.
