# 2. Instalación local

## Requisitos

- Windows 10 u 11, Linux o macOS.
- Apache o Nginx.
- PHP 8.1 o superior.
- MySQL 8 o MariaDB 10.6 o superior.
- Extensiones PHP `mysqli`, `mbstring`, `json` y `session`.
- Git para descargar los submódulos de Meralda.

## Clonar correctamente

```bash
git clone --recurse-submodules URL_DEL_REPOSITORIO botica-lyp
cd botica-lyp
```

Cuando falta una dependencia:

```bash
git submodule update --init --recursive
```

## DocumentRoot

El servidor web debe apuntar únicamente a:

```text
Botica-LyP/src/public_html
```

Nunca expongas la raíz completa, porque `src/app` y `src/mwap` contienen código del servidor.

## Base de datos

1. Crea una base llamada `botica`.
2. Instala las tablas base de Meralda desde `docs/db/`.
3. Ejecuta `database/pharmacy_schema.sql`.
4. Crea un usuario de MySQL dedicado a esta base.

## Configuración local

Copia los ejemplos:

```powershell
Copy-Item .\src\app\cfg\db.example.php .\src\app\cfg\db.php
Copy-Item .\src\app\cfg\install.example.php .\src\app\cfg\install.php
Copy-Item .\src\app\cfg\sysmail.example.php .\src\app\cfg\sysmail.php
```

Ejemplo de `db.php`:

```php
$data = array(
    "host" => "127.0.0.1",
    "db"   => "botica",
    "user" => "botica_user",
    "pass" => "TU_CONTRASENA_LOCAL",
    "port" => "3306",
);
```

No confirmes este archivo en Git.

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

Archivo `hosts`:

```text
127.0.0.1 botica
```

Abrir:

```text
http://botica/admin/
```

## Modo de depuración

La versión pública usa:

```ini
debug_mode = "NO"
```

Solo durante una prueba local puede cambiarse temporalmente a `YES`, manteniendo `debug_restrict_ips = "YES"`.
