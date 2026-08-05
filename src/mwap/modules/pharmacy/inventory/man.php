<?php

class mwap_pharmacy_inventory_man
    extends mwmod_mw_manager_baseman
{
    function __construct($mainAP)
    {
        $this->init("inventario", $mainAP);
    }

    function get_db_link()
    {
        $dbManager = $this->mainap->getDBManager();

        if (!$dbManager) {
            throw new Exception(
                "Meralda no pudo cargar el administrador de base de datos."
            );
        }

        $conexion = $dbManager->get_link();

        if (!$conexion) {
            throw new Exception(
                "Meralda no tiene una conexión activa con la base de datos."
            );
        }

        mysqli_report(
            MYSQLI_REPORT_ERROR |
            MYSQLI_REPORT_STRICT
        );

        $conexion->set_charset("utf8mb4");

        return $conexion;
    }

    function get_inventario()
    {
        $conexion = $this->get_db_link();

        $resultado = $conexion->query("
            SELECT
                id,
                codigo,
                nombre,
                categoria,
                precio,
                stock,
                COALESCE(stock_minimo, 5) AS stock_minimo,
                fecha_vencimiento,
                estado
            FROM productos
            ORDER BY nombre ASC
        ");

        $productos = [];

        while ($producto = $resultado->fetch_assoc()) {
            $productos[] = $producto;
        }

        $resultado->free();

        return $productos;
    }
}
