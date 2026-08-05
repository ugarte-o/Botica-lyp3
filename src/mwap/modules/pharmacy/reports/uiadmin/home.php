<?php

class mwap_pharmacy_reports_uiadmin_home
    extends mwmod_mw_ui_base_basesubui
{
    function __construct($cod, $parent)
    {
        $this->init_as_main_or_sub($cod, $parent);
        $this->set_lngmsgsmancod("pharmacy");
        $this->set_def_title("Reportes de ventas");
    }

    function do_exec_no_sub_interface()
    {
        $util =
            new mwmod_mw_html_manager_uipreparers_ui($this);

        $util->preapare_ui();

        $css = new mwmod_mw_html_manager_item_css(
            "botica_reportes_css",
            "/res/modules/pharmacy/reports/ui.css"
        );

        $util->add_css_item($css);

        $js = new mwmod_mw_html_manager_item_jsexternal(
            "botica_reportes_js",
            "/res/modules/pharmacy/reports/ui.js"
        );

        $util->add_js_item($js);
    }

    function do_exec_page_in()
    {
        $hoy = new DateTimeImmutable("today");

        $filtros = [
            "periodo" => "mes",
            "periodo_texto" => "Este mes",
            "fecha_desde" => $hoy
                ->modify("first day of this month")
                ->format("Y-m-d"),
            "fecha_hasta" => $hoy->format("Y-m-d"),
            "metodo_pago" => "",
            "buscar" => ""
        ];

        $resumenRapido = [
            "total_hoy" => 0,
            "ventas_hoy" => 0,
            "total_semana" => 0,
            "ventas_semana" => 0,
            "total_mes" => 0,
            "ventas_mes" => 0
        ];

        $reporte = [
            "resumen" => [
                "ventas" => 0,
                "total_vendido" => 0,
                "ticket_promedio" => 0,
                "clientes_unicos" => 0
            ],
            "ventas" => [],
            "productos" => [],
            "clientes" => [],
            "metodos" => [],
            "tendencia" => []
        ];

        $error = "";

        try {

            $reportesMan = $this->mainap->mainMan->reports;
            

            $filtros = $reportesMan->normalizar_filtros(
                $_GET
            );

            $resumenRapido = array_merge(
                $resumenRapido,
                $reportesMan->get_resumen_rapido()
            );

            $reporte = array_merge(
                $reporte,
                $reportesMan->get_reporte($filtros)
            );
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }

        $resumen = array_merge(
            [
                "ventas" => 0,
                "total_vendido" => 0,
                "ticket_promedio" => 0,
                "clientes_unicos" => 0
            ],
            $reporte["resumen"] ?? []
        );

        $ventas = $reporte["ventas"] ?? [];
        $productos = $reporte["productos"] ?? [];
        $clientes = $reporte["clientes"] ?? [];
        $metodos = $reporte["metodos"] ?? [];
        $tendencia = $reporte["tendencia"] ?? [];

        $maximoTendencia = 0.00;

        foreach ($tendencia as $dia) {
            $totalDia = (float) ($dia["total"] ?? 0);

            if ($totalDia > $maximoTendencia) {
                $maximoTendencia = $totalDia;
            }
        }

        $esc = static function ($valor) {
            return htmlspecialchars(
                (string) $valor,
                ENT_QUOTES,
                "UTF-8"
            );
        };

        $moneda = static function ($valor) {
            return "S/ " . number_format(
                (float) $valor,
                2
            );
        };

        $metodosPermitidos = [
            "Efectivo",
            "Yape",
            "Plin",
            "Tarjeta",
            "Transferencia"
        ];
?>

<div class="reportes-page">
    <header class="reportes-header">
        <div>
            <h2 class="reportes-title">
                Reportes de ventas
            </h2>

            <p class="reportes-subtitle">
                Historial administrativo de pedidos cobrados,
                clientes y productos vendidos.
            </p>
        </div>

        <div class="reportes-acciones no-imprimir">
            <button
                type="button"
                id="btnExportarReporte"
                class="btn-secundario"
            >
                Exportar CSV
            </button>

            <button
                type="button"
                id="btnImprimirReporte"
                class="btn-principal"
            >
                Imprimir
            </button>
        </div>
    </header>

    <?php if ($error !== "") { ?>
        <div class="mensaje-error">
            <?php echo $esc($error); ?>
        </div>
    <?php } ?>

    <section class="reportes-panel filtros-panel no-imprimir">
        <form method="get" id="formFiltrosReporte">
            <input type="hidden" name="ui" value="pharmacy">
            <input type="hidden" name="sui" value="reports">

            <div class="filtros-grid">
                <div class="form-group">
                    <label for="periodoReporte">Periodo</label>
                    <select
                        id="periodoReporte"
                        name="periodo"
                        class="reportes-form-control"
                    >
                        <option
                            value="hoy"
                            <?php echo $filtros["periodo"] === "hoy"
                                ? "selected"
                                : ""; ?>
                        >Hoy</option>
                        <option
                            value="semana"
                            <?php echo $filtros["periodo"] === "semana"
                                ? "selected"
                                : ""; ?>
                        >Esta semana</option>
                        <option
                            value="mes"
                            <?php echo $filtros["periodo"] === "mes"
                                ? "selected"
                                : ""; ?>
                        >Este mes</option>
                        <option
                            value="personalizado"
                            <?php echo $filtros["periodo"] === "personalizado"
                                ? "selected"
                                : ""; ?>
                        >Personalizado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fechaDesdeReporte">
                        Desde
                    </label>
                    <input
                        type="date"
                        id="fechaDesdeReporte"
                        name="fecha_desde"
                        class="reportes-form-control"
                        value="<?php echo $esc(
                            $filtros["fecha_desde"]
                        ); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="fechaHastaReporte">
                        Hasta
                    </label>
                    <input
                        type="date"
                        id="fechaHastaReporte"
                        name="fecha_hasta"
                        class="reportes-form-control"
                        value="<?php echo $esc(
                            $filtros["fecha_hasta"]
                        ); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="metodoPagoReporte">
                        Método de pago
                    </label>
                    <select
                        id="metodoPagoReporte"
                        name="metodo_pago"
                        class="reportes-form-control"
                    >
                        <option value="">Todos</option>
                        <?php foreach ($metodosPermitidos as $metodo) { ?>
                            <option
                                value="<?php echo $esc($metodo); ?>"
                                <?php echo $filtros["metodo_pago"] === $metodo
                                    ? "selected"
                                    : ""; ?>
                            >
                                <?php echo $esc($metodo); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group filtro-busqueda">
                    <label for="buscarReporte">
                        Pedido o cliente
                    </label>
                    <input
                        type="search"
                        id="buscarReporte"
                        name="buscar"
                        class="reportes-form-control"
                        value="<?php echo $esc(
                            $filtros["buscar"]
                        ); ?>"
                        placeholder="Código, nombre o documento..."
                    >
                </div>

                <div class="filtros-acciones">
                    <button
                        type="submit"
                        class="btn-principal"
                    >
                        Aplicar filtros
                    </button>

                    <a
                        href="?ui=pharmacy&sui=reports"
                        class="btn-limpiar"
                    >
                        Limpiar
                    </a>
                </div>
            </div>
        </form>
    </section>

    <div class="periodo-rapido-grid">
        <a href="?ui=pharmacy&sui=reports&periodo=hoy" class="periodo-card hoy">
            <span>Ventas de hoy</span>
            <strong><?php echo $moneda(
                $resumenRapido["total_hoy"]
            ); ?></strong>
            <small>
                <?php echo (int) $resumenRapido["ventas_hoy"]; ?>
                operaciones
            </small>
        </a>

        <a href="?ui=pharmacy&sui=reports&periodo=semana" class="periodo-card semana">
            <span>Esta semana</span>
            <strong><?php echo $moneda(
                $resumenRapido["total_semana"]
            ); ?></strong>
            <small>
                <?php echo (int) $resumenRapido["ventas_semana"]; ?>
                operaciones
            </small>
        </a>

        <a href="?ui=pharmacy&sui=reports&periodo=mes" class="periodo-card mes">
            <span>Este mes</span>
            <strong><?php echo $moneda(
                $resumenRapido["total_mes"]
            ); ?></strong>
            <small>
                <?php echo (int) $resumenRapido["ventas_mes"]; ?>
                operaciones
            </small>
        </a>
    </div>

    <div class="reporte-periodo-titulo">
        <div>
            <span>Reporte seleccionado</span>
            <strong><?php echo $esc(
                $filtros["periodo_texto"]
            ); ?></strong>
        </div>

        <small>
            <?php echo $esc(
                date("d/m/Y", strtotime($filtros["fecha_desde"]))
            ); ?>
            —
            <?php echo $esc(
                date("d/m/Y", strtotime($filtros["fecha_hasta"]))
            ); ?>
        </small>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card ventas-kpi">
            <span>Total vendido</span>
            <strong><?php echo $moneda(
                $resumen["total_vendido"]
            ); ?></strong>
        </div>

        <div class="kpi-card pedidos-kpi">
            <span>Pedidos pagados</span>
            <strong><?php echo (int) $resumen["ventas"]; ?></strong>
        </div>

        <div class="kpi-card clientes-kpi">
            <span>Clientes únicos</span>
            <strong><?php echo (int) $resumen["clientes_unicos"]; ?></strong>
        </div>

        <div class="kpi-card promedio-kpi">
            <span>Compra promedio</span>
            <strong><?php echo $moneda(
                $resumen["ticket_promedio"]
            ); ?></strong>
        </div>
    </div>

    <div class="analitica-grid">
        <section class="reportes-panel tendencia-panel">
            <div class="reportes-panel-header">
                <div>
                    <h3>Ventas por día</h3>
                    <p>Evolución del periodo seleccionado</p>
                </div>
            </div>

            <div class="reportes-panel-body">
                <?php if (count($tendencia) === 0) { ?>
                    <p class="estado-vacio">
                        No hay ventas para mostrar.
                    </p>
                <?php } else { ?>
                    <div class="grafico-barras">
                        <?php foreach ($tendencia as $dia) {
                            $porcentaje = $maximoTendencia > 0
                                ? max(
                                    5,
                                    round(
                                        ((float) $dia["total"] /
                                        $maximoTendencia) * 100,
                                        2
                                    )
                                )
                                : 5;
                        ?>
                            <div class="barra-item">
                                <div class="barra-valor">
                                    <?php echo $moneda($dia["total"]); ?>
                                </div>
                                <div class="barra-pista">
                                    <div
                                        class="barra"
                                        style="height: <?php echo $porcentaje; ?>%"
                                    ></div>
                                </div>
                                <div class="barra-fecha">
                                    <?php echo $esc(
                                        date(
                                            "d/m",
                                            strtotime($dia["fecha"])
                                        )
                                    ); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>

        <section class="reportes-panel">
            <div class="reportes-panel-header">
                <div>
                    <h3>Métodos de pago</h3>
                    <p>Operaciones por canal</p>
                </div>
            </div>

            <div class="reportes-panel-body ranking-lista">
                <?php if (count($metodos) === 0) { ?>
                    <p class="estado-vacio">Sin información.</p>
                <?php } ?>

                <?php foreach ($metodos as $metodo) { ?>
                    <div class="ranking-item">
                        <div>
                            <strong><?php echo $esc(
                                $metodo["metodo_pago"]
                            ); ?></strong>
                            <span>
                                <?php echo (int) $metodo["operaciones"]; ?>
                                operaciones
                            </span>
                        </div>
                        <b><?php echo $moneda($metodo["total"]); ?></b>
                    </div>
                <?php } ?>
            </div>
        </section>
    </div>

    <div class="ranking-grid">
        <section class="reportes-panel">
            <div class="reportes-panel-header">
                <div>
                    <h3>Productos más vendidos</h3>
                    <p>Top 5 por unidades</p>
                </div>
            </div>

            <div class="reportes-panel-body ranking-lista">
                <?php if (count($productos) === 0) { ?>
                    <p class="estado-vacio">Sin información.</p>
                <?php } ?>

                <?php foreach ($productos as $indice => $producto) { ?>
                    <div class="ranking-item">
                        <span class="ranking-posicion">
                            <?php echo $indice + 1; ?>
                        </span>
                        <div class="ranking-contenido">
                            <strong><?php echo $esc(
                                $producto["nombre"]
                            ); ?></strong>
                            <span>
                                <?php echo (int) $producto["unidades"]; ?>
                                unidades
                            </span>
                        </div>
                        <b><?php echo $moneda($producto["total"]); ?></b>
                    </div>
                <?php } ?>
            </div>
        </section>

        <section class="reportes-panel">
            <div class="reportes-panel-header">
                <div>
                    <h3>Clientes frecuentes</h3>
                    <p>Top 5 por total comprado</p>
                </div>
            </div>

            <div class="reportes-panel-body ranking-lista">
                <?php if (count($clientes) === 0) { ?>
                    <p class="estado-vacio">Sin información.</p>
                <?php } ?>

                <?php foreach ($clientes as $indice => $cliente) { ?>
                    <div class="ranking-item">
                        <span class="ranking-posicion cliente-posicion">
                            <?php echo $indice + 1; ?>
                        </span>
                        <div class="ranking-contenido">
                            <strong><?php echo $esc(
                                $cliente["cliente_nombre"]
                            ); ?></strong>
                            <span>
                                <?php echo (int) $cliente["compras"]; ?>
                                compras
                            </span>
                        </div>
                        <b><?php echo $moneda($cliente["total"]); ?></b>
                    </div>
                <?php } ?>
            </div>
        </section>
    </div>

    <section class="reportes-panel historial-panel">
        <div class="reportes-panel-header">
            <div>
                <h3>Historial de compras</h3>
                <p>
                    Hasta 500 pagos del periodo seleccionado
                </p>
            </div>

            <span class="resultados-contador">
                <?php echo count($ventas); ?> resultados
            </span>
        </div>

        <div class="reportes-panel-body">
            <div class="tabla-responsive">
                <table
                    id="tablaHistorialVentas"
                    class="tabla-reportes"
                >
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Documento</th>
                            <th>Método</th>
                            <th>Total</th>
                            <th class="no-imprimir">Detalle</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($ventas) === 0) { ?>
                            <tr>
                                <td colspan="7" class="estado-vacio">
                                    No se encontraron ventas pagadas.
                                </td>
                            </tr>
                        <?php } ?>

                        <?php foreach ($ventas as $venta) {
                            $detalle = array_values(
                                array_filter(
                                    explode(
                                        "||",
                                        (string) $venta["detalle"]
                                    )
                                )
                            );

                            $datosVenta = json_encode(
                                [
                                    "pago" => (int) $venta["pago_id"],
                                    "pedido" => (string) $venta["codigo"],
                                    "cliente" => (string) $venta["cliente_nombre"],
                                    "documento" => (string) $venta["cliente_documento"],
                                    "fecha" => date(
                                        "d/m/Y H:i",
                                        strtotime($venta["fecha_pago"])
                                    ),
                                    "metodo" => (string) $venta["metodo_pago"],
                                    "total" => $moneda($venta["monto_total"]),
                                    "recibido" => $moneda($venta["monto_recibido"]),
                                    "vuelto" => $moneda($venta["vuelto"]),
                                    "detalle" => $detalle
                                ],
                                JSON_UNESCAPED_UNICODE |
                                JSON_UNESCAPED_SLASHES
                            );
                        ?>
                            <tr data-venta-row>
                                <td>
                                    <?php echo $esc(
                                        date(
                                            "d/m/Y H:i",
                                            strtotime($venta["fecha_pago"])
                                        )
                                    ); ?>
                                </td>
                                <td>
                                    <strong><?php echo $esc(
                                        $venta["codigo"]
                                    ); ?></strong>
                                </td>
                                <td><?php echo $esc(
                                    $venta["cliente_nombre"]
                                ); ?></td>
                                <td><?php echo $esc(
                                    $venta["cliente_documento"] ?: "—"
                                ); ?></td>
                                <td>
                                    <span class="metodo-badge">
                                        <?php echo $esc(
                                            $venta["metodo_pago"]
                                        ); ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?php echo $moneda(
                                        $venta["monto_total"]
                                    ); ?></strong>
                                </td>
                                <td class="no-imprimir">
                                    <button
                                        type="button"
                                        class="btn-ver-detalle"
                                        data-venta="<?php echo $esc(
                                            $datosVenta ?: "{}"
                                        ); ?>"
                                    >
                                        Ver detalle
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<div
    id="modalDetalleVenta"
    class="modal-reporte"
    hidden
>
    <div class="modal-reporte-dialogo">
        <div class="modal-reporte-header">
            <div>
                <span>Detalle de compra</span>
                <h3 id="detallePedidoCodigo">Pedido</h3>
            </div>

            <button
                type="button"
                id="btnCerrarDetalleVenta"
                class="btn-cerrar-modal"
                aria-label="Cerrar"
            >×</button>
        </div>

        <div class="modal-reporte-body">
            <dl class="detalle-datos">
                <div><dt>Cliente</dt><dd id="detalleCliente"></dd></div>
                <div><dt>Documento</dt><dd id="detalleDocumento"></dd></div>
                <div><dt>Fecha</dt><dd id="detalleFecha"></dd></div>
                <div><dt>Método</dt><dd id="detalleMetodo"></dd></div>
            </dl>

            <div class="detalle-productos">
                <h4>Productos</h4>
                <ul id="detalleProductosLista"></ul>
            </div>

            <div class="detalle-totales">
                <div><span>Total</span><strong id="detalleTotal"></strong></div>
                <div><span>Recibido</span><strong id="detalleRecibido"></strong></div>
                <div><span>Vuelto</span><strong id="detalleVuelto"></strong></div>
            </div>
        </div>
    </div>
</div>

<?php
    }

    function is_allowed()
    {
        return $this->allow("admin");
    }
}