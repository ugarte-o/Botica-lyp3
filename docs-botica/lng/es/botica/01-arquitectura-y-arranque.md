# 1. Arquitectura y arranque

## Cadena de inicio

El navegador entra por:

```text
src/public_html/admin/index.php
```

Ese archivo carga el arranque público y finalmente `src/app/init.php`. La aplicación registra el prefijo propio:

```php
$GLOBALS["__mw_autoload_manager"]
    ->create_and_add_sub_pref_man(
        "pharmacy",
        dirname(dirname(__FILE__)) . "/mwap/modules/pharmacy",
        "mwap"
    );
```

Después declara:

```php
class mw_app extends mwap_pharmacy_ap
{
}
```

Esto demuestra que la aplicación principal es Botica y no Demo.

## Clase de aplicación

`src/mwap/modules/pharmacy/ap.php` crea dos piezas:

- `mwap_pharmacy_uiadmin_main`: interfaz administrativa principal.
- `mwap_pharmacy_mainman`: punto central de managers de negocio.

La propiedad virtual `mainMan` obtiene el manager principal mediante el sistema de submanagers de Meralda.

## mainMan

`base/mainmanabs.php` expone de manera diferida:

```text
mainMan->orders
mainMan->payments
mainMan->inventory
mainMan->products
mainMan->reports
```

Cada manager se crea solo cuando una pantalla lo solicita. Las interfaces no deben ejecutar `new mwap_pharmacy_*_man()` directamente.

## Interfaz principal

`uiadmin/main.php` define:

```text
url_base_path = /admin/
subinterface_def_code = welcome
su_cods_for_side = pharmacy,mwx,users,cfg
```

- `welcome` es el inicio.
- `pharmacy` contiene las cinco áreas de Botica.
- `users` y `cfg` son funciones administrativas del framework.
- `mwx` solo se crea cuando su clase opcional está disponible.

## Menú de Botica

`uiadmin.php` registra:

```text
orders,payments,inventory,addproduct,reports
```

Cada código crea su subinterfaz correspondiente y se agrega al menú lateral con su icono.

## Permisos

La interfaz principal de Botica y cada pantalla propia implementan:

```php
function is_allowed()
{
    return $this->allow("admin");
}
```

Por ello, el usuario debe iniciar sesión y poseer el permiso `admin`.
