<?php

class mwap_pharmacy_orders_uiadmin_orders
    extends mwmod_mw_ui_base_basesubui
{
    function __construct($cod, $parent)
    {
        $this->init_as_main_or_sub($cod, $parent);
        $this->set_lngmsgsmancod("pharmacy");
        $this->set_def_title("Pedidos");

        $this->js_ui_class_name =
            "mw_modules_pharmacy_orders_ui";
    }

    function do_exec_no_sub_interface()
    {
        $util =
            new mwmod_mw_html_manager_uipreparers_ui($this);

        $util->preapare_ui();

        $css = new mwmod_mw_html_manager_item_css(
            "botica_pedidos_css",
            "/res/modules/pharmacy/orders/ui.css"
        );

        $util->add_css_item($css);

        $js = new mwmod_mw_html_manager_item_jsexternal(
            "botica_pedidos_js",
            "/res/modules/pharmacy/orders/ui.js"
        );

        $util->add_js_item($js);

        $declaracion =
            $this->create_js_man_ui_header_declaration_item();

        $util->add_js_item($declaracion);
    }

    function do_exec_page_in()
    {
        $mensaje = "";
        $error = "";
        $pedidoRegistrado = null;
        $productos = [];

        try {
            $pedidosMan =
                $this->mainap->mainMan->orders;

            if (
                ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST" &&
                ($_POST["pedido_accion"] ?? "") === "registrar"
            ) {
                $pedidoRegistrado =
                    $pedidosMan->registrar_pedido($_POST);

                $mensaje =
                    "Pedido " .
                    $pedidoRegistrado["codigo"] .
                    " registrado correctamente.";
            }

            $productos =
                $pedidosMan->get_productos_disponibles();
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }

        $filasProductos = "";
        $productosJs = [];
        $categorias = [];

        foreach ($productos as $producto) {
            $id = (int) $producto["id"];
            $precio = (float) $producto["precio"];
            $stock = (int) $producto["stock"];

            $codigo = htmlspecialchars(
                (string) $producto["codigo"],
                ENT_QUOTES,
                "UTF-8"
            );

            $nombre = htmlspecialchars(
                (string) $producto["nombre"],
                ENT_QUOTES,
                "UTF-8"
            );

            $categoriaOriginal =
                (string) $producto["categoria"];

            $categoria = htmlspecialchars(
                $categoriaOriginal,
                ENT_QUOTES,
                "UTF-8"
            );

            if ($categoriaOriginal !== "") {
                $categorias[$categoriaOriginal] = true;
            }

            $productosJs[$id] = [
                "id" => $id,
                "codigo" => (string) $producto["codigo"],
                "nombre" => (string) $producto["nombre"],
                "categoria" => $categoriaOriginal,
                "precio" => $precio,
                "stock" => $stock
            ];

            $filasProductos .= '
                <tr
                    id="filaProducto_' . $id . '"
                    data-categoria="' . $categoria . '"
                >
                    <td>' . $codigo . '</td>
                    <td>' . $nombre . '</td>
                    <td>' . $categoria . '</td>
                    <td>S/ ' . number_format($precio, 2) . '</td>
                    <td>' . $stock . '</td>
                    <td>
                        <button
                            type="button"
                            class="btn-agregar"
                            id="btnAgregar_' . $id . '"
                            data-producto-id="' . $id . '"
                            ' . ($stock <= 0 ? "disabled" : "") . '
                        >
                            Agregar
                        </button>
                    </td>
                </tr>
            ';
        }

        if ($filasProductos === "") {
            $filasProductos = '
                <tr>
                    <td colspan="6" class="tabla-vacia">
                        No hay productos disponibles.
                    </td>
                </tr>
            ';
        }

        ksort($categorias, SORT_NATURAL | SORT_FLAG_CASE);

        $opcionesCategorias = "";

        foreach (array_keys($categorias) as $categoriaNombre) {
            $categoriaSegura = htmlspecialchars(
                $categoriaNombre,
                ENT_QUOTES,
                "UTF-8"
            );

            $opcionesCategorias .=
                '<option value="' .
                $categoriaSegura .
                '">' .
                $categoriaSegura .
                '</option>';
        }

        $limpiarAlRegistrar =
            $pedidoRegistrado !== null;

        $clienteNombre = $limpiarAlRegistrar
            ? ""
            : (string) ($_POST["cliente_nombre"] ?? "");

        $clienteDocumento = $limpiarAlRegistrar
            ? ""
            : (string) ($_POST["cliente_documento"] ?? "");

        $clienteTelefono = $limpiarAlRegistrar
            ? ""
            : (string) ($_POST["cliente_telefono"] ?? "");

        $clienteDireccion = $limpiarAlRegistrar
            ? ""
            : (string) ($_POST["cliente_direccion"] ?? "");

        $observaciones = $limpiarAlRegistrar
            ? ""
            : (string) ($_POST["observaciones"] ?? "");

        $containerId =
            $this->get_ui_elem_id_and_set_js_init_param(
                "container"
            );
?>

<div
    id="<?php echo htmlspecialchars(
        $containerId,
        ENT_QUOTES,
        "UTF-8"
    ); ?>"
    class="pedidos-page"
>
    <div class="pedidos-header">
        <h2 class="pedidos-title">Pedidos</h2>

        <p class="pedidos-subtitle">
            Selecciona productos, completa los datos del cliente
            y registra el pedido.
        </p>
    </div>

    <?php if ($mensaje !== "") { ?>
        <div class="mensaje-ok">
            <?php echo htmlspecialchars(
                $mensaje,
                ENT_QUOTES,
                "UTF-8"
            ); ?>
        </div>
    <?php } ?>

    <?php if ($error !== "") { ?>
        <div class="mensaje-error">
            <?php echo htmlspecialchars(
                $error,
                ENT_QUOTES,
                "UTF-8"
            ); ?>
        </div>
    <?php } ?>

    <form method="post" id="formRegistrarPedido">
        <input
            type="hidden"
            name="pedido_accion"
            value="registrar"
        >

        <input
            type="hidden"
            name="carrito_json"
            id="carritoJson"
            value="[]"
        >

        <div class="pedidos-grid">
            <div>
                <section class="pedido-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            1. Seleccionar productos
                        </h3>
                    </div>

                    <div class="panel-body">
                        <div class="productos-tools">
                            <input
                                type="text"
                                id="buscarProducto"
                                class="form-control"
                                placeholder="Buscar por nombre o código..."
                            >

                            <select
                                id="filtroCategoria"
                                class="form-control"
                            >
                                <option value="">
                                    Todos los productos
                                </option>

                                <?php echo $opcionesCategorias; ?>
                            </select>

                            <button
                                type="button"
                                id="btnActualizarProductos"
                                class="btn-actualizar"
                            >
                                Actualizar
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table
                                id="tablaProductos"
                                class="pedido-table"
                            >
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                        <th>Stock disponible</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php echo $filasProductos; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="pedido-panel detalle-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            2. Detalle del pedido
                        </h3>
                    </div>

                    <div class="panel-body">
                        <div class="detalle-layout">
                            <div>
                                <div class="form-group">
                                    <label class="form-label">
                                        Fecha
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control"
                                        value="<?php echo date("Y-m-d"); ?>"
                                        readonly
                                    >
                                </div>

                                <div class="form-group">
                                    <label
                                        for="observaciones"
                                        class="form-label"
                                    >
                                        Observaciones
                                    </label>

                                    <textarea
                                        name="observaciones"
                                        id="observaciones"
                                        class="form-control"
                                        rows="6"
                                        placeholder="Observación opcional..."
                                    ><?php echo htmlspecialchars(
                                        $observaciones,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ); ?></textarea>
                                </div>
                            </div>

                            <div>
                                <div class="table-responsive">
                                    <table class="pedido-table">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Subtotal</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>

                                        <tbody id="detalleCarrito">
                                            <tr>
                                                <td
                                                    colspan="5"
                                                    class="tabla-vacia"
                                                >
                                                    Aún no agregaste productos.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="total-pedido">
                                    <span>Subtotal del pedido</span>

                                    <span id="subtotalDetalle">
                                        S/ 0.00
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <aside>
                <section class="pedido-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            3. Datos del cliente
                        </h3>
                    </div>

                    <div class="panel-body">
                        <div class="form-group">
                            <label
                                for="clienteNombre"
                                class="form-label"
                            >
                                Nombre o razón social
                            </label>

                            <input
                                type="text"
                                name="cliente_nombre"
                                id="clienteNombre"
                                class="form-control"
                                value="<?php echo htmlspecialchars(
                                    $clienteNombre,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                                maxlength="150"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label
                                for="clienteDocumento"
                                class="form-label"
                            >
                                DNI
                            </label>

                            <input
                                type="text"
                                name="cliente_documento"
                                id="clienteDocumento"
                                class="form-control"
                                value="<?php echo htmlspecialchars(
                                    $clienteDocumento,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                                inputmode="numeric"
                                minlength="8"
                                maxlength="8"
                                pattern="[0-9]{8}"
                                placeholder="DNI de 8 dígitos"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label
                                for="clienteTelefono"
                                class="form-label"
                            >
                                Teléfono
                            </label>

                            <input
                                type="text"
                                name="cliente_telefono"
                                id="clienteTelefono"
                                class="form-control"
                                value="<?php echo htmlspecialchars(
                                    $clienteTelefono,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                                inputmode="numeric"
                                minlength="9"
                                maxlength="9"
                                pattern="9[0-9]{8}"
                                placeholder="Ejemplo: 987654321"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label
                                for="clienteDireccion"
                                class="form-label"
                            >
                                Dirección
                            </label>

                            <input
                                type="text"
                                name="cliente_direccion"
                                id="clienteDireccion"
                                class="form-control"
                                value="<?php echo htmlspecialchars(
                                    $clienteDireccion,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ); ?>"
                                maxlength="255"
                                placeholder="Dirección completa"
                                required
                            >
                        </div>
                    </div>
                </section>

                <section class="pedido-panel detalle-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            4. Resumen del pedido
                        </h3>
                    </div>

                    <div class="panel-body">
                        <div class="resumen-fila">
                            <span>Subtotal:</span>

                            <strong id="resumenSubtotal">
                                S/ 0.00
                            </strong>
                        </div>

                        <div class="resumen-fila">
                            <span>IGV (18%):</span>

                            <strong id="resumenIgv">
                                S/ 0.00
                            </strong>
                        </div>

                        <div class="resumen-total">
                            <span>Total:</span>

                            <span id="resumenTotal">
                                S/ 0.00
                            </span>
                        </div>

                        <div class="acciones">
                            <button
                                type="submit"
                                id="btnRegistrarPedido"
                                class="btn-registrar"
                                disabled
                            >
                                Registrar pedido
                            </button>

                            <button
                                type="button"
                                id="btnCancelarPedido"
                                class="btn-cancelar"
                            >
                                Cancelar pedido
                            </button>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </form>
</div>

<?php

        $this->ui_js_init_params->set_prop(
            "productos",
            $productosJs
        );

        $this->ui_js_init_params->set_prop(
            "limpiarAlRegistrar",
            $limpiarAlRegistrar
        );

        $variableJs =
            $this->get_js_ui_man_name();

        $codigoJs =
            new mwmod_mw_jsobj_codecontainer();

        $codigoJs->add_cont(
            $variableJs .
            ".init(" .
            $this->ui_js_init_params->get_as_js_val() .
            ");\n"
        );

        echo $codigoJs->get_js_script_html();
    }

    function is_allowed()
    {
        return $this->allow("admin");
    }
}