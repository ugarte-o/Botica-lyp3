# Botica LyP

Sistema web administrativo para la gestión de una botica o farmacia.

El proyecto permite administrar productos, inventario, pedidos, cobranza y reportes desde un panel web. Está desarrollado en PHP sobre el framework Meralda y utiliza MySQL o MariaDB como base de datos.

## Estado del proyecto

El sistema se encuentra funcional y organizado como un proyecto independiente.

Incluye:

- Módulo propio de farmacia.
- Base de datos.
- Recursos personalizados.
- Documentación.
- Componentes oficiales de Meralda configurados como submódulos.
- Archivos privados protegidos mediante `.gitignore`.

## Funciones principales

### Inicio

Panel principal con acceso rápido a los diferentes módulos del sistema.

### Pedidos

Permite:

- Buscar productos.
- Agregar productos al carrito.
- Aumentar o disminuir cantidades.
- Eliminar productos del carrito.
- Registrar datos del cliente.
- Validar DNI y teléfono.
- Comprobar la disponibilidad de stock.
- Registrar observaciones.
- Enviar pedidos al módulo de cobranza.

### Cobranza

Permite:

- Visualizar pedidos pendientes.
- Calcular el importe total.
- Mostrar el IGV.
- Registrar el pago.
- Generar tickets.
- Imprimir comprobantes.
- Eliminar registros cuando sea necesario.

### Inventario

Permite:

- Consultar productos registrados.
- Revisar el stock disponible.
- Detectar productos con stock bajo.
- Revisar fechas de vencimiento.
- Mostrar alertas de productos próximos a vencer.

### Productos

Permite registrar productos con información como:

- Código.
- Nombre.
- Categoría.
- Precio.
- Stock inicial.
- Fecha de vencimiento.

### Reportes

Permite consultar información relacionada con:

- Productos.
- Inventario.
- Pedidos.
- Cobranza.
- Movimientos registrados.
- Exportación de información.

## Tecnologías utilizadas

- PHP
- MySQL o MariaDB
- HTML5
- CSS3
- JavaScript
- jQuery
- Apache
- WampServer
- Git
- GitHub
- Framework Meralda

## Estructura principal

```text
Botica-lyp3/
│
├── database/
│   └── pharmacy_schema.sql
│
├── docs/
│
├── docs-botica/
│
├── src/
│   ├── app/
│   │   ├── cfg/
│   │   └── init.php
│   │
│   ├── mwap/
│   │   ├── modules/
│   │   │   ├── mw/
│   │   │   ├── pharmacy/
│   │   │   └── themes/
│   │   │
│   │   └── modulesext/
│   │
│   └── public_html/
│       ├── admin/
│       └── res/
│           ├── css/
│           ├── js/
│           ├── meralda/
│           ├── modules/
│           │   └── pharmacy/
│           ├── themes/
│           └── thirdparty/
│
├── .gitignore
├── .gitmodules
└── README.md
```

## Módulo propio de Botica

La lógica principal del sistema se encuentra en:

```text
src/mwap/modules/pharmacy
```

Los recursos visuales y archivos JavaScript propios se encuentran en:

```text
src/public_html/res/modules/pharmacy
```

La aplicación principal se inicializa desde:

```text
src/app/init.php
```

## Submódulos de Meralda

El proyecto utiliza nueve submódulos oficiales de Meralda.

### Módulos principales

```text
src/mwap/modules/mw
```

Repositorio:

```text
https://github.com/rodrigovecco/meralda_mw_modules.git
```

### JavaScript

```text
src/public_html/res/js
```

Repositorio:

```text
https://github.com/rodrigovecco/meralda_js_submodule.git
```

### CSS

```text
src/public_html/res/css
```

Repositorio:

```text
https://github.com/rodrigovecco/meralda_css.git
```

### Recursos de terceros

```text
src/public_html/res/thirdparty
```

Repositorio:

```text
https://github.com/rodrigovecco/meralda-thirdparty-public.git
```

### Documentación de Meralda

```text
docs
```

Repositorio:

```text
https://github.com/rodrigovecco/meralda-docs.git
```

### Módulos externos

```text
src/mwap/modulesext
```

Repositorio:

```text
https://github.com/rodrigovecco/meralda-thirdparty-modules.git
```

### Recursos públicos de Meralda

```text
src/public_html/res/meralda
```

Repositorio:

```text
https://github.com/rodrigovecco/meralda_public_res.git
```

### Tema principal

```text
src/mwap/modules/themes/default
```

Repositorio:

```text
https://github.com/rodrigovecco/meralda-theme-default.git
```

### Recursos públicos del tema

```text
src/public_html/res/themes/default
```

Repositorio:

```text
https://github.com/rodrigovecco/meralda-theme-default-public.git
```

## Requisitos

Para ejecutar el proyecto se necesita:

- Windows, Linux o macOS.
- Apache.
- PHP 8 o superior.
- MySQL o MariaDB.
- Git.
- Navegador web moderno.
- WampServer, XAMPP o un entorno equivalente.

## Clonar el proyecto

Debido a que el proyecto utiliza submódulos, debe clonarse con el parámetro `--recurse-submodules`.

```bash
git clone --recurse-submodules https://github.com/ugarte-o/Botica-lyp3.git
```

Después se debe ingresar a la carpeta:

```bash
cd Botica-lyp3
```

Si el proyecto fue clonado sin los submódulos, se pueden descargar con:

```bash
git submodule update --init --recursive
```

## Configuración de la base de datos

El archivo con la estructura de la base de datos se encuentra en:

```text
database/pharmacy_schema.sql
```

Pasos generales:

1. Abrir phpMyAdmin, MySQL Workbench o una herramienta equivalente.
2. Crear una base de datos para el sistema.
3. Importar el archivo:

```text
database/pharmacy_schema.sql
```

4. Configurar los datos de conexión locales.

## Configuración privada

Los datos de conexión deben colocarse en:

```text
src/app/cfg/db.php
```

Este archivo puede contener:

- Servidor de base de datos.
- Puerto.
- Nombre de la base de datos.
- Usuario.
- Contraseña.

Los archivos privados no deben subirse a GitHub.

Entre los archivos protegidos se encuentran:

```text
src/app/cfg/db.php
src/app/cfg/install.php
src/app/cfg/sysmail.php
```

Estos archivos están excluidos mediante `.gitignore`.

Nunca se deben publicar contraseñas reales dentro del repositorio.

## Configuración en WampServer

La carpeta pública que debe configurarse como `DocumentRoot` es:

```text
src/public_html
```

Ejemplo de VirtualHost:

```apache
<VirtualHost *:80>
    ServerName botica
    DocumentRoot "C:/ruta-del-proyecto/Botica-lyp3/src/public_html"

    <Directory "C:/ruta-del-proyecto/Botica-lyp3/src/public_html">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

También se debe agregar el nombre local al archivo `hosts`.

Ejemplo:

```text
127.0.0.1 botica
```

Después se deben reiniciar los servicios de Apache o WampServer.

## Abrir el sistema

Con el VirtualHost configurado, el panel administrativo se abre desde:

```text
http://botica/admin/
```

## Actualizar el proyecto

Para descargar cambios del repositorio principal:

```bash
git pull origin main
```

Después se deben sincronizar los submódulos:

```bash
git submodule update --init --recursive
```

## Revisar el estado de los submódulos

```bash
git submodule status
```

Los submódulos correctamente inicializados aparecen sin un signo `-` al inicio.

## Validar archivos PHP

Para validar el archivo principal:

```powershell
php -l ".\src\app\init.php"
```

Para validar todos los archivos PHP del módulo de farmacia:

```powershell
Get-ChildItem `
".\src\mwap\modules\pharmacy" `
-Recurse -File -Filter "*.php" |
ForEach-Object {
    php -l $_.FullName
}
```

El resultado correcto debe indicar:

```text
No syntax errors detected
```

## Seguridad

El proyecto aplica las siguientes medidas básicas:

- Las credenciales de base de datos no se almacenan en GitHub.
- Los archivos privados están protegidos por `.gitignore`.
- La base de datos debe utilizar un usuario propio.
- No se recomienda utilizar el usuario `root` en producción.
- Las contraseñas deben ser seguras.
- En producción se debe utilizar HTTPS.
- Se deben realizar copias de seguridad de la base de datos.
- El acceso administrativo debe estar protegido.

## Recomendaciones para producción

Antes de utilizar el sistema en un servidor público se recomienda:

- Configurar un dominio.
- Activar un certificado SSL.
- Usar HTTPS.
- Crear una base de datos de producción.
- Crear un usuario MySQL con permisos limitados.
- Desactivar la visualización pública de errores PHP.
- Configurar copias de seguridad.
- Revisar los permisos de archivos y carpetas.
- Proteger el panel administrativo.
- Verificar el funcionamiento en computadora y celular.

## Repositorio

Repositorio principal:

```text
https://github.com/ugarte-o/Botica-lyp3
```

Rama principal:

```text
main
```

## Autor

Proyecto desarrollado por Obec Ugarte.

## Nota

Este repositorio contiene el código propio de Botica LyP y utiliza componentes externos de Meralda mediante submódulos Git.

Los submódulos permanecen vinculados a sus repositorios originales y el proyecto guarda una referencia específica de cada versión utilizada.
