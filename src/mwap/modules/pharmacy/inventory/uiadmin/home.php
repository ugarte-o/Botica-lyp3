<?php

class mwap_pharmacy_inventory_uiadmin_home
    extends mwmod_mw_ui_base_basesubui
{
    function __construct($cod, $parent)
    {
        $this->init_as_main_or_sub($cod, $parent);
        $this->set_lngmsgsmancod("pharmacy");
        $this->set_def_title("Inventario de productos");
    }

    function do_exec_no_sub_interface()
    {
        $util =
            new mwmod_mw_html_manager_uipreparers_ui($this);

        $util->preapare_ui();

        $css = new mwmod_mw_html_manager_item_css(
            "botica_inventario_css",
            "/res/modules/pharmacy/inventory/ui.css"
        );

        $util->add_css_item($css);

        $js = new mwmod_mw_html_manager_item_jsexternal(
            "botica_inventario_js",
            "/res/modules/pharmacy/inventory/ui.js"
        );

        $util->add_js_item($js);
    }

    function do_exec_page_in()
    {
        $productos = [];
        $error = "";

        try {
            $inventarioMan = $this->mainap->mainMan->inventory;
            

            $productos = $inventarioMan->get_inventario();
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }

        $totalDisponibles = 0;
        $totalStockBajo = 0;
        $totalAgotados = 0;
        $totalPorVencer = 0;

        $hoy = new DateTime("today");
        $limiteVencimiento = new DateTime("today");
        $limiteVencimiento->modify("+30 days");

        $filas = "";
        $categorias = [];

        $esc = static function ($valor) {
            return htmlspecialchars(
                (string) $valor,
                ENT_QUOTES,
                "UTF-8"
            );
        };

        foreach ($productos as $producto) {
            $stock = (int) $producto["stock"];
            $stockMinimo = (int) $producto["stock_minimo"];
            $estadoActivo = (int) $producto["estado"] === 1;

            $categoriaOriginal = trim(
                (string) $producto["categoria"]
            );

            if ($categoriaOriginal !== "") {
                $categorias[$categoriaOriginal] = $categoriaOriginal;
            }

            if ($estadoActivo) {
                if ($stock <= 0) {
                    $totalAgotados++;
                } elseif ($stock <= $stockMinimo) {
                    $totalStockBajo++;
                } else {
                    $totalDisponibles++;
                }
            }

            $fechaVencimiento = null;
            $fechaVencimientoTexto = "Sin fecha";

            if (!empty($producto["fecha_vencimiento"])) {
                try {
                    $fechaVencimiento = new DateTime(
                        (string) $producto["fecha_vencimiento"]
                    );

                    $fechaVencimientoTexto =
                        $fechaVencimiento->format("d/m/Y");

                    if (
                        $estadoActivo &&
                        $fechaVencimiento >= $hoy &&
                        $fechaVencimiento <= $limiteVencimiento
                    ) {
                        $totalPorVencer++;
                    }
                } catch (Throwable $e) {
                    $fechaVencimiento = null;
                    $fechaVencimientoTexto = "Fecha no válida";
                }
            }

            $estadoTexto = "Disponible";
            $estadoClase = "estado-disponible";
            $estadoFiltro = "disponible";

            if (!$estadoActivo) {
                $estadoTexto = "Inactivo";
                $estadoClase = "estado-inactivo";
                $estadoFiltro = "inactivo";
            } elseif (
                $fechaVencimiento !== null &&
                $fechaVencimiento < $hoy
            ) {
                $estadoTexto = "Vencido";
                $estadoClase = "estado-agotado";
                $estadoFiltro = "vencido";
            } elseif (
                $fechaVencimiento !== null &&
                $fechaVencimiento <= $limiteVencimiento
            ) {
                $estadoTexto = "Por vencer";
                $estadoClase = "estado-vencer";
                $estadoFiltro = "por vencer";
            } elseif ($stock <= 0) {
                $estadoTexto = "Agotado";
                $estadoClase = "estado-agotado";
                $estadoFiltro = "agotado";
            } elseif ($stock <= $stockMinimo) {
                $estadoTexto = "Stock bajo";
                $estadoClase = "estado-bajo";
                $estadoFiltro = "stock bajo";
            }

            $codigo = $esc($producto["codigo"]);
            $nombre = $esc($producto["nombre"]);
            $categoria = $esc($categoriaOriginal);

            $busqueda = $esc(
                $producto["codigo"] . " " .
                $producto["nombre"]
            );

            $filas .= '
                <tr
                    data-busqueda="' . $busqueda . '"
                    data-categoria="' . $categoria . '"
                    data-estado="' . $esc($estadoFiltro) . '"
                >
                    <td>' . $codigo . '</td>
                    <td>' . $nombre . '</td>
                    <td>' . $categoria . '</td>
                    <td>S/ ' . number_format(
                        (float) $producto["precio"],
                        2
                    ) . '</td>
                    <td>' . $stock . '</td>
                    <td>' . $stockMinimo . '</td>
                    <td>' . $esc($fechaVencimientoTexto) . '</td>
                    <td>
                        <span class="estado ' . $estadoClase . '">
                            ' . $esc($estadoTexto) . '
                        </span>
                    </td>
                </tr>
            ';
        }

        if ($filas === "") {
            $filas = '
                <tr class="fila-inventario-vacia">
                    <td colspan="8" class="tabla-vacia">
                        No hay productos registrados en la tabla productos.
                    </td>
                </tr>
            ';
        }

        ksort(
            $categorias,
            SORT_NATURAL |
            SORT_FLAG_CASE
        );
?>

<div class="inventario-page">
    <div class="inventario-header">
        <h2 class="inventario-title">
            Inventario de productos
        </h2>

        <p class="inventario-subtitle">
            Estos son los mismos productos utilizados
            en la pantalla de Pedidos.
        </p>
    </div>

    <?php if ($error !== "") { ?>
        <div class="mensaje-error">
            <?php echo $esc($error); ?>
        </div>
    <?php } ?>

    <div class="resumen-grid">
        <div class="resumen-card normal">
            <p class="resumen-label">Disponibles</p>
            <p class="resumen-numero">
                <?php echo $totalDisponibles; ?>
            </p>
        </div>

        <div class="resumen-card bajo">
            <p class="resumen-label">Stock bajo</p>
            <p class="resumen-numero">
                <?php echo $totalStockBajo; ?>
            </p>
        </div>

        <div class="resumen-card agotado">
            <p class="resumen-label">Agotados</p>
            <p class="resumen-numero">
                <?php echo $totalAgotados; ?>
            </p>
        </div>

        <div class="resumen-card vencimiento">
            <p class="resumen-label">Próximos a vencer</p>
            <p class="resumen-numero">
                <?php echo $totalPorVencer; ?>
            </p>
        </div>
    </div>

    <section class="inventario-panel">
        <div class="inventario-panel-header">
            <h3 class="inventario-panel-title">
                Lista de productos
            </h3>
        </div>

        <div class="inventario-panel-body">
            <div class="herramientas">
                <input
                    type="text"
                    id="buscarProducto"
                    class="inventario-form-control"
                    placeholder="Buscar por nombre o código..."
                >

                <select
                    id="filtroCategoria"
                    class="inventario-form-control"
                >
                    <option value="">
                        Todas las categorías
                    </option>

                    <?php foreach ($categorias as $categoria) { ?>
                        <option value="<?php echo $esc($categoria); ?>">
                            <?php echo $esc($categoria); ?>
                        </option>
                    <?php } ?>
                </select>

                <select
                    id="filtroEstado"
                    class="inventario-form-control"
                >
                    <option value="">Todos los estados</option>
                    <option value="disponible">Disponible</option>
                    <option value="stock bajo">Stock bajo</option>
                    <option value="agotado">Agotado</option>
                    <option value="por vencer">Por vencer</option>
                    <option value="vencido">Vencido</option>
                    <option value="inactivo">Inactivo</option>
                </select>

                <button
                    type="button"
                    id="btnActualizarInventario"
                    class="btn-actualizar"
                >
                    Actualizar
                </button>
            </div>

            <div class="tabla-responsive">
                <table
                    id="tablaInventario"
                    class="tabla-inventario"
                >
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Stock mínimo</th>
                            <th>Vencimiento</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php echo $filas; ?>
                    </tbody>
                </table>

                <p
                    id="sinResultadosInventario"
                    class="sin-resultados"
                    hidden
                >
                    No se encontraron productos con esos filtros.
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
