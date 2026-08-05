# 3. Módulos de Botica

## Inicio

Archivo PHP:

```text
src/mwap/modules/pharmacy/uiadmin/welcome.php
```

Recurso visual:

```text
src/public_html/res/modules/pharmacy/uiadmin/welcome.css
```

Presenta accesos rápidos a las cinco áreas. Actualmente muestra valores informativos sin consultar métricas reales en la portada.

## Pedidos (`orders`)

Manager:

```text
src/mwap/modules/pharmacy/orders/man.php
```

Pantalla y recursos:

```text
src/mwap/modules/pharmacy/orders/uiadmin/orders.php
src/public_html/res/modules/pharmacy/orders/ui.css
src/public_html/res/modules/pharmacy/orders/ui.js
```

Responsabilidades:

- Listar productos activos.
- Validar nombre, DNI, teléfono y dirección.
- Consolidar cantidades del carrito.
- Bloquear productos con `FOR UPDATE`.
- Verificar stock.
- Crear pedido y detalle.
- Descontar stock dentro de una transacción.
- Calcular subtotal, IGV y total.

## Cobranza (`payments`)

Responsabilidades:

- Listar pedidos con estado `Pendiente`.
- Mostrar detalle del pedido.
- Validar método y monto recibido.
- Registrar el pago.
- Cambiar el pedido a `Pagado`.
- Calcular vuelto.
- Devolver los datos del ticket para impresión.

Métodos admitidos:

```text
Efectivo, Yape, Plin, Tarjeta, Transferencia
```

## Inventario (`inventory`)

Consulta todos los productos y muestra:

- código,
- nombre,
- categoría,
- precio,
- stock,
- stock mínimo,
- fecha de vencimiento,
- estado.

El frontend clasifica visualmente stock bajo y vencimientos.

## Agregar producto (`addproduct`)

El manager de productos permite:

- registrar un producto,
- impedir códigos duplicados,
- aumentar stock con transacción,
- eliminar lógicamente mediante `estado = 0`,
- validar precio, stock y fecha.

La eliminación lógica conserva el historial de pedidos.

## Reportes (`reports`)

Incluye filtros por:

- hoy,
- semana,
- mes,
- periodo personalizado,
- método de pago,
- código, cliente o documento.

Genera resumen, ventas, productos más vendidos, clientes principales, distribución por métodos y tendencia diaria. El límite de la tabla detallada es de 500 ventas por consulta.
