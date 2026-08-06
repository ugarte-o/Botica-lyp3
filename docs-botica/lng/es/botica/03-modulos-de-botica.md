# 3. Módulos de Botica

## Inicio

```text
PHP: src/mwap/modules/pharmacy/uiadmin/welcome.php
CSS: src/public_html/res/modules/pharmacy/uiadmin/welcome.css
```

Presenta accesos rápidos. Las métricas de portada permanecen informativas y no sustituyen los reportes.

## Pedidos (`orders`)

```text
Manager:   src/mwap/modules/pharmacy/orders/man.php
Interfaz:  src/mwap/modules/pharmacy/orders/uiadmin/orders.php
CSS/JS:    src/public_html/res/modules/pharmacy/orders/
```

Flujo:

1. Lista productos activos.
2. Construye el carrito.
3. Valida cliente y cantidades.
4. Bloquea productos con `FOR UPDATE`.
5. comprueba stock.
6. Inserta pedido y detalle.
7. Descuenta stock dentro de una transacción.
8. Calcula subtotal, IGV y total.

## Cobranza (`payments`)

```text
Manager:   src/mwap/modules/pharmacy/payments/man.php
Interfaz:  src/mwap/modules/pharmacy/payments/uiadmin/home.php
CSS/JS:    src/public_html/res/modules/pharmacy/payments/
```

Gestiona pedidos pendientes, detalle, monto recibido, vuelto, método de pago, estado pagado y ticket.

Métodos actuales:

```text
Efectivo, Yape, Plin, Tarjeta, Transferencia
```

Mercado Pago no forma parte de esta versión.

## Inventario (`inventory`)

Muestra código, nombre, categoría, precio, stock, stock mínimo, vencimiento y estado. El frontend destaca stock bajo y fechas de vencimiento.

## Agregar producto (`addproduct`)

Utiliza el manager `products` para registrar productos, impedir códigos duplicados, aumentar stock y aplicar eliminación lógica con `estado = 0`.

## Reportes (`reports`)

Incluye periodos, rango personalizado, método de pago y búsqueda. Genera resúmenes, ventas, productos, clientes, métodos y tendencia diaria.
