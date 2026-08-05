# Default Theme — PHP Module

Contains the PHP module for the `default` theme of the Meralda framework.

## Files

- **`mainuitemplate.php`** — `mwtheme_default_mainuitemplate` class. Extends `mwmod_mw_ui2_template_main` and injects the CSS override from `/res/themes/default/css/custom.css`.

## Registration

In `src/app/init.php`:

```php
$GLOBALS["__mw_autoload_manager"]->create_and_add_sub_pref_man(
    "default",
    dirname(dirname(__FILE__))."/mwap/modules/themes/default",
    "mwtheme"
);
```

## Usage

```php
class mwap_myproject_uiadmin_main extends mwmod_mw_ui2_def_main_admin {
    function create_template() {
        return new mwtheme_default_mainuitemplate($this);
    }
}
```

## Assets (CSS)

Static theme files (CSS) live in a separate directory:  
[`src/public_html/res/themes/default/`](../../../../public_html/res/themes/default/README.md)
