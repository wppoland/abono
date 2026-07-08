=== Plogins Subscribe - Newsletter Signup for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, newsletter, opt-in, gdpr, checkout
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Requiere complementos: woocommerce
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Añade una casilla de verificación de suscripción al boletín al proceso de pago de WooCommerce y registra el correo electrónico, el consentimiento y la fecha de registro de cada suscriptor.

== Description ==

Suscribirse coloca una casilla de verificación de suscripción al boletín informativo en su pago de WooCommerce. cuando un
el cliente lo marca y realiza el pedido, su correo electrónico se guarda en tu propio sitio
junto con el indicador de consentimiento, el origen de la suscripción y la fecha. tu revisas
la lista en WooCommerce y expórtela cuando la necesite.

La casilla de verificación está desmarcada de forma predeterminada, que es lo que necesitan la mayoría de las configuraciones de GDPR: el
El cliente debe tomar una decisión activa para participar. Puede editar el texto de la etiqueta y
nunca se envía nada a un servicio externo, por lo que las direcciones de correo electrónico permanecen en su
base de datos y en ningún otro lugar.

El complemento está diseñado para que la fuente sea fácil de leer y bifurcar. Si encuentras un error
o desea sugerir un cambio, el código y el rastreador de problemas están disponibles en
https://github.com/wppoland/plogins-subscribe.

= Documentation and links =

* <strong>Documentación</strong> - https://plogins.com/es/plogins-subscribe/docs/
* <strong>Página de complementos</strong> - https://plogins.com/es/plogins-subscribe/
* <strong>Código fuente</strong> - https://github.com/wppoland/plogins-subscribe
* <strong>Informes de errores y solicitudes de funciones</strong> - https://github.com/wppoland/plogins-subscribe/issues


= What it does =

* Añade una casilla de verificación de suscripción al boletín al proceso de pago clásico de WooCommerce.
* Registra la suscripción solo cuando el cliente marca la casilla; está desmarcado de forma predeterminada y la etiqueta es editable.
* Guarda a cada suscriptor como un registro privado de tipo de publicación personalizada con correo electrónico, consentimiento, fuente y fecha de registro.
* Enumera los suscriptores en WooCommerce > Suscriptores en wp-admin.
* Exporta la lista completa a un archivo CSV con un solo clic (seguro para inyección CSV).
* Omite duplicados, por lo que el mismo correo electrónico nunca se registra dos veces.
* Mantiene todo en su propia base de datos. No interviene ningún servicio de correo electrónico de terceros.
* Envía una plantilla de traducción (.pot) y elimina sus datos al desinstalarla.
* Declara compatibilidad con HPOS y funciona con WooCommerce 8.0 y versiones posteriores.

== Installation ==

1. Cargue el complemento en `/wp-content/plugins/subscribe`, o instálelo desde Complementos > Añadir nuevo.
2. Actívalo. WooCommerce debe estar instalado y activo primero.
3. Abra WooCommerce > Suscribirse para activar la suscripción, configure la etiqueta de la casilla de verificación y elija si comienza marcada.
4. Busque las personas que optaron por registrarse en WooCommerce > Suscriptores y use el botón Exportar a CSV allí para descargarlas.

== Frequently Asked Questions ==

= Does it need WooCommerce? =

Sí. WooCommerce debe estar instalado y activo; de lo contrario, el complemento muestra un aviso y no hace nada.

= Is the checkbox ticked by default? =

No. Comienza sin marcar, por lo que el cliente se registra intencionalmente, que es lo que generalmente requiere el consentimiento del RGPD. Hay una configuración para marcarlo previamente si las reglas locales lo permiten, pero eso está fuera de lo común.

= Where do the subscribers end up? =

En tu base de datos de WordPress, como registros privados de "Suscriptor" en el menú de WooCommerce. Cada uno contiene el correo electrónico, la bandera de consentimiento, la fuente y la fecha de registro. Puede exportar el lote a CSV.

= Does it push subscribers to Mailchimp or anything like that? =

No. No existe integración con ninguna plataforma de correo electrónico externa. Las direcciones permanecen en tu sitio y van a donde decidas llevarlas.

= Does the checkbox show on the block-based checkout? =

La suscripción se muestra en el pago clásico (código abreviado). El complemento declara compatibilidad con el carrito y los bloques de pago, por lo que no genera una advertencia, pero la casilla de verificación aparece actualmente en el pago clásico.


= Does this plugin work on WordPress Multisite? =

Sí. Este complemento es compatible con WordPress Multisite. Activarlo en red o activarlo en sitios individuales; Cada sitio mantiene su propia configuración y datos.

== Screenshots ==

1. La casilla de verificación de suscripción al boletín en el proceso de pago de WooCommerce.
2. La lista de Suscriptores con el botón Exportar a CSV.

== External Services ==

Suscríbete no se conecta a ningún servicio externo. La casilla de verificación de suscripción, los registros de consentimiento y la exportación CSV se ejecutan en tu propio sitio, y no se envían direcciones de correo electrónico ni datos de pedidos a ninguna parte fuera de él. Cada suscriptor se almacena en tu base de datos de WordPress como un registro privado de tipo de publicación personalizado "subscribe_subscriber" que contiene el correo electrónico, el indicador de consentimiento, la fuente y la marca de tiempo de registro; tu configuración se encuentra en la opción "subscribe_settings". El complemento no envía correo electrónico y no está vinculado a Mailchimp ni a ninguna otra plataforma de correo, por lo que lo que haga con la lista exportada depende totalmente de ti.

== Changelog ==

= 1.0.1 =
* Primera versión estable.

= 0.1.3 =
* Renombrado a Plogins Suscríbete a WooCommerce para obtener un nombre de complemento más distintivo.

= 0.1.2 =
* Añade la acción `subscribe/checkout_after_optin` para campos de pago adicionales (por ejemplo, campos personalizados de Subscribe Pro).
* Añade el filtro `subscribe/subscriber_meta` para meta meta de publicación de suscriptor adicional al crear.
* Añade filtros `subscribe/export_headers` y `subscribe/export_row` para extensiones de exportación CSV.

= 0.1.1 =
* Añade ganchos `subscribe/subscriber_consent_default` y `subscribe/confirm_url` para flujos premium de doble suscripción.
* Pasa la URL de confirmación a `subscribe/subscriber_created`.

= 0.1.0 =
* Primera versión: casilla de verificación de suscripción, registros privados de suscriptores que almacenan el consentimiento, la fuente y la fecha, y exportación CSV.
