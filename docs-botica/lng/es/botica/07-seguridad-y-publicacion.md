# 7. Seguridad y publicación

## Archivos privados

La versión pública ignora:

```text
src/app/cfg/db.php
src/app/cfg/install.php
src/app/cfg/sysmail.php
```

Solo se incluyen archivos `.example.php`.

## Datos que no deben subir a GitHub

- Contraseñas.
- Tokens o claves privadas.
- Exportaciones con datos reales.
- nombres, DNI, teléfonos o direcciones de clientes.
- archivos de respaldo o logs.

## Configuración de producción

Mantén:

```ini
debug_mode = "NO"
```

El servidor debe exponer únicamente:

```text
src/public_html
```

Usa HTTPS, un usuario de base de datos dedicado y copias de seguridad.

## Permisos de la aplicación

Las pantallas propias requieren `admin`. Esto evita el acceso anónimo, pero una instalación pública también necesita:

- contraseña fuerte para cada usuario,
- actualización periódica del framework,
- limitación de acceso al instalador,
- revisión de permisos del servidor,
- protección y respaldo de la base.

## Túnel de Cloudflare

Un túnel rápido sirve para una demostración temporal, no convierte el equipo local en un hosting permanente. La URL cambia al reiniciar el túnel y el proceso debe permanecer abierto.

Para producción usa un hosting, un dominio y un certificado HTTPS.

## GitHub

La publicación del código no publica automáticamente una página PHP funcional. GitHub Pages solo sirve contenido estático; Botica necesita PHP y MySQL. GitHub se utiliza para alojar el código fuente, mientras que la aplicación debe desplegarse en un servidor compatible.
