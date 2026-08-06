# 10. Instalación completa de la base de datos

Esta guía instala primero las tablas del framework y después las tablas propias de Botica.

## 1. Elegir nombre y usuario

Ejemplo recomendado:

```text
Base:    botica
Usuario: botica_user
Puerto:  3306 o el puerto real del servidor
```

No uses estas credenciales como texto fijo en una publicación. Sustitúyelas por valores locales.

## 2. Crear la base y el usuario

Desde MySQL o MariaDB con una cuenta administradora:

```sql
CREATE DATABASE IF NOT EXISTS botica
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'botica_user'@'localhost'
    IDENTIFIED BY 'CONTRASENA_LOCAL_SEGURA';

GRANT ALL PRIVILEGES ON botica.*
    TO 'botica_user'@'localhost';

FLUSH PRIVILEGES;
```

Cuando PHP se conecta a `127.0.0.1`, puede ser necesario crear también el usuario para ese host:

```sql
CREATE USER IF NOT EXISTS 'botica_user'@'127.0.0.1'
    IDENTIFIED BY 'CONTRASENA_LOCAL_SEGURA';

GRANT ALL PRIVILEGES ON botica.*
    TO 'botica_user'@'127.0.0.1';
```

## 3. Importar las tablas base de Meralda

Archivo:

```text
docs-botica/db/meralda_base_tables.sql
```

Con cliente MySQL:

```powershell
mysql -u root -p botica < .\docs-botica\db\meralda_base_tables.sql
```

En phpMyAdmin:

1. Selecciona la base `botica`.
2. Abre **Importar**.
3. Selecciona `docs-botica/db/meralda_base_tables.sql`.
4. Ejecuta la importación.

Tablas base principales:

```text
users
bruteforce_blacklist
bruteforce_ip_activity
bruteforce_whitelist
user_api_tokens
```

## 4. Importar las tablas de Botica

Archivo:

```text
database/pharmacy_schema.sql
```

Con cliente MySQL:

```powershell
mysql -u root -p botica < .\database\pharmacy_schema.sql
```

Este archivo crea:

```text
productos
pedidos
detalle_pedido
pagos
```

## 5. Configurar la conexión

Edita localmente:

```text
src/app/cfg/db.php
```

Ejemplo de estructura:

```php
<?php
$data = array(
    "host" => "127.0.0.1",
    "db"   => "botica",
    "user" => "botica_user",
    "pass" => "CONTRASENA_LOCAL_SEGURA",
    "port" => "3306",
);
?>
```

El puerto debe coincidir con el proceso que realmente escucha en MySQL o MariaDB.

## 6. Crear el primer administrador

### Opción recomendada: instalador local

1. Edita `src/app/cfg/install.php`.
2. Mantén `allowed_ips` limitado a `::1` y `127.0.0.1`.
3. Define una clave temporal fuerte.
4. Activa temporalmente `allowed`.
5. Abre:

```text
http://botica/install/
```

6. Crea el usuario principal.
7. Al terminar, cambia otra vez:

```php
"allowed" => false
```

No dejes el instalador habilitado.

### Opción manual: SQL

Existe:

```text
docs-botica/db/initial_user.sql
```

Antes de importarlo:

- cambia el correo;
- cambia el nombre;
- reemplaza el hash bcrypt;
- revisa los valores de rol y fecha para la versión de MySQL/MariaDB usada.

El hash puede generarse con:

```powershell
pip install bcrypt
python .\docs-botica\db\hash_password.py
```

## 7. Verificar las tablas

```sql
USE botica;
SHOW TABLES;
```

Deben aparecer las tablas base y las cuatro tablas propias.

Comprobaciones útiles:

```sql
SELECT COUNT(*) AS usuarios FROM users;
SELECT COUNT(*) AS productos FROM productos;
SELECT COUNT(*) AS pedidos FROM pedidos;
SELECT COUNT(*) AS pagos FROM pagos;
```

## 8. Probar la aplicación

Abre:

```text
http://botica/admin/
```

Orden de prueba:

1. iniciar sesión;
2. registrar producto;
3. comprobar inventario;
4. crear pedido;
5. confirmar descuento de stock;
6. cobrar pedido;
7. imprimir ticket;
8. revisar reportes.

## 9. Errores frecuentes

### Conexión rechazada

El servicio no está iniciado o el puerto configurado no coincide.

### Access denied

El usuario, contraseña o host autorizado no coinciden.

### Unknown database

La base configurada en `db.php` no existe.

### Table does not exist

Falta importar las tablas base de Meralda o `pharmacy_schema.sql`.

## 10. Prueba limpia

La verificación final consiste en repetir esta instalación en una carpeta y base nuevas. Esa prueba queda pendiente para realizarla manualmente en el entorno local del proyecto.
