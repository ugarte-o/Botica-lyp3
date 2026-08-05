# 5. Crear un módulo nuevo

Ejemplo: agregar `suppliers` para proveedores.

## 1. Manager

```text
src/mwap/modules/pharmacy/suppliers/man.php
```

```php
<?php

class mwap_pharmacy_suppliers_man
    extends mwmod_mw_manager_baseman
{
    function __construct($mainAP)
    {
        $this->init("suppliers", $mainAP);
    }

    function listar()
    {
        $dbManager = $this->mainap->getDBManager();
        $db = $dbManager->get_link();
        // Ejecutar una consulta preparada.
        return array();
    }
}
```

## 2. Registrar en mainMan

En `base/mainmanabs.php` agrega la propiedad y su cargador diferido:

```php
protected $suppliers = null;

final function __get_priv_suppliers()
{
    if (!isset($this->suppliers)) {
        $this->suppliers =
            new mwap_pharmacy_suppliers_man(
                $this->mainap
            );
    }

    return $this->suppliers;
}
```

## 3. Interfaz

```text
src/mwap/modules/pharmacy/suppliers/uiadmin/home.php
```

La interfaz debe obtener el manager así:

```php
$man = $this->mainap->mainMan->suppliers;
```

No debe crear el manager directamente.

## 4. Registrar la ruta

En `src/mwap/modules/pharmacy/uiadmin.php`:

1. Agrega `suppliers` a `$this->sucods`.
2. Crea `_do_create_subinterface_child_suppliers($cod)`.
3. Agrega un icono en `$icons`.

La ruta será:

```text
/admin/?ui=pharmacy&sui=suppliers
```

## 5. Recursos

```text
src/public_html/res/modules/pharmacy/suppliers/ui.css
src/public_html/res/modules/pharmacy/suppliers/ui.js
```

## 6. Permiso

```php
function is_allowed()
{
    return $this->allow("admin");
}
```
