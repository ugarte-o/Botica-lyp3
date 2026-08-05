<?php

class mwap_pharmacy_orders_man
    extends mwmod_mw_manager_baseman
{
    function __construct($mainAP)
    {
        $this->init("pedidos", $mainAP);
    }

    function get_db_link()
    {
        $dbManager =
            $this->mainap->getDBManager();

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

        $conexion->set_charset("utf8mb4");

        return $conexion;
    }

    function get_productos_disponibles()
    {
        $conexion = $this->get_db_link();

        $resultado = $conexion->query("
            SELECT
                id,
                codigo,
                nombre,
                categoria,
                precio,
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

    function registrar_pedido($datos)
    {
        $clienteNombre = trim(
            (string) ($datos["cliente_nombre"] ?? "")
        );

        $clienteDocumento = trim(
            (string) ($datos["cliente_documento"] ?? "")
        );

        $clienteTelefono = trim(
            (string) ($datos["cliente_telefono"] ?? "")
        );

        $clienteDireccion = trim(
            (string) ($datos["cliente_direccion"] ?? "")
        );

        $observaciones = trim(
            (string) ($datos["observaciones"] ?? "")
        );

        $carritoJson =
            (string) ($datos["carrito_json"] ?? "[]");

        $carrito = json_decode(
            $carritoJson,
            true
        );

        if ($clienteNombre === "") {
            throw new Exception(
                "El nombre del cliente es obligatorio."
            );
        }

        if (!preg_match('/^[0-9]{8}$/', $clienteDocumento)) {
            throw new Exception(
                "El DNI debe tener exactamente 8 dígitos."
            );
        }

        if (!preg_match('/^9[0-9]{8}$/', $clienteTelefono)) {
            throw new Exception(
                "El teléfono debe comenzar en 9 y tener exactamente 9 dígitos."
            );
        }

        if ($clienteDireccion === "") {
            throw new Exception(
                "La dirección del cliente es obligatoria."
            );
        }

        if (!is_array($carrito) || count($carrito) === 0) {
            throw new Exception(
                "Agrega al menos un producto."
            );
        }

        $cantidadesPorProducto = [];

        foreach ($carrito as $item) {
            $productoId = isset($item["id"])
                ? (int) $item["id"]
                : 0;

            $cantidad = isset($item["cantidad"])
                ? (int) $item["cantidad"]
                : 0;

            if ($productoId <= 0 || $cantidad <= 0) {
                throw new Exception(
                    "El carrito contiene datos inválidos."
                );
            }

            if (!isset($cantidadesPorProducto[$productoId])) {
                $cantidadesPorProducto[$productoId] = 0;
            }

            $cantidadesPorProducto[$productoId] += $cantidad;
        }

        $conexion = $this->get_db_link();

        mysqli_report(
            MYSQLI_REPORT_ERROR |
            MYSQLI_REPORT_STRICT
        );

        $conexion->begin_transaction();

        try {
            $detalleValidado = [];
            $subtotalPedido = 0.00;

            $stmtProducto = $conexion->prepare("
                SELECT
                    id,
                    nombre,
                    precio,
                    stock
                FROM productos
                WHERE id = ?
                  AND estado = 1
                FOR UPDATE
            ");

            foreach ($cantidadesPorProducto as $productoId => $cantidad) {
                $productoId = (int) $productoId;
                $cantidad = (int) $cantidad;

                $stmtProducto->bind_param(
                    "i",
                    $productoId
                );

                $stmtProducto->execute();

                $producto = $stmtProducto
                    ->get_result()
                    ->fetch_assoc();

                if (!$producto) {
                    throw new Exception(
                        "Uno de los productos ya no existe o está desactivado."
                    );
                }

                if ($cantidad > (int) $producto["stock"]) {
                    throw new Exception(
                        "Stock insuficiente para " .
                        $producto["nombre"] .
                        "."
                    );
                }

                $precio =
                    (float) $producto["precio"];

                $subtotal = round(
                    $precio * $cantidad,
                    2
                );

                $subtotalPedido += $subtotal;

                $detalleValidado[] = [
                    "producto_id" => $productoId,
                    "cantidad" => $cantidad,
                    "precio_unitario" => $precio,
                    "subtotal" => $subtotal
                ];
            }

            $stmtProducto->close();

            $subtotalPedido = round(
                $subtotalPedido,
                2
            );

            $igv = round(
                $subtotalPedido * 0.18,
                2
            );

            $total = round(
                $subtotalPedido + $igv,
                2
            );

            $stmtPedido = $conexion->prepare("
                INSERT INTO pedidos (
                    codigo,
                    cliente_nombre,
                    cliente_documento,
                    cliente_telefono,
                    cliente_direccion,
                    observaciones,
                    subtotal,
                    igv,
                    total,
                    estado_pago,
                    estado_despacho
                )
                VALUES (
                    NULL,
                    ?,
                    ?,
                    ?,
                    ?,
                    NULLIF(?, ''),
                    ?,
                    ?,
                    ?,
                    'Pendiente',
                    'Pendiente'
                )
            ");

            $stmtPedido->bind_param(
                "sssssddd",
                $clienteNombre,
                $clienteDocumento,
                $clienteTelefono,
                $clienteDireccion,
                $observaciones,
                $subtotalPedido,
                $igv,
                $total
            );

            $stmtPedido->execute();

            $pedidoId =
                (int) $conexion->insert_id;

            $stmtPedido->close();

            $codigoPedido = "PED-" . str_pad(
                (string) $pedidoId,
                5,
                "0",
                STR_PAD_LEFT
            );

            $stmtCodigo = $conexion->prepare("
                UPDATE pedidos
                SET codigo = ?
                WHERE id = ?
            ");

            $stmtCodigo->bind_param(
                "si",
                $codigoPedido,
                $pedidoId
            );

            $stmtCodigo->execute();
            $stmtCodigo->close();

            $stmtDetalle = $conexion->prepare("
                INSERT INTO detalle_pedido (
                    pedido_id,
                    producto_id,
                    cantidad,
                    precio_unitario,
                    subtotal
                )
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmtStock = $conexion->prepare("
                UPDATE productos
                SET stock = stock - ?
                WHERE id = ?
                  AND stock >= ?
            ");

            foreach ($detalleValidado as $detalle) {
                $productoId =
                    (int) $detalle["producto_id"];

                $cantidad =
                    (int) $detalle["cantidad"];

                $precio =
                    (float) $detalle["precio_unitario"];

                $subtotal =
                    (float) $detalle["subtotal"];

                $stmtDetalle->bind_param(
                    "iiidd",
                    $pedidoId,
                    $productoId,
                    $cantidad,
                    $precio,
                    $subtotal
                );

                $stmtDetalle->execute();

                $stmtStock->bind_param(
                    "iii",
                    $cantidad,
                    $productoId,
                    $cantidad
                );

                $stmtStock->execute();

                if ($stmtStock->affected_rows !== 1) {
                    throw new Exception(
                        "No se pudo actualizar el stock del producto."
                    );
                }
            }

            $stmtDetalle->close();
            $stmtStock->close();

            $conexion->commit();

            return [
                "id" => $pedidoId,
                "codigo" => $codigoPedido,
                "cliente" => $clienteNombre,
                "subtotal" => $subtotalPedido,
                "igv" => $igv,
                "total" => $total
            ];
        } catch (Throwable $e) {
            $conexion->rollback();
            throw $e;
        }
    }
}