<?php

class mwap_pharmacy_uiadmin_main
    extends mwmod_mw_ui2_def_main_admin
{
    function __construct($ap)
    {
        $this->set_mainap($ap);

        
        $this->subinterface_def_code =
            "welcome";

        
        $this->url_base_path =
            "/admin/";

        $this->enable_session_check();

        $this->logout_script_file =
            "logout.php";

     
        $this->su_cods_for_side =
            "pharmacy,mwx,users,cfg";
    }

    function create_template()
    {
        return new mwtheme_default_mainuitemplate(
            $this
        );
    }

    function createUISessionDataMan()
    {
        return new mwmod_mw_data_session_man(
            "pharmacymainui"
        );
    }


    function create_subinterface_pharmacy()
    {
        return new mwap_pharmacy_uiadmin(
            "pharmacy",
            $this
        );
    }

  
    function create_subinterface_welcome()
   {
    return new mwap_pharmacy_uiadmin_welcome(
        "welcome",
        $this
        );
   }

    /*
     * Conserva Meralda X cuando esté disponible.
     */
    function create_subinterface_mwx()
    {
        $autoload =
            mw_get_autoload_manager();

        if (
            $autoload->class_exists(
                "mwmod_mwx_demo_ui"
            )
        ) {
            return new mwmod_mwx_demo_ui(
                "mwx",
                $this
            );
        }

        return false;
    }
}