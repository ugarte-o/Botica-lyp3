# 1. Arquitectura y arranque

## Cadena de inicio

```text
Navegador
  ↓
src/public_html/admin/index.php
  ↓
src/public_html/admin/init.php
  ↓
src/public_html/init.php
  ↓
src/app/init.php
  ↓
mwap_pharmacy_ap
  ↓
interfaz administrativa
  ↓
mainMan
  ↓
manager especializado
  ↓
base de datos
```

`src/app/init.php` registra el prefijo `pharmacy`:

```php
$GLOBALS["__mw_autoload_manager"]
    ->create_and_add_sub_pref_man(
        "pharmacy",
        dirname(dirname(__FILE__)) . "/mwap/modules/pharmacy",
        "mwap"
    );
```

Después declara la aplicación activa:

```php
class mw_app extends mwap_pharmacy_ap
{
}
```

Esto confirma que Botica es la aplicación principal.

## Clase de aplicación

`src/mwap/modules/pharmacy/ap.php` proporciona:

- la interfaz administrativa principal;
- el acceso a `mainMan`;
- la integración con las clases base de Meralda.

## mainMan

Los archivos son:

```text
src/mwap/modules/pharmacy/mainman.php
src/mwap/modules/pharmacy/base/mainmanabs.php
```

Exponen de manera diferida:

```text
mainMan->orders
mainMan->payments
mainMan->inventory
mainMan->products
mainMan->reports
```

Las interfaces solicitan el manager correspondiente y no lo crean directamente.

## Interfaz principal

`src/mwap/modules/pharmacy/uiadmin/main.php` configura:

```text
url_base_path = /admin/
subinterface_def_code = welcome
su_cods_for_side = pharmacy,mwx,users,cfg
```

`mwx` solo se crea cuando el módulo opcional está disponible. `users` y `cfg` pertenecen a las funciones administrativas del framework.

## Menú de Botica

`src/mwap/modules/pharmacy/uiadmin.php` registra:

```text
orders,payments,inventory,addproduct,reports
```

## Permisos

Las pantallas propias aplican:

```php
function is_allowed()
{
    return $this->allow("admin");
}
```

La autenticación y el permiso se resuelven mediante Meralda.
