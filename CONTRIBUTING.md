# Contribuir a Botica LyP

1. Crea una rama desde `main`.
2. Mantén la lógica propia dentro de `src/mwap/modules/pharmacy/`.
3. Mantén CSS y JavaScript propios dentro de `src/public_html/res/modules/pharmacy/`.
4. No modifiques los submódulos de Meralda para implementar funciones de la botica.
5. No confirmes credenciales ni datos reales.
6. Valida PHP y JavaScript antes de enviar cambios.

```powershell
Get-ChildItem .\src\mwap\modules\pharmacy -Recurse -Filter *.php |
ForEach-Object { php -l $_.FullName }

Get-ChildItem .\src\public_html\res\modules\pharmacy -Recurse -Filter *.js |
ForEach-Object { node --check $_.FullName }
```
