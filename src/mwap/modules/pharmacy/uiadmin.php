<?php

class mwap_pharmacy_uiadmin
    extends mwmod_mw_ui_base_basesubuia
{
    function __construct($cod, $parent)
    {
        $this->init_as_main_or_sub($cod, $parent);
        $this->set_lngmsgsmancod("pharmacy");
        $this->set_def_title("Botica LyP");

        $this->sucods =
            "orders,payments,inventory,addproduct,reports";

        $this->subinterface_def_code = "orders";
    }

    function allowcreatesubinterfacechildbycode()
    {
        return true;
    }

    function _do_create_subinterface_child_orders($cod)
    {
        return new mwap_pharmacy_orders_uiadmin_orders(
            $cod,
            $this
        );
    }

    function _do_create_subinterface_child_payments($cod)
    {
        return new mwap_pharmacy_payments_uiadmin_home(
            $cod,
            $this
        );
    }

    function _do_create_subinterface_child_inventory($cod)
    {
        return new mwap_pharmacy_inventory_uiadmin_home(
            $cod,
            $this
        );
    }

    function _do_create_subinterface_child_addproduct($cod)
    {
        return new mwap_pharmacy_products_uiadmin_home(
            $cod,
            $this
        );
    }

    function _do_create_subinterface_child_reports($cod)
    {
        return new mwap_pharmacy_reports_uiadmin_home(
            $cod,
            $this
        );
    }

    function create_sub_interface_mnu_for_sub_interface(
        $su = false
    ) {
        return false;
    }

    function is_responsable_for_sub_interface_mnu()
    {
        return false;
    }

    function add_2_side_mnu($mnu, $checkallowed = true)
    {
        if (!$mnu) {
            return false;
        }

        if (
            $checkallowed &&
            !$this->is_allowed_on_mnu()
        ) {
            return false;
        }

        $icons = [
            "orders" =>
                "meralda-icon-color meralda-icon-pedidos",

            "payments" =>
                "meralda-icon-color meralda-icon-cobranza",

            "inventory" =>
                "meralda-icon-color meralda-icon-inventario",

            "addproduct" =>
                "meralda-icon-color meralda-icon-agregarproducto",

            "reports" =>
                "fas fa-chart-line mnuicon"
        ];

        $subinterfaces =
            $this->get_subinterfaces_by_code(
                $this->sucods,
                $checkallowed
            );

        if (!$subinterfaces) {
            return false;
        }

        foreach (
            $subinterfaces as
            $code => $subinterface
        ) {
            if (isset($icons[$code])) {
                $subinterface->mnuIconClass =
                    $icons[$code];
            }

            $subinterface->add_2_side_mnu(
                $mnu,
                $checkallowed
            );
        }

        return true;
    }

    function do_exec_no_sub_interface()
    {
    }

    function is_allowed()
    {
        return $this->allow("admin");
    }
}