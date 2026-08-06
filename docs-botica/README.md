# Documentación de Botica LyP

Esta carpeta contiene la documentación específica de **Botica LyP**. No es un documento Word: son archivos Markdown que forman parte del proyecto y pueden leerse directamente en GitHub.

## Guía principal

- [`lng/es/botica/README.md`](lng/es/botica/README.md)

La guía describe el estado actual del módulo técnico `pharmacy`, su arquitectura, arranque, managers, interfaces, recursos, base de datos, seguridad, mantenimiento y relación con Meralda.

## Separación de documentación

```text
docs/          Documentación oficial de Meralda, declarada como submódulo.
docs-botica/   Documentación propia de Botica LyP.
```

No se debe modificar `docs/` para explicar funciones exclusivas de Botica. Las actualizaciones del proyecto se documentan aquí.

## Configuración local

La documentación ya no supone la existencia de archivos `*.example.php`. La instalación utiliza los archivos reales de configuración local:

```text
src/app/cfg/db.php
src/app/cfg/install.php
src/app/cfg/sysmail.php
```

Antes de publicar, deben permanecer fuera del seguimiento de Git o contener únicamente valores seguros.
