# 9. Git, submódulos y eliminación de Demo

## Repositorio público limpio

La versión preparada para GitHub usa un historial Git nuevo. No conserva el remoto ni el historial del ZIP original, evitando publicar configuraciones antiguas que pudieron existir en commits anteriores.

## Submódulos de Meralda

`.gitmodules` conserva las dependencias oficiales:

- `docs`
- `src/mwap/modules/mw`
- `src/mwap/modules/themes/default`
- `src/mwap/modulesext`
- `src/public_html/res/css`
- `src/public_html/res/js`
- `src/public_html/res/meralda`
- `src/public_html/res/themes/default`
- `src/public_html/res/thirdparty`

Al clonar:

```bash
git clone --recurse-submodules URL_DEL_REPOSITORIO
```

O después:

```bash
git submodule update --init --recursive
```

## Independencia de Demo

La aplicación activa declara:

```php
class mw_app extends mwap_pharmacy_ap
```

El autoloader registra `pharmacy`, la interfaz principal crea `pharmacy` y las rutas públicas usan `/res/modules/pharmacy/`. Por ello, Botica no depende del antiguo módulo de aplicación Demo.

La plantilla `example/demo` fue retirada de la versión pública para evitar confusión. El directorio `src/mwap/modules/mw/demo`, cuando aparece dentro del submódulo del núcleo, pertenece a Meralda y no representa una dependencia de la aplicación Botica.

## Publicar

Crea un repositorio vacío en GitHub y ejecuta:

```powershell
git remote add origin https://github.com/TU_USUARIO/botica-lyp.git
git branch -M main
git push -u origin main
```

No uses el remoto de Meralda como destino de tus cambios. Meralda permanece únicamente como framework y como origen de los submódulos oficiales.
