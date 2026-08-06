# 8. Mantenimiento y pruebas

## Antes de cambiar código

1. Crea una copia del proyecto.
2. Exporta la base de datos.
3. Modifica un módulo a la vez.
4. Valida sintaxis.
5. Prueba el flujo completo.

## PHP

```powershell
Get-ChildItem .\src\mwap\modules\pharmacy -Recurse -Filter *.php |
ForEach-Object { php -l $_.FullName }

php -l .\src\app\init.php
```

## JavaScript

```powershell
Get-ChildItem .\src\public_htmles\modules\pharmacy -Recurse -Filter *.js |
ForEach-Object { node --check $_.FullName }
```

## Pruebas funcionales

### Productos e inventario

- Registrar producto.
- Rechazar código duplicado.
- Aumentar stock.
- Eliminar lógicamente.
- Verificar alertas y vencimientos.

### Pedidos

- Validar cliente.
- Agregar, aumentar, reducir y retirar productos.
- Impedir cantidades superiores al stock.
- Guardar el pedido.
- Confirmar descuento de stock.
- Confirmar subtotal, IGV y total.

### Cobranza

- Listar pendientes.
- Seleccionar pedido.
- Validar monto.
- Registrar método.
- Calcular vuelto.
- Cambiar a Pagado.
- Imprimir ticket.

### Reportes

- Probar periodos y rango.
- Filtrar por método.
- Buscar pedido o cliente.
- Comparar totales con `pagos`.

## Submódulos

```powershell
git submodule status --recursive
git submodule sync --recursive
git submodule update --init --recursive
```

## Antes de publicar

```powershell
git status --short
git diff --cached
```

La prueba desde una instalación limpia queda como validación final del propietario del proyecto.
