# Guía técnica de Botica LyP

> Estado documentado: módulo `pharmacy` sobre Meralda, actualizado el 5 de agosto de 2026.

## Objetivo

Botica LyP administra productos, inventario, pedidos, cobranza y reportes. Meralda proporciona el framework; la lógica propia se encuentra en `src/mwap/modules/pharmacy`.

## Estado actual

- `src/app/init.php` registra el prefijo `pharmacy`.
- `mw_app` extiende `mwap_pharmacy_ap`.
- `mainMan` centraliza Orders, Payments, Inventory, Products y Reports.
- Las interfaces propias exigen permiso `admin`.
- Los recursos propios se cargan desde `/res/modules/pharmacy/`.
- El antiguo Demo de aplicación no es una dependencia activa.
- Mercado Pago no está incluido.
- La base de datos propia se define en `database/pharmacy_schema.sql`.
- `.gitmodules` contiene los enlaces originales de los componentes de Meralda.
- No se agregaron archivos de configuración `*.example.php`.

## Orden de lectura

1. [Arquitectura y arranque](01-arquitectura-y-arranque.md)
2. [Instalación local](02-instalacion-local.md)
3. [Módulos de Botica](03-modulos-de-botica.md)
4. [Base de datos y flujo](04-base-de-datos.md)
5. [Crear un módulo](05-crear-un-modulo.md)
6. [CSS, JavaScript y HTML dinámico](06-recursos-frontend.md)
7. [Seguridad y publicación](07-seguridad-y-publicacion.md)
8. [Mantenimiento y pruebas](08-mantenimiento.md)
9. [Git, submódulos y Demo](09-git-y-limpieza-demo.md)
10. [Instalación completa de la base de datos](10-instalacion-base-de-datos.md)

## Mapa del proyecto

```text
Botica-LyP/
├── .gitmodules                         URL oficiales de Meralda
├── database/
│   └── pharmacy_schema.sql             Tablas propias de Botica
├── docs-botica/                        Documentación propia
├── src/
│   ├── app/
│   │   ├── init.php                    Registra pharmacy y crea mw_app
│   │   ├── cfg.ini                     Nombre, moneda y debug
│   │   └── cfg/                        Configuración local privada
│   ├── mwap/modules/pharmacy/
│   │   ├── ap.php                      Clase principal
│   │   ├── mainman.php                 Manager principal
│   │   ├── base/mainmanabs.php         Managers diferidos
│   │   ├── uiadmin.php                 Menú de Botica
│   │   ├── uiadmin/                    Inicio y panel
│   │   ├── orders/                     Pedidos
│   │   ├── payments/                   Cobranza
│   │   ├── inventory/                  Inventario
│   │   ├── products/                   Productos
│   │   └── reports/                    Reportes
│   └── public_html/res/modules/pharmacy/
│       ├── uiadmin/
│       ├── orders/
│       ├── payments/
│       ├── inventory/
│       ├── products/
│       └── reports/
└── README.md
```

## Regla de separación

```text
man.php           lógica, validaciones, SQL y transacciones
uiadmin/*.php     preparación de datos y HTML dinámico
ui.css            presentación visual
ui.js             comportamiento del navegador
```

El HTML no se guarda como una página `.html` independiente por cada módulo. Se genera desde las clases PHP de interfaz, igual que en el patrón administrativo de Meralda.
