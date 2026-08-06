# 6. PHP, HTML, CSS y JavaScript

## Separación usada por Botica y Meralda

```text
man.php           lógica de negocio, SQL y transacciones
uiadmin/*.php     control de pantalla y HTML dinámico
ui.css            diseño
ui.js             comportamiento en el navegador
```

El HTML dinámico está dentro de métodos PHP como `do_exec_page_in()`. No existe un archivo `.html` independiente por cada módulo administrativo.

Ejemplo conceptual:

```php
function do_exec_page_in()
{
    $man = $this->mainap->mainMan->orders;
    $productos = $man->get_productos_disponibles();
?>
    <div class="orders-page">
        <!-- HTML dinámico -->
    </div>
<?php
}
```

La lógica importante no debe quedar en ese HTML; se mantiene en el manager.

## Recursos propios

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

## Registro de recursos

Las interfaces usan los preparadores de Meralda para registrar CSS y JavaScript. La ruta física está dentro de `src/public_html`, mientras que el navegador recibe rutas como:

```text
/res/modules/pharmacy/orders/ui.css
/res/modules/pharmacy/orders/ui.js
```

## Comparación con Demo

El Demo original seguía el mismo patrón de interfaz PHP que genera HTML y recursos públicos separados. La diferencia es funcional: Demo presenta ejemplos del framework; Botica implementa reglas reales de productos, stock, pedidos, pagos y reportes.

`src/mwap/modules/mw/demo` forma parte del núcleo `mw` y no reemplaza ni controla el módulo `pharmacy`.

## Caché

Después de modificar un recurso:

```text
Ctrl + F5
```

Las credenciales nunca deben guardarse en HTML, CSS, JavaScript o `localStorage`.
