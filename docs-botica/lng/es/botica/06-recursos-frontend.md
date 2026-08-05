# 6. CSS, JavaScript y recursos públicos

## Ubicación

```text
src/public_html/res/modules/pharmacy/
├── uiadmin/welcome.css
├── orders/ui.css
├── orders/ui.js
├── payments/ui.css
├── payments/ui.js
├── inventory/ui.css
├── inventory/ui.js
├── products/ui.css
├── products/ui.js
├── reports/ui.css
└── reports/ui.js
```

## Qué hace PHP

La clase `uiadmin` prepara la página, obtiene datos desde un manager y genera el HTML inicial. También registra los archivos CSS y JavaScript con rutas públicas.

## Qué hace JavaScript

- Pedidos: carrito, cantidades, filtros y envío del formulario.
- Cobranza: selección del pedido, cálculo visual, ticket e impresión.
- Inventario: búsqueda, filtros y estados visuales.
- Productos: formularios de registro, aumento de stock y eliminación.
- Reportes: filtros, gráficos, tablas y exportación disponible en la interfaz.

JavaScript mejora la experiencia, pero las validaciones decisivas se repiten en PHP.

## Qué hace CSS

Cada módulo posee su propio archivo para evitar que una pantalla modifique accidentalmente otra. Los recursos de Botica no se guardan dentro de carpetas Demo.

## Caché

Después de cambiar CSS o JavaScript:

```text
Ctrl + F5
```

También puede incrementarse una versión en la URL del recurso, por ejemplo `?v=4` a `?v=5`.

## Regla de seguridad

No guardes contraseñas, tokens ni credenciales en JavaScript, HTML, `localStorage` o CSS. Todo secreto debe permanecer en configuración privada del servidor.
