<?php
/**
 * Default theme — UI2 template
 *
 * Loads the standard UI2 CSS stack. Color customization should be done
 * by extending this class and overriding add_default_css_sheets().
 *
 * Available color schemes in /res/themes/default/css/:
 * - summer-sunflower.css  — Warm greens & sunflower yellows
 * - dark-ocean.css        — Professional dark theme with deep blues
 * - corporate-blue.css    — Clean corporate navy theme
 *
 * Example usage in a project UI class:
 *
 * ```php
 * class mwap_myproject_uiadmin_main extends mwmod_mw_ui2_def_main_admin {
 *     function create_template() {
 *         return new mwtheme_myproject_template($this);
 *     }
 * }
 * 
 * class mwtheme_myproject_template extends mwtheme_default_mainuitemplate {
 *     function add_default_css_sheets($cssmanager) {
 *         parent::add_default_css_sheets($cssmanager);
 *         
 *         // Apply your chosen color scheme
 *         $cssmanager->add_item_by_item(
 *             new mwmod_mw_html_manager_item_css(
 *                 "theme-colors",
 *                 "/res/themes/default/css/summer-sunflower.css"
 *             )
 *         );
 *     }
 * }
 * ```
 *
 * Registration in src/app/init.php (if not already done):
 *
 * ```php
 * $GLOBALS["__mw_autoload_manager"]->create_and_add_sub_pref_man(
 *     "default",
 *     dirname(dirname(__FILE__))."/mwap/modules/themes/default",
 *     "mwtheme"
 * );
 * ```
 *
 * @see mwmod_mw_ui2_template_main  Parent with the standard UI2 CSS stack.
 */
class mwtheme_default_mainuitemplate extends mwmod_mw_ui2_template_main {

    /**
     * Path (relative to public_html) of the theme JS enhancement file.
     *
     * @var string
     */
    protected string $custom_js_path = "/res/themes/default/js/theme.js";

    /**
     * Load standard UI2 CSS stack.
     * 
     * NOTE: This base template does NOT load any color scheme by default.
     * To apply colors, extend this class and override this method to load
     * one of the available schemes:
     * 
     * Available color schemes:
     * - /res/themes/default/css/summer-sunflower.css
     * - /res/themes/default/css/dark-ocean.css
     * - /res/themes/default/css/corporate-blue.css
     * 
     * Or create your own by overriding CSS variables.
     *
     * @param mwmod_mw_html_manager_css $cssmanager
     */
    function add_default_css_sheets($cssmanager) {
        parent::add_default_css_sheets($cssmanager);
        
        // No color scheme loaded by default.
        // Projects should extend this class and load their preferred theme here.
    }

    /**
     * Load standard UI2 JS and then append the theme enhancement script.
     * The script is injected at the bottom of <body> so it runs after the
     * framework markup is fully parsed.
     *
     * @param object $mainUI
     * @param mwmod_mw_html_manager_js $jsmanager
     */
    function add_default_js_scripts_for_main($mainUI, $jsmanager) {
        parent::add_default_js_scripts_for_main($mainUI, $jsmanager);
        /*

        $item = new mwmod_mw_html_manager_item_jsexternal("theme-custom-js", $this->custom_js_path);
        $item->bottom = true;
        $jsmanager->add_item_by_item($item);
        */
    }
}
?>
