# 9. Git, submódulos y Demo

## `.gitmodules`

El proyecto incluye las URL originales de Meralda para:

```text
src/mwap/modules/mw
src/public_html/res/js
src/public_html/res/css
src/public_html/res/thirdparty
docs
src/mwap/modulesext
src/public_html/res/meralda
src/mwap/modules/themes/default
src/public_html/res/themes/default
```

## Importante: archivo y gitlinks

`.gitmodules` guarda el nombre, la ruta y la URL de cada dependencia. Para que una ruta sea un submódulo real, el repositorio principal también debe registrarla como un gitlink y guardar el commit concreto de la dependencia.

Por eso, después de preparar los submódulos en el repositorio local, se debe confirmar:

```powershell
git submodule status --recursive
git status --short
git add .gitmodules
git add src/mwap/modules/mw
git add src/public_html/res/js
git add src/public_html/res/css
git add src/public_html/res/thirdparty
git add docs
git add src/mwap/modulesext
git add src/public_html/res/meralda
git add src/mwap/modules/themes/default
git add src/public_html/res/themes/default
git commit -m "Configurar submódulos oficiales de Meralda"
```

No ejecutes una conversión destructiva sin una copia de seguridad y un commit previo del módulo `pharmacy`.

## Clonar

```bash
git clone --recurse-submodules URL_DEL_REPOSITORIO
```

O inicializar después:

```bash
git submodule sync --recursive
git submodule update --init --recursive
```

## Demo

La aplicación activa es:

```php
class mw_app extends mwap_pharmacy_ap
```

El módulo propio y sus recursos usan `pharmacy`. El antiguo Demo de aplicación no es necesario.

El directorio:

```text
src/mwap/modules/mw/demo
```

pertenece al submódulo del núcleo Meralda. No debe eliminarse como si fuera el antiguo módulo de aplicación.

## Remotos

El remoto `origin` debe apuntar al repositorio de Botica. Los repositorios de Meralda permanecen en `.gitmodules` como dependencias y no deben reemplazar `origin`.
