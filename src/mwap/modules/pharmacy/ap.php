<?php

class mwap_pharmacy_ap
    extends mwmod_mw_ap_def2
{
    private $mainMan;

    function __construct()
    {
    }

    function create_submanager_uiadmin()
    {
        return new mwap_pharmacy_uiadmin_main(
            $this
        );
    }

    function create_submanager_pharmacy()
    {
        return new mwap_pharmacy_mainman(
            "pharmacy",
            $this
        );
    }

    final function __get_priv_mainMan()
    {
        if (!isset($this->mainMan)) {
            $this->mainMan =
                $this->get_submanager(
                    "pharmacy"
                );
        }

        return $this->mainMan;
    }
}