# 2. Instalación local

## Requisitos

- Apache o Nginx.
- PHP 8.1 o superior con `mysqli`, `mbstring`, `json` y `session`.
- MySQL 8 o MariaDB 10.6 o superior.
- Git para trabajar con los submódulos de Meralda.

## Clonar

Cuando el repositorio ya tenga las rutas registradas como submódulos reales:

```bash
git clone --recurse-submodules URL_DEL_REPOSITORIO botica-lyp
cd botica-lyp
```

En una copia existente:

```bash
git submodule sync --recursive
git submodule update --init --recursive
```

## DocumentRoot

El servidor debe exponer únicamente:

```text
Botica-LyP/src/public_html
```

## Configuración local

Este proyecto no agrega archivos `*.example.php`. Configura directamente los archivos locales:

```text
src/app/cfg/db.php
src/app/cfg/install.php
src/app/cfg/sysmail.php
```

### `db.php`

Debe indicar el host, nombre de base, usuario, contraseña y puerto que realmente funcionan en la computadora:

```php
<?php
$data = array(
    "host" => "127.0.0.1",
    "db"   => "botica",
    "user" => "botica_user",
    "pass" => "CONTRASENA_LOCAL",
    "port" => "3306",
);
?>
```

No publiques el valor real de `pass`.

## Base de datos

Sigue la guía completa:

- [Instalación de la base de datos](10-instalacion-base-de-datos.md)

## VirtualHost de Apache

```apache
<VirtualHost *:80>
    ServerName botica
    DocumentRoot "C:/ruta/Botica-LyP/src/public_html"

    <Directory "C:/ruta/Botica-LyP/src/public_html">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

En el archivo `hosts`:

```text
127.0.0.1 botica
```

Abrir:

```text
http://botica/admin/
```

## Depuración

La configuración actual usa:

```ini
debug_mode = "NO"
debug_restrict_ips = "YES"
```

Activa depuración solo temporalmente y únicamente en local.
