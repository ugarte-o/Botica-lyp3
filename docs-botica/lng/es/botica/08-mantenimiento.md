# 8. Mantenimiento y pruebas

## Antes de modificar

1. Crea una copia ZIP.
2. Exporta la base de datos.
3. Cambia un módulo a la vez.
4. Valida sintaxis.
5. Prueba el flujo completo.

## Validar PHP

```powershell
Get-ChildItem .\src\mwap\modules\pharmacy -Recurse -Filter *.php |
ForEach-Object { php -l $_.FullName }

php -l .\src\app\init.php
```

## Validar JavaScript

```powershell
Get-ChildItem .\src\public_html\res\modules\pharmacy -Recurse -Filter *.js |
ForEach-Object { node --check $_.FullName }
```

## Pruebas funcionales

### Productos e inventario

- Registrar un producto válido.
- Rechazar código duplicado.
- Aumentar stock.
- Eliminar lógicamente.
- Mostrar stock bajo.
- Mostrar vencimientos.

### Pedidos

- Validar DNI de 8 dígitos.
- Validar teléfono peruano de 9 dígitos que inicia en 9.
- Agregar, aumentar, reducir y retirar productos.
- Impedir cantidades superiores al stock.
- Confirmar descuento de stock al guardar.
- Confirmar subtotal, IGV y total.

### Cobranza

- Mostrar pedidos pendientes.
- Impedir un monto menor al total.
- Registrar cada método permitido.
- Calcular vuelto.
- Cambiar el pedido a Pagado.
- Imprimir el ticket.

### Reportes

- Probar hoy, semana, mes y rango personalizado.
- Filtrar por método.
- Buscar por pedido, cliente y documento.
- Comparar los totales con la tabla `pagos`.

## Revisión antes de publicar

```powershell
git status
git diff --cached
```

Comprueba especialmente que no aparezcan archivos privados de `src/app/cfg/`.
