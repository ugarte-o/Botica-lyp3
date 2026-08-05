# Meralda Public Resources

Shared frontend resources for Meralda-based projects. This package provides Bootstrap 5 and custom UI components that can be used across multiple Meralda installations.

## Contents

```
meralda/
├── css/
│   ├── bootstrap.min.css    # Bootstrap 5.2.3
│   ├── variables.css        # CSS custom properties / theme variables
│   ├── layout.css           # Base layout utilities
│   ├── components.css       # UI component styles
│   └── theme.css            # Color scheme and theming
└── js/
    ├── bootstrap.bundle.min.js  # Bootstrap 5.2.3 JS + Popper
    └── scripts.js               # Custom UI scripts
```

## Integration

### Adding to a Meralda Project

Navigate to your project's public resources directory and add as a submodule:

```bash
cd src/public_html/res
git submodule add https://github.com/rodrigovecco/meralda_public_res.git meralda
```

Then commit the changes to your main repository:

```bash
cd ../../..  # back to project root
git add .gitmodules src/public_html/res/meralda
git commit -m "Add meralda public resources submodule"
```

### Cloning a Project with This Submodule

When cloning a Meralda project that uses this submodule:

```bash
# Option 1: Clone with submodules
git clone --recurse-submodules <repository-url>

# Option 2: Initialize after cloning
git clone <repository-url>
cd <project>
git submodule update --init --recursive
```

### Updating the Submodule

To pull the latest changes:

```bash
cd src/public_html/res/meralda
git pull origin main
cd ../../../..  # back to project root
git add src/public_html/res/meralda
git commit -m "Update meralda submodule"
```

## Usage in PHP

Reference the resources in your Meralda module using the `/res/meralda/` path:

```php
// CSS
$cssmanager->add_item_by_item(
    new mwmod_mw_html_manager_item_css("meralda-bootstrap", "/res/meralda/css/bootstrap.min.css")
);
$cssmanager->add_item_by_item(
    new mwmod_mw_html_manager_item_css("meralda-variables", "/res/meralda/css/variables.css")
);
$cssmanager->add_item_by_item(
    new mwmod_mw_html_manager_item_css("meralda-theme", "/res/meralda/css/theme.css")
);

// JavaScript
$item = new mwmod_mw_html_manager_item_jsexternal("bootstrap", "/res/meralda/js/bootstrap.bundle.min.js");
$jsmanager->add_item_by_item($item);
```

## Usage in HTML

Direct inclusion in templates:

```html
<!-- CSS -->
<link rel="stylesheet" href="/res/meralda/css/bootstrap.min.css">
<link rel="stylesheet" href="/res/meralda/css/variables.css">
<link rel="stylesheet" href="/res/meralda/css/layout.css">
<link rel="stylesheet" href="/res/meralda/css/components.css">
<link rel="stylesheet" href="/res/meralda/css/theme.css">

<!-- JavaScript (before </body>) -->
<script src="/res/meralda/js/bootstrap.bundle.min.js"></script>
<script src="/res/meralda/js/scripts.js"></script>
```

## CSS Variables

The `variables.css` file defines CSS custom properties for theming. Override them in your project's custom CSS:

```css
:root {
    --mw-primary: #your-color;
    --mw-secondary: #your-color;
    /* ... */
}
```

## License

MIT License - See LICENSE file for details.
