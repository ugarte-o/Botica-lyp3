# Guía técnica de Botica LyP

> Estado documentado: **Botica(33).zip**, revisado y preparado para publicación el **5 de agosto de 2026**.

## Objetivo

Botica LyP es una aplicación administrativa para registrar productos, controlar inventario, crear pedidos, cobrar ventas y consultar reportes. Está desarrollada sobre Meralda, pero su lógica de negocio se encuentra en un módulo propio e independiente llamado `pharmacy`.

## Estado actual

- La aplicación principal extiende `mwap_pharmacy_ap`.
- El prefijo `pharmacy` se registra en `src/app/init.php`.
- La interfaz principal es `mwap_pharmacy_uiadmin_main`.
- Los managers se obtienen desde `mainMan` y se crean de forma diferida.
- Pedidos, Cobranza, Inventario, Productos y Reportes tienen PHP, CSS y JavaScript propios.
- Todas las pantallas propias exigen permiso `admin`.
- El módulo activo no depende del antiguo módulo de aplicación `demo`.
- Mercado Pago no forma parte de la versión actual.
- La versión pública no contiene credenciales ni historial Git heredado.

## Orden de lectura

1. [Arquitectura y arranque](01-arquitectura-y-arranque.md)
2. [Instalación local](02-instalacion-local.md)
3. [Módulos de Botica](03-modulos-de-botica.md)
4. [Base de datos y flujo](04-base-de-datos.md)
5. [Crear un módulo nuevo](05-crear-un-modulo.md)
6. [CSS, JavaScript y recursos](06-recursos-frontend.md)
7. [Seguridad y publicación](07-seguridad-y-publicacion.md)
8. [Mantenimiento y pruebas](08-mantenimiento.md)
9. [Git, submódulos y eliminación de Demo](09-git-y-limpieza-demo.md)

## Mapa del proyecto

```text
Botica-LyP/
├── docs/                              Submódulo oficial meralda-docs
├── docs-botica/                       Documentación propia
├── database/pharmacy_schema.sql       Tablas propias
├── src/
│   ├── app/
│   │   ├── init.php                   Registra pharmacy y crea mw_app
│   │   ├── cfg.ini                    Nombre, moneda y modo debug
│   │   └── cfg/*.example.php          Configuraciones públicas de ejemplo
│   ├── mwap/modules/pharmacy/
│   │   ├── ap.php                     Aplicación base
│   │   ├── mainman.php                Manager principal
│   │   ├── base/mainmanabs.php        Managers diferidos
│   │   ├── uiadmin.php                Menú de módulos
│   │   ├── uiadmin/main.php           Panel principal
│   │   ├── uiadmin/welcome.php        Inicio
│   │   ├── orders/                    Pedidos
│   │   ├── payments/                  Cobranza
│   │   ├── inventory/                 Inventario
│   │   ├── products/                  Productos
│   │   └── reports/                   Reportes
│   └── public_html/res/modules/pharmacy/
│       ├── uiadmin/
│       ├── orders/
│       ├── payments/
│       ├── inventory/
│       ├── products/
│       └── reports/
├── .gitignore
├── .gitmodules
├── README.md
└── SECURITY.md
```

## Rutas administrativas

| Pantalla | Código interno | Ruta |
|---|---|---|
| Inicio | `welcome` | `/admin/` |
| Pedidos | `orders` | `/admin/?ui=pharmacy&sui=orders` |
| Cobranza | `payments` | `/admin/?ui=pharmacy&sui=payments` |
| Inventario | `inventory` | `/admin/?ui=pharmacy&sui=inventory` |
| Agregar producto | `addproduct` | `/admin/?ui=pharmacy&sui=addproduct` |
| Reportes | `reports` | `/admin/?ui=pharmacy&sui=reports` |

## Regla principal de organización

- Arranque y configuración: `src/app/`.
- PHP propio: `src/mwap/modules/pharmacy/`.
- Consultas y reglas de negocio: archivos `man.php`.
- Pantallas: archivos `uiadmin/*.php`.
- CSS y JavaScript: `src/public_html/res/modules/pharmacy/`.
- Núcleo de Meralda: submódulos oficiales; no se modifica para agregar funciones de Botica.
