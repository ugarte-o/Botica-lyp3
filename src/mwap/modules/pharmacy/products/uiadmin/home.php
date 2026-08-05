<?php

class mwap_pharmacy_products_uiadmin_home
    extends mwmod_mw_ui_base_basesubui
{
    function __construct($cod, $parent)
    {
        $this->init_as_main_or_sub($cod, $parent);
        $this->set_lngmsgsmancod("pharmacy");
        $this->set_def_title("Agregar producto");
    }

    function do_exec_no_sub_interface()
    {
        $util =
            new mwmod_mw_html_manager_uipreparers_ui($this);

        $util->preapare_ui();

        $css = new mwmod_mw_html_manager_item_css(
            "botica_productos_css",
            "/res/modules/pharmacy/products/ui.css"
        );

        $util->add_css_item($css);

        $js = new mwmod_mw_html_manager_item_jsexternal(
            "botica_productos_js",
            "/res/modules/pharmacy/products/ui.js"
        );

        $util->add_js_item($js);
    }

    function do_exec_page_in()
    {
        $mensaje = "";
        $error = "";
        $productos = [];
        $productosParaStock = [];

        $datosFormulario = [
            "codigo" => "",
            "nombre" => "",
            "categoria" => "",
            "precio" => "",
            "stock" => "",
            "fecha_vencimiento" => ""
        ];

        try {
            $productosMan = $this->mainap->mainMan->products;
            

            if (
                ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST"
            ) {
                $accion = (string) (
                    $_POST["producto_accion"] ?? ""
                );

                if ($accion === "guardar") {
                    foreach ($datosFormulario as $campo => $valor) {
                        $datosFormulario[$campo] =
                            (string) ($_POST[$campo] ?? "");
                    }

                    $productosMan->guardar_producto($_POST);

                    $mensaje =
                        "Producto registrado correctamente.";

                    foreach ($datosFormulario as $campo => $valor) {
                        $datosFormulario[$campo] = "";
                    }
                } elseif ($accion === "agregar_stock") {
                    $cantidadAgregar = (int) (
                        $_POST["cantidad_agregar"] ?? 0
                    );

                    $productoId = (int) (
                        $_POST["producto_id"] ?? 0
                    );

                    $stockNuevo = $productosMan->agregar_stock(
                        $productoId,
                        $cantidadAgregar
                    );

                    $mensaje =
                        "Stock actualizado. Nuevo stock: " .
                        $stockNuevo .
                        ".";
                } elseif ($accion === "eliminar") {
                    $productoId = (int) (
                        $_POST["producto_id"] ?? 0
                    );

                    $productosMan->eliminar_producto(
                        $productoId
                    );

                    $mensaje =
                        "Producto eliminado de las listas activas.";
                }
            }

            $productos = $productosMan->listar_productos();

            $productosParaStock =
                $productosMan->listar_productos_para_stock();
        } catch (Throwable $e) {
            $error = $e->getMessage();

            try {
                if (isset($productosMan)) {
                    $productos =
                        $productosMan->listar_productos();

                    $productosParaStock =
                        $productosMan
                            ->listar_productos_para_stock();
                }
            } catch (Throwable $consultaError) {
            }
        }

        $esc = static function ($valor) {
            return htmlspecialchars(
                (string) $valor,
                ENT_QUOTES,
                "UTF-8"
            );
        };

        $filasProductos = "";
        $categorias = [];

        foreach ($productos as $producto) {
            $id = (int) $producto["id"];
            $stock = (int) $producto["stock"];

            $codigo = $esc($producto["codigo"]);
            $nombre = $esc($producto["nombre"]);
            $categoria = $esc($producto["categoria"]);

            $categoriaOriginal = trim(
                (string) $producto["categoria"]
            );

            if ($categoriaOriginal !== "") {
                $categorias[$categoriaOriginal] =
                    $categoriaOriginal;
            }

            $fechaVencimiento = !empty(
                $producto["fecha_vencimiento"]
            )
                ? date(
                    "d/m/Y",
                    strtotime($producto["fecha_vencimiento"])
                )
                : "Sin fecha";

            $stockClase = $stock <= 5
                ? "stock-bajo"
                : "stock-normal";

            $filasProductos .= '
                <tr data-busqueda="' . $esc(
                    $producto["codigo"] . " " .
                    $producto["nombre"] . " " .
                    $producto["categoria"]
                ) . '">
                    <td>' . $codigo . '</td>
                    <td>' . $nombre . '</td>
                    <td>' . $categoria . '</td>
                    <td>S/ ' . number_format(
                        (float) $producto["precio"],
                        2
                    ) . '</td>
                    <td>
                        <span class="stock-badge ' . $stockClase . '">
                            ' . $stock . '
                        </span>
                    </td>
                    <td>' . $esc($fechaVencimiento) . '</td>
                    <td>
                        <form
                            method="post"
                            class="form-eliminar-producto"
                            data-producto-nombre="' . $nombre . '"
                        >
                            <input
                                type="hidden"
                                name="producto_accion"
                                value="eliminar"
                            >
                            <input
                                type="hidden"
                                name="producto_id"
                                value="' . $id . '"
                            >
                            <button
                                type="submit"
                                class="btn-eliminar-producto"
                            >
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            ';
        }

        if ($filasProductos === "") {
            $filasProductos = '
                <tr class="fila-productos-vacia">
                    <td colspan="7" class="tabla-vacia">
                        No hay productos activos.
                    </td>
                </tr>
            ';
        }

        $opcionesProductos = "";

        foreach ($productosParaStock as $producto) {
            $opcionesProductos .=
                '<option value="' .
                (int) $producto["id"] .
                '">' .
                $esc($producto["codigo"]) .
                ' - ' .
                $esc($producto["nombre"]) .
                ' (' .
                (int) $producto["stock"] .
                ')</option>';
        }

        ksort(
            $categorias,
            SORT_NATURAL |
            SORT_FLAG_CASE
        );
?>

<div class="productos-page">
    <div class="productos-header">
        <div>
            <h2 class="productos-title">
                Gestión de productos
            </h2>

            <p class="productos-subtitle">
                Registra productos, actualiza existencias
                y administra la lista activa.
            </p>
        </div>

        <div class="productos-contador">
            <span>Productos activos</span>
            <strong><?php echo count($productos); ?></strong>
        </div>
    </div>

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

    <div class="productos-grid-superior">
        <section class="productos-panel">
            <div class="productos-panel-header">
                <div>
                    <h3 class="productos-panel-title">
                        Nuevo producto
                    </h3>
                    <p class="productos-panel-ayuda">
                        Completa los datos obligatorios del producto.
                    </p>
                </div>
            </div>

            <div class="productos-panel-body">
                <form
                    method="post"
                    id="formNuevoProducto"
                    class="form-producto"
                >
                    <input
                        type="hidden"
                        name="producto_accion"
                        value="guardar"
                    >

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="productoCodigo">
                                Código <span>*</span>
                            </label>
                            <input
                                id="productoCodigo"
                                class="productos-form-control"
                                type="text"
                                name="codigo"
                                maxlength="30"
                                value="<?php echo $esc(
                                    $datosFormulario["codigo"]
                                ); ?>"
                                placeholder="Ejemplo: PROD-007"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="productoNombre">
                                Nombre <span>*</span>
                            </label>
                            <input
                                id="productoNombre"
                                class="productos-form-control"
                                type="text"
                                name="nombre"
                                maxlength="120"
                                value="<?php echo $esc(
                                    $datosFormulario["nombre"]
                                ); ?>"
                                placeholder="Nombre del producto"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="productoCategoria">
                                Categoría <span>*</span>
                            </label>
                            <input
                                id="productoCategoria"
                                class="productos-form-control"
                                type="text"
                                name="categoria"
                                list="categoriasProducto"
                                maxlength="80"
                                value="<?php echo $esc(
                                    $datosFormulario["categoria"]
                                ); ?>"
                                placeholder="Selecciona o escribe una categoría"
                                required
                            >

                            <datalist id="categoriasProducto">
                                <?php foreach ($categorias as $categoria) { ?>
                                    <option value="<?php echo $esc(
                                        $categoria
                                    ); ?>">
                                <?php } ?>
                            </datalist>
                        </div>

                        <div class="form-group">
                            <label for="productoPrecio">
                                Precio <span>*</span>
                            </label>
                            <div class="input-moneda">
                                <span>S/</span>
                                <input
                                    id="productoPrecio"
                                    class="productos-form-control"
                                    type="number"
                                    name="precio"
                                    step="0.01"
                                    min="0.01"
                                    value="<?php echo $esc(
                                        $datosFormulario["precio"]
                                    ); ?>"
                                    placeholder="0.00"
                                    required
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="productoStock">
                                Stock inicial <span>*</span>
                            </label>
                            <input
                                id="productoStock"
                                class="productos-form-control"
                                type="number"
                                name="stock"
                                min="0"
                                step="1"
                                value="<?php echo $esc(
                                    $datosFormulario["stock"]
                                ); ?>"
                                placeholder="0"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="productoVencimiento">
                                Fecha de vencimiento
                            </label>
                            <input
                                id="productoVencimiento"
                                class="productos-form-control"
                                type="date"
                                name="fecha_vencimiento"
                                value="<?php echo $esc(
                                    $datosFormulario["fecha_vencimiento"]
                                ); ?>"
                            >
                        </div>
                    </div>

                    <div class="form-acciones">
                        <button
                            type="submit"
                            class="btn-accion-principal"
                        >
                            Guardar producto
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <aside class="productos-panel panel-stock">
            <div class="productos-panel-header">
                <div>
                    <h3 class="productos-panel-title">
                        Aumentar stock
                    </h3>
                    <p class="productos-panel-ayuda">
                        Suma unidades a un producto activo.
                    </p>
                </div>
            </div>

            <div class="productos-panel-body">
                <form method="post" id="formAumentarStock">
                    <input
                        type="hidden"
                        name="producto_accion"
                        value="agregar_stock"
                    >

                    <div class="form-group">
                        <label for="productoStockId">
                            Producto
                        </label>
                        <select
                            id="productoStockId"
                            class="productos-form-control"
                            name="producto_id"
                            required
                        >
                            <option value="">
                                Selecciona un producto
                            </option>
                            <?php echo $opcionesProductos; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cantidadAgregar">
                            Cantidad a agregar
                        </label>
                        <input
                            id="cantidadAgregar"
                            class="productos-form-control"
                            type="number"
                            min="1"
                            step="1"
                            name="cantidad_agregar"
                            placeholder="Ejemplo: 10"
                            required
                        >
                    </div>

                    <button
                        type="submit"
                        class="btn-accion-principal btn-stock"
                    >
                        Actualizar stock
                    </button>
                </form>
            </div>
        </aside>
    </div>

    <section class="productos-panel panel-listado">
        <div class="productos-panel-header listado-header">
            <div>
                <h3 class="productos-panel-title">
                    Productos activos
                </h3>
                <p class="productos-panel-ayuda">
                    Eliminar desactiva el producto sin borrar
                    sus pedidos históricos.
                </p>
            </div>

            <input
                type="search"
                id="buscarProductoActivo"
                class="productos-form-control buscador-productos"
                placeholder="Buscar producto..."
            >
        </div>

        <div class="productos-panel-body">
            <div class="tabla-responsive">
                <table
                    id="tablaProductosActivos"
                    class="tabla-productos"
                >
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Vencimiento</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php echo $filasProductos; ?>
                    </tbody>
                </table>

                <p
                    id="sinResultadosProductos"
                    class="sin-resultados"
                    hidden
                >
                    No se encontraron productos.
                </p>
            </div>
        </div>
    </section>
</div>

<?php
    }

    function is_allowed()
    {
        return $this->allow("admin");
    }
}
