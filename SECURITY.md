# Seguridad

## Información que no debe publicarse

No agregues al repositorio:

- `src/app/cfg/db.php`
- `src/app/cfg/install.php`
- `src/app/cfg/sysmail.php`
- exportaciones de la base de datos real
- datos personales de clientes
- tokens, claves privadas o contraseñas

Los archivos `.example.php` contienen únicamente valores de muestra.

## Producción

- Mantén `debug_mode = "NO"`.
- Expón únicamente `src/public_html` como raíz del servidor.
- Usa HTTPS.
- Crea un usuario de base de datos con permisos limitados a la base de Botica.
- Genera claves diferentes para instalación, base de datos y correo.
- Realiza copias de seguridad antes de actualizar el framework o los submódulos.

## Reporte responsable

No publiques credenciales ni datos personales dentro de un Issue. Describe el problema sin incluir información sensible y cambia inmediatamente cualquier clave expuesta.
