# 4. Base de datos y flujo de información

## Archivos SQL disponibles

```text
docs-botica/db/meralda_base_tables.sql   tablas base del framework
docs-botica/db/initial_user.sql          usuario inicial opcional
database/pharmacy_schema.sql             tablas propias de Botica
```

La instalación paso a paso está en [10-instalacion-base-de-datos.md](10-instalacion-base-de-datos.md).

## Tablas propias

```text
productos
pedidos
detalle_pedido
pagos
```

## Relaciones

```text
productos 1 ─── N detalle_pedido N ─── 1 pedidos 1 ─── 1 pagos
```

## Productos

Conserva código único, nombre, categoría, precio, stock, stock mínimo, vencimiento, estado y marcas de tiempo.

## Pedidos

Conserva datos del cliente, importes, estados y fecha. El código visible usa el formato:

```text
PED-00001
```

`estado_despacho` permanece en el esquema por compatibilidad, aunque la aplicación actual no tenga una pantalla de despacho.

## Detalle del pedido

Conserva producto, cantidad, precio unitario histórico y subtotal. El precio guardado evita alterar ventas anteriores cuando cambia el precio actual del producto.

## Pagos

Relaciona un pedido con método, total, monto recibido, vuelto, observación y fecha. Existe una restricción única por `pedido_id` para impedir dos pagos del mismo pedido.

## Transacción de pedido

```text
BEGIN
  validar cliente y carrito
  bloquear productos
  verificar estado y stock
  insertar pedido
  generar código
  insertar detalles
  descontar stock
COMMIT
```

Ante una excepción se ejecuta `ROLLBACK`.

## Transacción de cobranza

```text
BEGIN
  bloquear pedido
  verificar estado Pendiente
  validar monto
  insertar pago
  actualizar pedido a Pagado
  recuperar ticket
COMMIT
```

## Acceso SQL

Los managers usan consultas preparadas. Los valores de `$_POST` y `$_GET` no deben concatenarse directamente en SQL.
