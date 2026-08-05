# Botica LyP

Sistema web administrativo para una botica, desarrollado en PHP sobre el framework **Meralda**. La aplicación organiza el registro de productos, el inventario, los pedidos, la cobranza y los reportes de ventas desde un panel protegido por usuarios y permisos.

> Esta versión pública usa el módulo propio `pharmacy`. No depende del antiguo módulo de aplicación `demo`.

## Funciones principales

- Registro de productos con código, categoría, precio, stock y fecha de vencimiento.
- Aumento de stock y eliminación lógica de productos.
- Inventario con alertas de stock bajo y productos próximos a vencer.
- Creación de pedidos con datos del cliente y carrito de productos.
- Validación de stock dentro de una transacción de base de datos.
- Cálculo de subtotal, IGV del 18 % y total.
- Cobranza con Efectivo, Yape, Plin, Tarjeta o Transferencia.
- Generación de ticket e impresión desde el navegador.
- Reportes por fecha, cliente, pedido y método de pago.
- Inicio de sesión y permiso administrativo mediante Meralda.

## Arquitectura

```text
Botica-LyP/
├── docs/                              Documentación oficial de Meralda (submódulo)
├── docs-botica/                       Documentación propia de Botica LyP
├── database/pharmacy_schema.sql       Tablas propias de la botica
├── scripts/                           Ayudas para instalación y publicación
├── src/
│   ├── app/                           Configuración y arranque de la aplicación
│   ├── mwap/modules/pharmacy/         PHP y lógica de negocio propia
│   └── public_html/
│       ├── admin/                     Entrada del panel administrativo
│       └── res/modules/pharmacy/      CSS y JavaScript propios
├── .gitmodules                        Dependencias oficiales de Meralda
└── LICENSE                            Licencia MIT del framework base
```

### Separación de responsabilidades

- `src/app/init.php`: registra el prefijo `pharmacy` y crea la aplicación principal.
- `src/mwap/modules/pharmacy/ap.php`: conecta la aplicación con el administrador principal.
- `src/mwap/modules/pharmacy/base/mainmanabs.php`: carga de forma diferida los managers de negocio.
- `*/man.php`: validación, consultas SQL y transacciones.
- `*/uiadmin/*.php`: construcción de las pantallas administrativas.
- `src/public_html/res/modules/pharmacy/`: comportamiento JavaScript y estilos CSS.

## Módulos y rutas

| Módulo | Ruta |
|---|---|
| Inicio | `/admin/` |
| Pedidos | `/admin/?ui=pharmacy&sui=orders` |
| Cobranza | `/admin/?ui=pharmacy&sui=payments` |
| Inventario | `/admin/?ui=pharmacy&sui=inventory` |
| Agregar producto | `/admin/?ui=pharmacy&sui=addproduct` |
| Reportes | `/admin/?ui=pharmacy&sui=reports` |

## Requisitos

- PHP 8.1 o superior.
- Apache o Nginx.
- MySQL 8 o MariaDB 10.6 o superior.
- Extensiones PHP `mysqli`, `mbstring`, `json` y `session`.
- Git, porque Meralda se distribuye mediante submódulos.

En Windows se puede utilizar WampServer. El servidor web debe exponer únicamente `src/public_html`, nunca la raíz completa del repositorio.

## Instalación

### 1. Clonar con submódulos

```bash
git clone --recurse-submodules URL_DE_TU_REPOSITORIO botica-lyp
cd botica-lyp
```

Cuando el repositorio ya fue clonado sin submódulos:

```bash
git submodule update --init --recursive
```

### 2. Crear la base de datos

Instala primero las tablas base de Meralda y después las tablas de la botica:

```text
docs/db/meralda_base_tables.sql
docs/db/migrations/*.sql
database/pharmacy_schema.sql
```

Para crear el primer usuario administrador, revisa `docs/db/initial_user.sql` y genera la contraseña con `password_hash()` de PHP.

### 3. Crear la configuración local

Copia los ejemplos sin publicarlos con tus datos reales:

```powershell
Copy-Item .\src\app\cfg\db.example.php .\src\app\cfg\db.php
Copy-Item .\src\app\cfg\install.example.php .\src\app\cfg\install.php
Copy-Item .\src\app\cfg\sysmail.example.php .\src\app\cfg\sysmail.php
```

Edita `src/app/cfg/db.php` con la base, usuario, contraseña y puerto de tu servidor.

### 4. Configurar Apache

Ejemplo de VirtualHost:

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

Agrega en el archivo `hosts` de Windows:

```text
127.0.0.1 botica
```

Después abre:

```text
http://botica/admin/
```

## Seguridad de la versión pública

El repositorio no incluye:

- Contraseñas reales de MySQL.
- Claves de instalación.
- Credenciales SMTP.
- Datos de clientes o exportaciones de la base real.
- Historial Git heredado del repositorio original.

Los archivos locales `db.php`, `install.php` y `sysmail.php` están ignorados por Git. Solo se publican archivos `.example.php`.

## Publicar en GitHub

Esta carpeta se entrega como un repositorio Git nuevo, con un único historial limpio y los submódulos de Meralda conservados. Crea un repositorio vacío en GitHub y ejecuta:

```powershell
Set-Location "C:\ruta\Botica-LyP-GitHub-Publico"
git remote add origin https://github.com/TU_USUARIO/botica-lyp.git
git branch -M main
git push -u origin main
```

También puedes ejecutar:

```powershell
.\scripts\publicar-github.ps1 -RepositoryUrl "https://github.com/TU_USUARIO/botica-lyp.git"
```

## Documentación

La guía detallada está en [`docs-botica/lng/es/botica/README.md`](docs-botica/lng/es/botica/README.md).

## Framework y licencia

Botica LyP está construida sobre [Meralda](https://github.com/rodrigovecco/meralda), creado por Rodrigo Vecco Haddad. El framework base se distribuye bajo licencia MIT. Revisa `LICENSE` y las licencias incluidas en cada submódulo antes de redistribuir el proyecto.
