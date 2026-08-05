<?php

class mwap_pharmacy_products_man
    extends mwmod_mw_manager_baseman
{
    function __construct($mainAP)
    {
        $this->init("productos", $mainAP);
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

    function listar_productos()
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
                fecha_vencimiento
            FROM productos
            WHERE estado = 1
            ORDER BY nombre ASC
        ");

        $productos = [];

        while ($producto = $resultado->fetch_assoc()) {
            $productos[] = $producto;
        }

        $resultado->free();

        return $productos;
    }

    function listar_productos_para_stock()
    {
        $conexion = $this->get_db_link();

        $resultado = $conexion->query("
            SELECT
                id,
                codigo,
                nombre,
                stock
            FROM productos
            WHERE estado = 1
            ORDER BY nombre ASC
        ");

        $productos = [];

        while ($producto = $resultado->fetch_assoc()) {
            $productos[] = $producto;
        }

        $resultado->free();

        return $productos;
    }

    function guardar_producto($datos)
    {
        $codigo = trim(
            (string) ($datos["codigo"] ?? "")
        );

        $nombre = trim(
            (string) ($datos["nombre"] ?? "")
        );

        $categoria = trim(
            (string) ($datos["categoria"] ?? "")
        );

        $precioTexto = str_replace(
            ",",
            ".",
            trim((string) ($datos["precio"] ?? ""))
        );

        $stockTexto = trim(
            (string) ($datos["stock"] ?? "")
        );

        $fechaVencimiento = trim(
            (string) ($datos["fecha_vencimiento"] ?? "")
        );

        if ($codigo === "") {
            throw new Exception("El código es obligatorio.");
        }

        if ($nombre === "") {
            throw new Exception("El nombre es obligatorio.");
        }

        if ($categoria === "") {
            throw new Exception("La categoría es obligatoria.");
        }

        if (
            $precioTexto === "" ||
            !is_numeric($precioTexto) ||
            (float) $precioTexto <= 0
        ) {
            throw new Exception(
                "El precio debe ser mayor que cero."
            );
        }

        if (
            $stockTexto === "" ||
            filter_var(
                $stockTexto,
                FILTER_VALIDATE_INT
            ) === false ||
            (int) $stockTexto < 0
        ) {
            throw new Exception(
                "El stock debe ser un número entero igual o mayor que cero."
            );
        }

        if (
            $fechaVencimiento !== "" &&
            !$this->fecha_es_valida($fechaVencimiento)
        ) {
            throw new Exception(
                "La fecha de vencimiento no es válida."
            );
        }

        $precio = round((float) $precioTexto, 2);
        $stock = (int) $stockTexto;
        $conexion = $this->get_db_link();

        $stmtExiste = $conexion->prepare("
            SELECT
                id,
                estado
            FROM productos
            WHERE codigo = ?
            LIMIT 1
        ");

        $stmtExiste->bind_param("s", $codigo);
        $stmtExiste->execute();

        $existente = $stmtExiste
            ->get_result()
            ->fetch_assoc();

        $stmtExiste->close();

        if ($existente) {
            if ((int) $existente["estado"] === 0) {
                throw new Exception(
                    "El código ya pertenece a un producto eliminado."
                );
            }

            throw new Exception(
                "El código ya existe. Usa la sección Aumentar stock."
            );
        }

        $stmt = $conexion->prepare("
            INSERT INTO productos (
                codigo,
                nombre,
                categoria,
                precio,
                stock,
                fecha_vencimiento,
                estado
            )
            VALUES (
                ?, ?, ?, ?, ?, NULLIF(?, ''), 1
            )
        ");

        $stmt->bind_param(
            "sssdis",
            $codigo,
            $nombre,
            $categoria,
            $precio,
            $stock,
            $fechaVencimiento
        );

        $stmt->execute();

        $insertId = (int) $conexion->insert_id;

        $stmt->close();

        return $insertId;
    }

    function agregar_stock($productoId, $cantidadAgregar)
    {
        $productoId = (int) $productoId;
        $cantidadAgregar = (int) $cantidadAgregar;

        if ($productoId <= 0) {
            throw new Exception("Selecciona un producto.");
        }

        if ($cantidadAgregar <= 0) {
            throw new Exception(
                "La cantidad debe ser mayor que cero."
            );
        }

        $conexion = $this->get_db_link();

        $conexion->begin_transaction();

        try {
            $stmtProducto = $conexion->prepare("
                SELECT
                    nombre,
                    stock
                FROM productos
                WHERE id = ?
                  AND estado = 1
                FOR UPDATE
            ");

            $stmtProducto->bind_param("i", $productoId);
            $stmtProducto->execute();

            $productoActual = $stmtProducto
                ->get_result()
                ->fetch_assoc();

            $stmtProducto->close();

            if (!$productoActual) {
                throw new Exception(
                    "El producto no existe o fue eliminado."
                );
            }

            $stmtStock = $conexion->prepare("
                UPDATE productos
                SET stock = stock + ?
                WHERE id = ?
                  AND estado = 1
            ");

            $stmtStock->bind_param(
                "ii",
                $cantidadAgregar,
                $productoId
            );

            $stmtStock->execute();

            if ($stmtStock->affected_rows !== 1) {
                throw new Exception(
                    "No se pudo actualizar el stock."
                );
            }

            $stmtStock->close();

            $stockNuevo =
                (int) $productoActual["stock"] +
                $cantidadAgregar;

            $conexion->commit();

            return $stockNuevo;
        } catch (Throwable $e) {
            $conexion->rollback();
            throw $e;
        }
    }

    function eliminar_producto($productoId)
    {
        $productoId = (int) $productoId;

        if ($productoId <= 0) {
            throw new Exception("Producto no válido.");
        }

        $conexion = $this->get_db_link();

        $stmt = $conexion->prepare("
            UPDATE productos
            SET estado = 0
            WHERE id = ?
              AND estado = 1
        ");

        $stmt->bind_param("i", $productoId);
        $stmt->execute();

        $eliminado = $stmt->affected_rows === 1;

        $stmt->close();

        if (!$eliminado) {
            throw new Exception(
                "El producto no existe o ya fue eliminado."
            );
        }

        return true;
    }

    function fecha_es_valida($fecha)
    {
        $valor = DateTime::createFromFormat(
            "Y-m-d",
            (string) $fecha
        );

        return
            $valor !== false &&
            $valor->format("Y-m-d") === $fecha;
    }
}
