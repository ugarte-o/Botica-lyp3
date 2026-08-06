# Cambios aplicados a la documentación y estructura pública

Fecha de actualización: 5 de agosto de 2026.

## Documentación actualizada

- Se documentó la aplicación activa `mwap_pharmacy_ap`.
- Se actualizaron las rutas `orders`, `payments`, `inventory`, `addproduct` y `reports`.
- Se documentó la centralización de managers mediante `mainMan`.
- Se explicó la separación entre managers, interfaces PHP/HTML, CSS y JavaScript.
- Se confirmó que Mercado Pago no forma parte de la versión actual.
- Se documentó que el antiguo Demo de aplicación fue retirado y que `src/mwap/modules/mw/demo` pertenece al núcleo de Meralda.
- Se eliminó de la documentación la dependencia de archivos `*.example.php` que no existen en este proyecto.
- Se añadió una guía completa para instalar la base de datos y crear el primer administrador.

## Submódulos de Meralda

Se añadió `.gitmodules` con las URL originales de:

- núcleo `mw`;
- JavaScript y CSS generales;
- recursos públicos de Meralda;
- dependencias de terceros;
- módulos externos;
- tema predeterminado PHP y público;
- documentación oficial de Meralda.

El archivo `.gitmodules` declara las rutas y los repositorios. Para que GitHub los muestre como submódulos reales, cada ruta debe quedar registrada como gitlink en el repositorio local y después confirmarse mediante un commit.

## Base de datos

Se conserva:

```text
database/pharmacy_schema.sql
```

Este archivo crea las tablas propias:

```text
productos
pedidos
detalle_pedido
pagos
```

La instalación completa se documenta en:

```text
docs-botica/lng/es/botica/10-instalacion-base-de-datos.md
```

## Cambios que no se realizaron

- No se modificó la lógica PHP de Botica.
- No se modificaron los archivos CSS o JavaScript.
- No se agregaron archivos de configuración `*.example.php`.
- No se ejecutó una instalación limpia; esa prueba queda a cargo del propietario del proyecto.
- No se eliminaron los componentes internos de Meralda.
