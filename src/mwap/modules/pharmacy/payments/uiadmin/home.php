<?php

class mwap_pharmacy_payments_uiadmin_home
    extends mwmod_mw_ui_base_basesubui
{
    function __construct($cod, $parent)
    {
        $this->init_as_main_or_sub($cod, $parent);
        $this->set_lngmsgsmancod("pharmacy");
        $this->set_def_title("Cobranza");
    }

    function do_exec_no_sub_interface()
    {
        $util =
            new mwmod_mw_html_manager_uipreparers_ui($this);

        $util->preapare_ui();

        $css = new mwmod_mw_html_manager_item_css(
            "botica_cobranza_css",
            "/res/modules/pharmacy/payments/ui.css"
        );

        $util->add_css_item($css);

        $js = new mwmod_mw_html_manager_item_jsexternal(
            "botica_cobranza_js",
            "/res/modules/pharmacy/payments/ui.js"
        );

        $util->add_js_item($js);
    }

    function do_exec_page_in()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $mensaje = "";
        $error = "";
        $pedidos = [];
        $pedidoSeleccionado = null;
        $detallePedido = [];
        $ticket = null;
        $cobranzaMan = null;

        try {
         $cobranzaMan =
          $this->mainap->mainMan->payments;
         } catch (Throwable $e) {
               $error = $e->getMessage();
     }
    


        if ($cobranzaMan !== null) {
            try {
                if (
                    ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST"
                ) {
                    $accion = (string) ($_POST["accion"] ?? "");

                    if ($accion === "seleccionar") {
                        $pedidoId = (int) (
                            $_POST["pedido_id"] ?? 0
                        );

                        if ($pedidoId <= 0) {
                            throw new Exception("Pedido no válido.");
                        }

                        $_SESSION["cobranza_pedido_id"] =
                            $pedidoId;
                    } elseif ($accion === "cancelar") {
                        unset($_SESSION["cobranza_pedido_id"]);
                        $mensaje = "Selección cancelada.";
                    } elseif ($accion === "confirmar_pago") {
                        $ticket =
                            $cobranzaMan->registrar_pago($_POST);

                        unset($_SESSION["cobranza_pedido_id"]);

                        $mensaje =
                            "Pago registrado correctamente.";
                    }
                }
            } catch (Throwable $e) {
                $error = $e->getMessage();
            }

            try {
                $pedidos =
                    $cobranzaMan->get_pedidos_pendientes();

                $pedidoSeleccionadoId = isset(
                    $_SESSION["cobranza_pedido_id"]
                )
                    ? (int) $_SESSION["cobranza_pedido_id"]
                    : 0;

                if ($pedidoSeleccionadoId > 0) {
                    $resultado =
                        $cobranzaMan->get_pedido_pendiente(
                            $pedidoSeleccionadoId
                        );

                    if ($resultado !== null) {
                        $pedidoSeleccionado =
                            $resultado["pedido"];

                        $detallePedido =
                            $resultado["detalle"];
                    } else {
                        unset(
                            $_SESSION["cobranza_pedido_id"]
                        );
                    }
                }
            } catch (Throwable $e) {
                $error = $e->getMessage();
            }
        }

        $pedidoIdActual = $pedidoSeleccionado
            ? (int) $pedidoSeleccionado["id"]
            : 0;

        $codigoActual = $pedidoSeleccionado
            ? (string) $pedidoSeleccionado["codigo"]
            : "---";

        $clienteActual = $pedidoSeleccionado
            ? (string) $pedidoSeleccionado["cliente_nombre"]
            : "---";

        $subtotalActual = $pedidoSeleccionado
            ? (float) $pedidoSeleccionado["subtotal"]
            : 0.00;

        $igvActual = $pedidoSeleccionado
            ? (float) $pedidoSeleccionado["igv"]
            : 0.00;

        $totalActual = $pedidoSeleccionado
            ? (float) $pedidoSeleccionado["total"]
            : 0.00;

        $esc = static function ($valor) {
            return htmlspecialchars(
                (string) $valor,
                ENT_QUOTES,
                "UTF-8"
            );
        };
?>

<div class="cobranza-page">
    <h2 class="cobranza-title">Cobranza</h2>

    <p class="cobranza-subtitle">
        Selecciona un pedido pendiente, procesa el pago
        y genera el ticket.
    </p>

    <?php if ($mensaje !== "") { ?>
        <div class="mensaje-ok">
            <?php echo $esc($mensaje); ?>
        </div>
    <?php } ?>

    <?php if ($error !== "") { ?>
        <div class="mensaje-error">
            <?php echo $esc($error); ?>
        </div>
    <?php } ?>

    <div class="cobranza-grid">
        <div>
            <section class="panel-cobranza">
                <div class="panel-cobranza-header">
                    <h3 class="panel-cobranza-title">
                        1. Pedidos pendientes de pago
                    </h3>
                </div>

                <div class="panel-cobranza-body">
                    <div class="busqueda">
                        <input
                            type="text"
                            id="buscarPedido"
                            class="cobranza-form-control"
                            placeholder="Buscar por pedido o cliente..."
                        >

                        <button
                            type="button"
                            id="btnActualizarCobranza"
                            class="btn-seleccionar"
                        >
                            Actualizar
                        </button>
                    </div>

                    <div class="tabla-responsive">
                        <table
                            id="tablaPedidos"
                            class="tabla-cobranza"
                        >
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (count($pedidos) === 0) { ?>
                                    <tr>
                                        <td
                                            colspan="6"
                                            class="tabla-vacia"
                                        >
                                            No hay pedidos pendientes de pago.
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php foreach ($pedidos as $pedido) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $esc(
                                                $pedido["codigo"]
                                            ); ?>
                                        </td>

                                        <td>
                                            <?php echo $esc(
                                                $pedido["cliente_nombre"]
                                            ); ?>
                                        </td>

                                        <td>
                                            <?php echo $esc(
                                                date(
                                                    "d/m/Y H:i",
                                                    strtotime(
                                                        $pedido["fecha_pedido"]
                                                    )
                                                )
                                            ); ?>
                                        </td>

                                        <td>
                                            S/ <?php echo number_format(
                                                (float) $pedido["total"],
                                                2
                                            ); ?>
                                        </td>

                                        <td>
                                            <span class="estado-pendiente">
                                                Pendiente
                                            </span>
                                        </td>

                                        <td>
                                            <form method="post">
                                                <input
                                                    type="hidden"
                                                    name="accion"
                                                    value="seleccionar"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="pedido_id"
                                                    value="<?php echo (int) $pedido["id"]; ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn-seleccionar"
                                                >
                                                    Seleccionar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section class="panel-cobranza">
                <div class="panel-cobranza-header">
                    <h3 class="panel-cobranza-title">
                        2. Detalle del pedido seleccionado
                    </h3>
                </div>

                <div class="panel-cobranza-body">
                    <div class="tabla-responsive">
                        <table class="tabla-cobranza">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (count($detallePedido) === 0) { ?>
                                    <tr>
                                        <td
                                            colspan="5"
                                            class="tabla-vacia"
                                        >
                                            Selecciona un pedido pendiente.
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php foreach ($detallePedido as $detalle) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $esc(
                                                $detalle["codigo"]
                                            ); ?>
                                        </td>

                                        <td>
                                            <?php echo $esc(
                                                $detalle["nombre"]
                                            ); ?>
                                        </td>

                                        <td>
                                            <?php echo (int) $detalle["cantidad"]; ?>
                                        </td>

                                        <td>
                                            S/ <?php echo number_format(
                                                (float) $detalle["precio_unitario"],
                                                2
                                            ); ?>
                                        </td>

                                        <td>
                                            S/ <?php echo number_format(
                                                (float) $detalle["subtotal"],
                                                2
                                            ); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="total-detalle">
                        <span>Total del pedido</span>

                        <span>
                            S/ <?php echo number_format(
                                $totalActual,
                                2
                            ); ?>
                        </span>
                    </div>
                </div>
            </section>
        </div>

        <aside>
            <section class="panel-cobranza">
                <div class="panel-cobranza-header">
                    <h3 class="panel-cobranza-title">
                        3. Procesar pago
                    </h3>
                </div>

                <div class="panel-cobranza-body">
                    <div class="pago-fila">
                        <span>Pedido:</span>
                        <strong><?php echo $esc($codigoActual); ?></strong>
                    </div>

                    <div class="pago-fila">
                        <span>Cliente:</span>
                        <strong><?php echo $esc($clienteActual); ?></strong>
                    </div>

                    <div class="pago-fila pago-total">
                        <span>Total:</span>
                        <span>
                            S/ <?php echo number_format(
                                $totalActual,
                                2
                            ); ?>
                        </span>
                    </div>

                    <form method="post" id="formPago">
                        <input
                            type="hidden"
                            name="accion"
                            value="confirmar_pago"
                        >

                        <input
                            type="hidden"
                            name="pedido_id"
                            value="<?php echo $pedidoIdActual; ?>"
                        >

                        <label
                            for="montoRecibido"
                            class="cobranza-form-label"
                        >
                            Monto recibido
                        </label>

                        <input
                            type="number"
                            id="montoRecibido"
                            name="monto_recibido"
                            class="cobranza-form-control"
                            min="<?php echo number_format(
                                $totalActual,
                                2,
                                ".",
                                ""
                            ); ?>"
                            step="0.01"
                            value="<?php echo number_format(
                                $totalActual,
                                2,
                                ".",
                                ""
                            ); ?>"
                            data-total="<?php echo number_format(
                                $totalActual,
                                2,
                                ".",
                                ""
                            ); ?>"
                            <?php echo $pedidoSeleccionado
                                ? "required"
                                : "disabled"; ?>
                        >

                        <div class="vuelto">
                            <span>Vuelto:</span>
                            <span id="montoVuelto">S/ 0.00</span>
                        </div>

                        <label class="cobranza-form-label">
                            Método de pago
                        </label>

                        <div class="metodos">
                            <?php
                            $metodosPago = [
                                "Efectivo",
                                "Yape",
                                "Plin",
                                "Tarjeta",
                                "Transferencia"
                            ];
                            ?>

                            <?php foreach ($metodosPago as $indice => $metodo) { ?>
                                <label class="metodo">
                                    <input
                                        type="radio"
                                        name="metodo_pago"
                                        value="<?php echo $esc($metodo); ?>"
                                        <?php echo $indice === 0
                                            ? "checked"
                                            : ""; ?>
                                        <?php echo $pedidoSeleccionado
                                            ? ""
                                            : "disabled"; ?>
                                    >

                                    <?php echo $esc($metodo); ?>
                                </label>
                            <?php } ?>
                        </div>

                        <label
                            for="observacionPago"
                            class="cobranza-form-label"
                        >
                            Observación
                        </label>

                        <textarea
                            id="observacionPago"
                            name="observacion"
                            class="cobranza-form-control"
                            rows="3"
                            placeholder="Observación opcional..."
                            <?php echo $pedidoSeleccionado
                                ? ""
                                : "disabled"; ?>
                        ></textarea>

                        <div class="acciones-cobranza">
                            <button
                                type="submit"
                                form="formCancelar"
                                class="btn-cancelar"
                                <?php echo $pedidoSeleccionado
                                    ? ""
                                    : "disabled"; ?>
                            >
                                Cancelar
                            </button>

                            <button
                                type="submit"
                                class="btn-confirmar"
                                <?php echo $pedidoSeleccionado
                                    ? ""
                                    : "disabled"; ?>
                            >
                                Confirmar pago
                            </button>
                        </div>
                    </form>

                    <form method="post" id="formCancelar">
                        <input
                            type="hidden"
                            name="accion"
                            value="cancelar"
                        >
                    </form>
                </div>
            </section>

            <section class="panel-cobranza">
                <div class="panel-cobranza-header">
                    <h3 class="panel-cobranza-title">
                        4. Vista previa del ticket
                    </h3>
                </div>

                <div class="panel-cobranza-body">
                    <div class="ticket-preview">
                        <div class="ticket-header">
                            <h3>BOTICA LyP</h3>
                            <p>Comprobante de pago</p>
                        </div>

                        <div class="ticket-line"></div>

                        <div class="ticket-row">
                            <span>Pedido:</span>
                            <strong><?php echo $esc($codigoActual); ?></strong>
                        </div>

                        <div class="ticket-row">
                            <span>Cliente:</span>
                            <strong><?php echo $esc($clienteActual); ?></strong>
                        </div>

                        <div class="ticket-row">
                            <span>Fecha:</span>
                            <strong><?php echo date("d/m/Y"); ?></strong>
                        </div>

                        <div class="ticket-line"></div>

                        <div class="ticket-row">
                            <span>Subtotal:</span>
                            <strong>
                                S/ <?php echo number_format(
                                    $subtotalActual,
                                    2
                                ); ?>
                            </strong>
                        </div>

                        <div class="ticket-row">
                            <span>IGV (18%):</span>
                            <strong>
                                S/ <?php echo number_format(
                                    $igvActual,
                                    2
                                ); ?>
                            </strong>
                        </div>

                        <div class="ticket-total">
                            <span>Total:</span>
                            <strong>
                                S/ <?php echo number_format(
                                    $totalActual,
                                    2
                                ); ?>
                            </strong>
                        </div>

                        <div class="ticket-row">
                            <span>Recibido:</span>
                            <strong id="previewRecibido">
                                S/ <?php echo number_format(
                                    $totalActual,
                                    2
                                ); ?>
                            </strong>
                        </div>

                        <div class="ticket-row">
                            <span>Vuelto:</span>
                            <strong id="previewVuelto">S/ 0.00</strong>
                        </div>

                        <div class="ticket-line"></div>

                        <div class="ticket-gracias">
                            Confirma el pago para generar
                            el ticket definitivo.
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>
</div>

<?php if ($ticket !== null) { ?>
    <div class="ticket-overlay" id="ticketOverlay">
        <div class="ticket-modal" id="ticketCobranza">
            <div class="ticket-header">
                <h2>BOTICA LyP</h2>
                <p>Comprobante de pago</p>
            </div>

            <div class="ticket-line"></div>

            <div class="ticket-row">
                <span>Pago:</span>
                <strong>N.º <?php echo (int) $ticket["pago_id"]; ?></strong>
            </div>

            <div class="ticket-row">
                <span>Pedido:</span>
                <strong><?php echo $esc($ticket["codigo"]); ?></strong>
            </div>

            <div class="ticket-row">
                <span>Cliente:</span>
                <strong><?php echo $esc($ticket["cliente"]); ?></strong>
            </div>

            <div class="ticket-row">
                <span>Fecha:</span>
                <strong><?php echo $esc($ticket["fecha"]); ?></strong>
            </div>

            <div class="ticket-row">
                <span>Método:</span>
                <strong><?php echo $esc($ticket["metodo_pago"]); ?></strong>
            </div>

            <div class="ticket-line"></div>

            <?php foreach ($ticket["detalle"] as $item) { ?>
                <div class="ticket-item">
                    <div><?php echo $esc($item["nombre"]); ?></div>

                    <div class="ticket-row">
                        <span>
                            <?php echo (int) $item["cantidad"]; ?>
                            × S/ <?php echo number_format(
                                (float) $item["precio_unitario"],
                                2
                            ); ?>
                        </span>

                        <strong>
                            S/ <?php echo number_format(
                                (float) $item["subtotal"],
                                2
                            ); ?>
                        </strong>
                    </div>
                </div>
            <?php } ?>

            <div class="ticket-line"></div>

            <div class="ticket-row">
                <span>Subtotal:</span>
                <strong>
                    S/ <?php echo number_format(
                        (float) $ticket["subtotal"],
                        2
                    ); ?>
                </strong>
            </div>

            <div class="ticket-row">
                <span>IGV (18%):</span>
                <strong>
                    S/ <?php echo number_format(
                        (float) $ticket["igv"],
                        2
                    ); ?>
                </strong>
            </div>

            <div class="ticket-total">
                <span>Total:</span>
                <strong>
                    S/ <?php echo number_format(
                        (float) $ticket["monto_total"],
                        2
                    ); ?>
                </strong>
            </div>

            <div class="ticket-row">
                <span>Recibido:</span>
                <strong>
                    S/ <?php echo number_format(
                        (float) $ticket["monto_recibido"],
                        2
                    ); ?>
                </strong>
            </div>

            <div class="ticket-row">
                <span>Vuelto:</span>
                <strong>
                    S/ <?php echo number_format(
                        (float) $ticket["vuelto"],
                        2
                    ); ?>
                </strong>
            </div>

            <div class="ticket-line"></div>

            <div class="ticket-gracias">
                Gracias por su compra.
            </div>

            <div class="ticket-actions">
                <button
                    type="button"
                    id="btnImprimirTicket"
                    class="btn-imprimir"
                >
                    Imprimir ticket
                </button>

                <button
                    type="button"
                    id="btnCerrarTicket"
                    class="btn-cerrar-ticket"
                >
                    Cerrar
                </button>
            </div>
        </div>
    </div>

<?php
    }

    }

    function is_allowed()
    {
        return $this->allow("admin");
    }
}