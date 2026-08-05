<?php

class mwap_pharmacy_reports_man
    extends mwmod_mw_manager_baseman
{
    protected $fechaPagoSql = null;

    function __construct($mainAP)
    {
        $this->init("reportes", $mainAP);
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

    function normalizar_filtros($datos)
    {
        $periodosPermitidos = [
            "hoy",
            "semana",
            "mes",
            "personalizado"
        ];

        $periodo = (string) (
            $datos["periodo"] ?? "mes"
        );

        if (!in_array($periodo, $periodosPermitidos, true)) {
            $periodo = "mes";
        }

        $hoy = new DateTimeImmutable("today");

        if ($periodo === "hoy") {
            $desde = $hoy;
            $hasta = $hoy;
            $periodoTexto = "Hoy";
        } elseif ($periodo === "semana") {
            $desde = $hoy->modify("monday this week");
            $hasta = $hoy;
            $periodoTexto = "Esta semana";
        } elseif ($periodo === "personalizado") {
            $desde = $this->crear_fecha(
                (string) ($datos["fecha_desde"] ?? "")
            );

            $hasta = $this->crear_fecha(
                (string) ($datos["fecha_hasta"] ?? "")
            );

            if (!$desde || !$hasta) {
                throw new Exception(
                    "Selecciona un rango de fechas válido."
                );
            }

            if ($desde > $hasta) {
                throw new Exception(
                    "La fecha inicial no puede ser posterior a la final."
                );
            }

            if ($desde->diff($hasta)->days > 366) {
                throw new Exception(
                    "El reporte personalizado no puede superar 366 días."
                );
            }

            $periodoTexto = "Periodo personalizado";
        } else {
            $desde = $hoy->modify("first day of this month");
            $hasta = $hoy;
            $periodoTexto = "Este mes";
        }

        $metodosPermitidos = [
            "",
            "Efectivo",
            "Yape",
            "Plin",
            "Tarjeta",
            "Transferencia"
        ];

        $metodoPago = trim(
            (string) ($datos["metodo_pago"] ?? "")
        );

        if (!in_array($metodoPago, $metodosPermitidos, true)) {
            $metodoPago = "";
        }

        $buscar = trim(
            (string) ($datos["buscar"] ?? "")
        );

        if (strlen($buscar) > 100) {
            $buscar = substr($buscar, 0, 100);
        }

        return [
            "periodo" => $periodo,
            "periodo_texto" => $periodoTexto,
            "fecha_desde" => $desde->format("Y-m-d"),
            "fecha_hasta" => $hasta->format("Y-m-d"),
            "desde_sql" => $desde
                ->setTime(0, 0, 0)
                ->format("Y-m-d H:i:s"),
            "hasta_sql" => $hasta
                ->setTime(23, 59, 59)
                ->format("Y-m-d H:i:s"),
            "metodo_pago" => $metodoPago,
            "buscar" => $buscar
        ];
    }

    function get_reporte($filtros)
    {
        $fechaSql = $this->get_fecha_pago_sql();
        $where = $this->crear_where($fechaSql);

        $resumen = $this->obtener_una_fila("
            SELECT
                COUNT(*) AS ventas,
                COALESCE(SUM(pa.monto_total), 0) AS total_vendido,
                COALESCE(AVG(pa.monto_total), 0) AS ticket_promedio,
                COUNT(
                    DISTINCT CASE
                        WHEN COALESCE(pe.cliente_documento, '') <> ''
                            THEN pe.cliente_documento
                        ELSE CONCAT('N:', pe.cliente_nombre)
                    END
                ) AS clientes_unicos
            FROM pagos pa
            INNER JOIN pedidos pe
                ON pe.id = pa.pedido_id
            " . $where, $filtros);

        $ventas = $this->obtener_filas("
            SELECT
                pa.id AS pago_id,
                pe.id AS pedido_id,
                pe.codigo,
                pe.cliente_nombre,
                pe.cliente_documento,
                pa.metodo_pago,
                pa.monto_total,
                pa.monto_recibido,
                pa.vuelto,
                " . $fechaSql . " AS fecha_pago,
                COALESCE(det.detalle, '') AS detalle
            FROM pagos pa
            INNER JOIN pedidos pe
                ON pe.id = pa.pedido_id
            LEFT JOIN (
                SELECT
                    dp.pedido_id,
                    GROUP_CONCAT(
                        CONCAT(
                            pr.nombre,
                            ' × ',
                            dp.cantidad
                        )
                        ORDER BY dp.id ASC
                        SEPARATOR '||'
                    ) AS detalle
                FROM detalle_pedido dp
                INNER JOIN productos pr
                    ON pr.id = dp.producto_id
                GROUP BY dp.pedido_id
            ) det
                ON det.pedido_id = pe.id
            " . $where . "
            ORDER BY " . $fechaSql . " DESC, pa.id DESC
            LIMIT 500
        ", $filtros);

        $productos = $this->obtener_filas("
            SELECT
                pr.codigo,
                pr.nombre,
                SUM(dp.cantidad) AS unidades,
                SUM(dp.subtotal) AS total
            FROM pagos pa
            INNER JOIN pedidos pe
                ON pe.id = pa.pedido_id
            INNER JOIN detalle_pedido dp
                ON dp.pedido_id = pe.id
            INNER JOIN productos pr
                ON pr.id = dp.producto_id
            " . $where . "
            GROUP BY
                pr.id,
                pr.codigo,
                pr.nombre
            ORDER BY unidades DESC, total DESC
            LIMIT 5
        ", $filtros);

        $clientes = $this->obtener_filas("
            SELECT
                pe.cliente_nombre,
                pe.cliente_documento,
                COUNT(*) AS compras,
                SUM(pa.monto_total) AS total
            FROM pagos pa
            INNER JOIN pedidos pe
                ON pe.id = pa.pedido_id
            " . $where . "
            GROUP BY
                pe.cliente_nombre,
                pe.cliente_documento
            ORDER BY total DESC, compras DESC
            LIMIT 5
        ", $filtros);

        $metodos = $this->obtener_filas("
            SELECT
                pa.metodo_pago,
                COUNT(*) AS operaciones,
                SUM(pa.monto_total) AS total
            FROM pagos pa
            INNER JOIN pedidos pe
                ON pe.id = pa.pedido_id
            " . $where . "
            GROUP BY pa.metodo_pago
            ORDER BY total DESC
        ", $filtros);

        $tendencia = $this->obtener_filas("
            SELECT
                DATE(" . $fechaSql . ") AS fecha,
                COUNT(*) AS ventas,
                SUM(pa.monto_total) AS total
            FROM pagos pa
            INNER JOIN pedidos pe
                ON pe.id = pa.pedido_id
            " . $where . "
            GROUP BY DATE(" . $fechaSql . ")
            ORDER BY fecha ASC
        ", $filtros);

        return [
            "resumen" => $resumen,
            "ventas" => $ventas,
            "productos" => $productos,
            "clientes" => $clientes,
            "metodos" => $metodos,
            "tendencia" => $tendencia
        ];
    }

    function get_resumen_rapido()
    {
        $conexion = $this->get_db_link();
        $fechaSql = $this->get_fecha_pago_sql();

        $resultado = $conexion->query("
            SELECT
                COALESCE(SUM(
                    CASE
                        WHEN DATE(" . $fechaSql . ") = CURDATE()
                            THEN pa.monto_total
                        ELSE 0
                    END
                ), 0) AS total_hoy,
                SUM(
                    CASE
                        WHEN DATE(" . $fechaSql . ") = CURDATE()
                            THEN 1
                        ELSE 0
                    END
                ) AS ventas_hoy,
                COALESCE(SUM(
                    CASE
                        WHEN YEARWEEK(" . $fechaSql . ", 1) =
                             YEARWEEK(CURDATE(), 1)
                            THEN pa.monto_total
                        ELSE 0
                    END
                ), 0) AS total_semana,
                SUM(
                    CASE
                        WHEN YEARWEEK(" . $fechaSql . ", 1) =
                             YEARWEEK(CURDATE(), 1)
                            THEN 1
                        ELSE 0
                    END
                ) AS ventas_semana,
                COALESCE(SUM(
                    CASE
                        WHEN YEAR(" . $fechaSql . ") = YEAR(CURDATE())
                         AND MONTH(" . $fechaSql . ") = MONTH(CURDATE())
                            THEN pa.monto_total
                        ELSE 0
                    END
                ), 0) AS total_mes,
                SUM(
                    CASE
                        WHEN YEAR(" . $fechaSql . ") = YEAR(CURDATE())
                         AND MONTH(" . $fechaSql . ") = MONTH(CURDATE())
                            THEN 1
                        ELSE 0
                    END
                ) AS ventas_mes
            FROM pagos pa
            INNER JOIN pedidos pe
                ON pe.id = pa.pedido_id
        ");

        $fila = $resultado->fetch_assoc();
        $resultado->free();

        return $fila ?: [];
    }

    function get_fecha_pago_sql()
    {
        if ($this->fechaPagoSql !== null) {
            return $this->fechaPagoSql;
        }

        $conexion = $this->get_db_link();
        $resultado = $conexion->query("SHOW COLUMNS FROM pagos");
        $columnas = [];

        while ($columna = $resultado->fetch_assoc()) {
            $columnas[] = (string) $columna["Field"];
        }

        $resultado->free();

        $preferidas = [
            "fecha_pago",
            "fecha",
            "created_at",
            "fecha_registro"
        ];

        foreach ($preferidas as $columna) {
            if (in_array($columna, $columnas, true)) {
                $this->fechaPagoSql =
                    "pa.`" . $columna . "`";

                return $this->fechaPagoSql;
            }
        }

        $this->fechaPagoSql = "pe.fecha_pedido";

        return $this->fechaPagoSql;
    }

    function crear_where($fechaSql)
    {
        return "
            WHERE " . $fechaSql . " BETWEEN ? AND ?
              AND (
                    ? = ''
                    OR pa.metodo_pago = ?
              )
              AND (
                    ? = ''
                    OR pe.codigo LIKE CONCAT('%', ?, '%')
                    OR pe.cliente_nombre LIKE CONCAT('%', ?, '%')
                    OR COALESCE(pe.cliente_documento, '')
                        LIKE CONCAT('%', ?, '%')
              )
        ";
    }

    function obtener_una_fila($sql, $filtros)
    {
        $filas = $this->obtener_filas($sql, $filtros);

        return count($filas) > 0 ? $filas[0] : [];
    }

    function obtener_filas($sql, $filtros)
    {
        $conexion = $this->get_db_link();
        $stmt = $conexion->prepare($sql);

        $desde = (string) $filtros["desde_sql"];
        $hasta = (string) $filtros["hasta_sql"];
        $metodo1 = (string) $filtros["metodo_pago"];
        $metodo2 = $metodo1;
        $buscar1 = (string) $filtros["buscar"];
        $buscar2 = $buscar1;
        $buscar3 = $buscar1;
        $buscar4 = $buscar1;

        $stmt->bind_param(
            "ssssssss",
            $desde,
            $hasta,
            $metodo1,
            $metodo2,
            $buscar1,
            $buscar2,
            $buscar3,
            $buscar4
        );

        $stmt->execute();

        $resultado = $stmt->get_result();
        $filas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $filas[] = $fila;
        }

        $stmt->close();

        return $filas;
    }

    function crear_fecha($fecha)
    {
        $fecha = trim((string) $fecha);

        if ($fecha === "") {
            return null;
        }

        $valor = DateTimeImmutable::createFromFormat(
            "!Y-m-d",
            $fecha
        );

        if (
            $valor === false ||
            $valor->format("Y-m-d") !== $fecha
        ) {
            return null;
        }

        return $valor;
    }
}
