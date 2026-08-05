# Cambios realizados para la versión pública

Fecha: 5 de agosto de 2026.

## Documentación

- Se reemplazaron referencias antiguas a `botica`, `docs8` y `Botica(31)` por la estructura real de `Botica(33)`.
- Se documentó el módulo actual `pharmacy` y sus rutas `orders`, `payments`, `inventory`, `addproduct` y `reports`.
- Se explicó la función de `ap.php`, `mainMan`, managers, interfaces, CSS y JavaScript.
- Se añadieron instrucciones de instalación, seguridad, mantenimiento y publicación en GitHub.
- Se retiró el archivo Word de la copia pública; la documentación oficial queda en Markdown dentro de `docs-botica`.

## Seguridad

- Se eliminaron de la copia pública las credenciales locales de MySQL.
- Se eliminaron claves de instalación y configuración SMTP local.
- Se agregaron `db.example.php`, `install.example.php` y `sysmail.example.php`.
- Se actualizó `.gitignore` para impedir que los archivos privados se publiquen accidentalmente.
- Se desactivó el modo debug por defecto.
- Se retiraron respaldos `.bak-tunnel`.

## Base de datos

- Se añadió `database/pharmacy_schema.sql` con las tablas `productos`, `pedidos`, `detalle_pedido` y `pagos`.
- Se agregaron claves, índices, relaciones y restricciones compatibles con la lógica actual.

## GitHub

- Se eliminó el historial Git heredado del proyecto original.
- Se creó un repositorio Git nuevo con un único commit limpio.
- Se conservaron las dependencias oficiales de Meralda como submódulos.
- Se añadió `scripts/publicar-github.ps1` para configurar el remoto, descargar submódulos y publicar.
- Se agregaron `SECURITY.md`, `CONTRIBUTING.md` y `NOTICE.md`.

## Independencia de Demo

- Se retiró `example/demo` de la versión pública.
- Se confirmó que la aplicación activa extiende `mwap_pharmacy_ap`.
- Se confirmó que las rutas PHP y los recursos públicos usan `pharmacy`.
- El módulo `demo` que pueda existir dentro del núcleo `mw` pertenece al framework y no es una dependencia de Botica.

## Validaciones

- 25 archivos PHP revisados sin errores de sintaxis.
- 5 archivos JavaScript revisados sin errores de sintaxis.
- Búsqueda de las credenciales locales conocidas sin coincidencias.
- Repositorio Git verificado sin remoto heredado y sin archivos privados rastreados.
