# 4. Base de datos y flujo de información

## Archivos SQL

- Tablas del framework: `docs/db/`.
- Tablas de Botica: `database/pharmacy_schema.sql`.

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

## productos

Campos principales:

| Campo | Uso |
|---|---|
| `id` | Identificador interno |
| `codigo` | Código único |
| `nombre` | Nombre comercial |
| `categoria` | Clasificación |
| `precio` | Precio unitario |
| `stock` | Existencia actual |
| `stock_minimo` | Umbral de alerta |
| `fecha_vencimiento` | Control de vencimiento |
| `estado` | Activo o eliminado lógicamente |

## pedidos

Guarda datos del cliente, importes, estados y fecha. El código visible se genera después del INSERT con el formato:

```text
PED-00001
```

El campo `estado_despacho` se conserva porque el INSERT actual lo establece en `Pendiente`, aunque no exista una pantalla de despacho.

## detalle_pedido

Conserva cada producto vendido, cantidad, precio unitario histórico y subtotal. El precio histórico evita que un cambio futuro en `productos.precio` altere pedidos anteriores.

## pagos

Relaciona un pedido con su método de pago, total, monto recibido, vuelto, observación y fecha. El esquema público usa `fecha_pago`.

## Transacción de pedido

```text
BEGIN
  validar cliente y carrito
  bloquear productos
  validar productos activos y stock
  insertar pedido
  generar código PED-xxxxx
  insertar detalles
  descontar stock
COMMIT
```

Cualquier excepción ejecuta `ROLLBACK`.

## Transacción de cobranza

```text
BEGIN
  bloquear pedido
  verificar estado Pendiente
  validar monto recibido
  insertar pago
  actualizar pedido a Pagado
  recuperar detalle del ticket
COMMIT
```

## Seguridad SQL

Los valores proporcionados por el usuario se envían con `prepare()` y `bind_param()`. No concatene valores de `$_POST` o `$_GET` directamente en consultas SQL.
