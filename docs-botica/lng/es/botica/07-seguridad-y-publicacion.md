# 7. Seguridad y publicación

## Configuración privada

Los archivos locales son:

```text
src/app/cfg/db.php
src/app/cfg/install.php
src/app/cfg/sysmail.php
```

El proyecto no agrega copias `*.example.php`. Antes de publicar:

1. Verifica que esos archivos estén ignorados.
2. Comprueba que no estén ya rastreados por Git.
3. No publiques contraseñas, claves de instalación ni credenciales SMTP.

Comprobación:

```powershell
git check-ignore -v .\src\app\cfg\db.php
git ls-files .\src\app\cfg\db.php .\src\app\cfg\install.php .\src\app\cfg\sysmail.php
```

Si `git ls-files` muestra alguno, retirarlo del índice sin borrarlo del disco:

```powershell
git rm --cached .\src\app\cfg\db.php
git rm --cached .\src\app\cfg\install.php
git rm --cached .\src\app\cfg\sysmail.php
```

## Producción

- Mantén `debug_mode = "NO"`.
- Expón únicamente `src/public_html`.
- Usa HTTPS.
- Usa un usuario de base de datos dedicado.
- Deshabilita el instalador después de crear el administrador.
- Realiza copias de seguridad.
- No publiques datos reales de clientes.

## Permisos

Las pantallas propias requieren `admin`. Esto complementa, pero no sustituye, la seguridad del servidor y de la base de datos.

## GitHub

GitHub aloja el código fuente. Botica necesita PHP y MySQL, por lo que no funciona como aplicación completa en GitHub Pages.
