<?php

class mwap_pharmacy_payments_man
    extends mwmod_mw_manager_baseman
{
    function __construct($mainAP)
    {
        $this->init("cobranza", $mainAP);
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
                "Meralda no tiene una conexión activa."
            );
        }

        mysqli_report(
            MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT
        );

        $conexion->set_charset("utf8mb4");

        return $conexion;
    }

    function get_pedidos_pendientes()
    {
        $conexion = $this->get_db_link();

        $resultado = $conexion->query("
            SELECT
                id,
                codigo,
                cliente_nombre,
                fecha_pedido,
                total,
                estado_pago
            FROM pedidos
            WHERE estado_pago = 'Pendiente'
            ORDER BY id DESC
        ");

        $pedidos = [];

        while ($pedido = $resultado->fetch_assoc()) {
            $pedidos[] = $pedido;
        }

        $resultado->free();

        return $pedidos;
    }

    function get_pedido_pendiente($pedidoId)
    {
        $pedidoId = (int) $pedidoId;

        if ($pedidoId <= 0) {
            return null;
        }

        $conexion = $this->get_db_link();

        $stmtPedido = $conexion->prepare("
            SELECT
                id,
                codigo,
                cliente_nombre,
                cliente_documento,
                fecha_pedido,
                subtotal,
                igv,
                total,
                estado_pago
            FROM pedidos
            WHERE id = ?
              AND estado_pago = 'Pendiente'
            LIMIT 1
        ");

        $stmtPedido->bind_param("i", $pedidoId);
        $stmtPedido->execute();

        $pedido = $stmtPedido
            ->get_result()
            ->fetch_assoc();

        $stmtPedido->close();

        if (!$pedido) {
            return null;
        }

        $stmtDetalle = $conexion->prepare("
            SELECT
                p.codigo,
                p.nombre,
                dp.cantidad,
                dp.precio_unitario,
                dp.subtotal
            FROM detalle_pedido dp
            INNER JOIN productos p
                ON p.id = dp.producto_id
            WHERE dp.pedido_id = ?
            ORDER BY dp.id ASC
        ");

        $stmtDetalle->bind_param("i", $pedidoId);
        $stmtDetalle->execute();

        $resultadoDetalle = $stmtDetalle->get_result();
        $detalle = [];

        while ($item = $resultadoDetalle->fetch_assoc()) {
            $detalle[] = $item;
        }

        $stmtDetalle->close();

        return [
            "pedido" => $pedido,
            "detalle" => $detalle
        ];
    }

    function registrar_pago($datos)
    {
        $pedidoId = isset($datos["pedido_id"])
            ? (int) $datos["pedido_id"]
            : 0;

        $montoTexto = str_replace(
            ",",
            ".",
            trim((string) ($datos["monto_recibido"] ?? ""))
        );

        $metodoPago = trim(
            (string) ($datos["metodo_pago"] ?? "")
        );

        $observacion = trim(
            (string) ($datos["observacion"] ?? "")
        );

        $metodosPermitidos = [
            "Efectivo",
            "Yape",
            "Plin",
            "Tarjeta",
            "Transferencia"
        ];

        if ($pedidoId <= 0) {
            throw new Exception("Selecciona un pedido.");
        }

        if (
            $montoTexto === "" ||
            !is_numeric($montoTexto) ||
            (float) $montoTexto < 0
        ) {
            throw new Exception(
                "Ingresa un monto recibido válido."
            );
        }

        if (!in_array($metodoPago, $metodosPermitidos, true)) {
            throw new Exception(
                "Selecciona un método de pago válido."
            );
        }

        $montoRecibido = round((float) $montoTexto, 2);
        $conexion = $this->get_db_link();

        $conexion->begin_transaction();

        try {
            $stmtPedido = $conexion->prepare("
                SELECT
                    id,
                    codigo,
                    cliente_nombre,
                    subtotal,
                    igv,
                    total,
                    estado_pago,
                    fecha_pedido
                FROM pedidos
                WHERE id = ?
                FOR UPDATE
            ");

            $stmtPedido->bind_param("i", $pedidoId);
            $stmtPedido->execute();

            $pedido = $stmtPedido
                ->get_result()
                ->fetch_assoc();

            $stmtPedido->close();

            if (!$pedido) {
                throw new Exception("El pedido no existe.");
            }

            if ($pedido["estado_pago"] !== "Pendiente") {
                throw new Exception(
                    "Este pedido ya fue pagado o anulado."
                );
            }

            $totalPedido = round(
                (float) $pedido["total"],
                2
            );

            if ($montoRecibido < $totalPedido) {
                throw new Exception(
                    "El monto recibido es menor al total."
                );
            }

            $vuelto = round(
                $montoRecibido - $totalPedido,
                2
            );

            $stmtPago = $conexion->prepare("
                INSERT INTO pagos (
                    pedido_id,
                    metodo_pago,
                    monto_total,
                    monto_recibido,
                    vuelto,
                    observacion
                )
                VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    NULLIF(?, '')
                )
            ");

            $stmtPago->bind_param(
                "isddds",
                $pedidoId,
                $metodoPago,
                $totalPedido,
                $montoRecibido,
                $vuelto,
                $observacion
            );

            $stmtPago->execute();
            $pagoId = (int) $conexion->insert_id;
            $stmtPago->close();

            $stmtActualizar = $conexion->prepare("
                UPDATE pedidos
                SET estado_pago = 'Pagado'
                WHERE id = ?
                  AND estado_pago = 'Pendiente'
            ");

            $stmtActualizar->bind_param("i", $pedidoId);
            $stmtActualizar->execute();

            if ($stmtActualizar->affected_rows !== 1) {
                throw new Exception(
                    "No se pudo actualizar el estado del pedido."
                );
            }

            $stmtActualizar->close();

            $stmtDetalle = $conexion->prepare("
                SELECT
                    p.nombre,
                    dp.cantidad,
                    dp.precio_unitario,
                    dp.subtotal
                FROM detalle_pedido dp
                INNER JOIN productos p
                    ON p.id = dp.producto_id
                WHERE dp.pedido_id = ?
                ORDER BY dp.id ASC
            ");

            $stmtDetalle->bind_param("i", $pedidoId);
            $stmtDetalle->execute();

            $resultadoDetalle = $stmtDetalle->get_result();
            $detalleTicket = [];

            while ($item = $resultadoDetalle->fetch_assoc()) {
                $detalleTicket[] = $item;
            }

            $stmtDetalle->close();
            $conexion->commit();

            return [
                "pago_id" => $pagoId,
                "pedido_id" => $pedidoId,
                "codigo" => $pedido["codigo"],
                "cliente" => $pedido["cliente_nombre"],
                "fecha" => date("d/m/Y H:i:s"),
                "metodo_pago" => $metodoPago,
                "subtotal" => (float) $pedido["subtotal"],
                "igv" => (float) $pedido["igv"],
                "monto_total" => $totalPedido,
                "monto_recibido" => $montoRecibido,
                "vuelto" => $vuelto,
                "detalle" => $detalleTicket
            ];
        } catch (Throwable $e) {
            $conexion->rollback();
            throw $e;
        }
    }

   

  }