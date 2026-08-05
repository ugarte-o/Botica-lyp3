<?php

abstract class mwap_pharmacy_base_mainmanabs
    extends mwmod_mw_manager_baseman
{
    protected $orders = null;
    protected $payments = null;
    protected $inventory = null;
    protected $products = null;
    protected $reports = null;

    function __construct($code, $ap)
    {
        $this->init($code, $ap);
    }

    final function __get_priv_orders()
    {
        if (!isset($this->orders)) {
            $this->orders =
                new mwap_pharmacy_orders_man(
                    $this->mainap
                );
        }

        return $this->orders;
    }

    final function __get_priv_payments()
    {
        if (!isset($this->payments)) {
            $this->payments =
                new mwap_pharmacy_payments_man(
                    $this->mainap
                );
        }

        return $this->payments;
    }

    final function __get_priv_inventory()
    {
        if (!isset($this->inventory)) {
            $this->inventory =
                new mwap_pharmacy_inventory_man(
                    $this->mainap
                );
        }

        return $this->inventory;
    }

    final function __get_priv_products()
    {
        if (!isset($this->products)) {
            $this->products =
                new mwap_pharmacy_products_man(
                    $this->mainap
                );
        }

        return $this->products;
    }

    final function __get_priv_reports()
    {
        if (!isset($this->reports)) {
            $this->reports =
                new mwap_pharmacy_reports_man(
                    $this->mainap
                );
        }

        return $this->reports;
    }
}