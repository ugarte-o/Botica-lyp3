<?php

class mwap_pharmacy_uiadmin_welcome
    extends mwmod_mw_ui_base_basesubuia
{
    function __construct($cod, $parent)
    {
        $this->init_as_main_or_sub(
            $cod,
            $parent
        );

        $this->set_def_title(
            "Inicio"
        );
    }

    function do_exec_no_sub_interface()
    {
    }

    private function escape_html($value)
    {
        return htmlspecialchars(
            (string) $value,
            ENT_QUOTES | ENT_SUBSTITUTE,
            "UTF-8"
        );
    }

    function do_exec_page_in()
    {
        $usuario = "Administrador";

        $resumen = array(
            "orders" => "Sin datos",
            "pendientes" => "Sin datos",
            "por_vencer" => "Sin datos"
        );

        $alertas = array();

        $accesos = array(
            array(
                "url" => "/admin/?ui=pharmacy&sui=orders",
                "clase" => "verde",
                "icono" => "fa-shopping-cart",
                "titulo" => "Nuevo pedido",
                "texto" => "Registrar una nueva venta"
            ),
            array(
                "url" => "/admin/?ui=pharmacy&sui=payments",
                "clase" => "azul",
                "icono" => "fa-credit-card",
                "titulo" => "Cobranza",
                "texto" => "Gestionar pagos pendientes"
            ),
            array(
                "url" => "/admin/?ui=pharmacy&sui=inventory",
                "clase" => "naranja",
                "icono" => "fa-cube",
                "titulo" => "Inventario",
                "texto" => "Consultar productos y stock"
            ),
            array(
                "url" => "/admin/?ui=pharmacy&sui=addproduct",
                "clase" => "verde",
                "icono" => "fa-plus-square",
                "titulo" => "Agregar producto",
                "texto" => "Registrar un nuevo producto"
            ),
            array(
                "url" => "/admin/?ui=pharmacy&sui=reports",
                "clase" => "azul",
                "icono" => "fa-chart-line",
                "titulo" => "Reportes",
                "texto" => "Consultar ventas y estadísticas"
            )
        );

        echo '
         <link
         rel="stylesheet"
         href="/res/modules/pharmacy/uiadmin/welcome.css?v=4"
         >';

        echo '
        <div class="botica-welcome">';

        echo '
        <section class="welcome-hero welcome-card">

            <div class="welcome-hero-main">

                <div class="welcome-hand">
                    <i
                        class="fas fa-hand-paper"
                        aria-hidden="true"
                    ></i>
                </div>

                <div class="welcome-hero-copy">

                    <h2>
                        Buenos días, ' .
                        $this->escape_html($usuario) .
                    '</h2>

                    <p>
                        Gestiona tu botica de manera
                        rápida y organizada.
                    </p>

                </div>

                <div
                    class="welcome-pharmacy"
                    aria-hidden="true"
                >
                    <span class="welcome-cross"></span>

                    <i class="fas fa-capsules"></i>
                </div>

            </div>

            <div class="welcome-verse">

                <i
                    class="fas fa-book-open"
                    aria-hidden="true"
                ></i>

                <div>
                    <strong>
                        Todo lo que hagan,
                        háganlo de corazón.
                    </strong>

                    <small>
                        Colosenses 3:23
                    </small>
                </div>

            </div>

        </section>';

        echo '
        <h3 class="welcome-section-title">
            Accesos rápidos
        </h3>

        <section class="welcome-shortcuts">';

        foreach ($accesos as $acceso) {
            echo '
            <a
                class="welcome-shortcut welcome-card"
                href="' .
                    $this->escape_html(
                        $acceso["url"]
                    ) .
                '"
            >

                <span class="welcome-shortcut-icon ' .
                    $this->escape_html(
                        $acceso["clase"]
                    ) .
                '">

                    <i
                        class="fas ' .
                            $this->escape_html(
                                $acceso["icono"]
                            ) .
                        '"
                        aria-hidden="true"
                    ></i>

                </span>

                <span class="welcome-shortcut-copy">

                    <strong>' .
                        $this->escape_html(
                            $acceso["titulo"]
                        ) .
                    '</strong>

                    <small>' .
                        $this->escape_html(
                            $acceso["texto"]
                        ) .
                    '</small>

                </span>

                <i
                    class="
                        fas
                        fa-chevron-right
                        welcome-arrow
                    "
                    aria-hidden="true"
                ></i>

            </a>';
        }

        echo '
        </section>';

        echo '
        <section class="welcome-panels">';

        echo '
        <article class="welcome-panel welcome-card">

            <h3>
                Alertas importantes
            </h3>

            <div class="welcome-alert-list">';

        if (empty($alertas)) {
            echo '
            <div class="welcome-alert">

                <span
                    class="welcome-dot"
                    style="background: #149b67;"
                ></span>

                <strong>
                    No hay alertas importantes actualmente.
                </strong>

                <span></span>

                <i
                    class="fas fa-check-circle"
                    style="color: #149b67;"
                    aria-hidden="true"
                ></i>

            </div>';
        }

        foreach ($alertas as $alerta) {
            $tipo = (
                $alerta["tipo"] === "peligro"
            )
                ? "peligro"
                : "advertencia";

            echo '
            <a
                class="welcome-alert"
                href="/admin/?ui=pharmacy&sui=inventory"
            >

                <span class="welcome-dot ' .
                    $tipo .
                '"></span>

                <strong>' .
                    $this->escape_html(
                        $alerta["producto"]
                    ) .
                '</strong>

                <span class="
                    welcome-alert-message ' .
                    $tipo .
                '">' .
                    $this->escape_html(
                        $alerta["mensaje"]
                    ) .
                '</span>

                <i
                    class="fas fa-chevron-right"
                    aria-hidden="true"
                ></i>

            </a>';
        }

        echo '
            </div>

            <a
                class="welcome-link"
                href="/admin/?ui=pharmacy&sui=inventory"
            >
                Ver inventario

                <i
                    class="fas fa-chevron-right"
                    aria-hidden="true"
                ></i>
            </a>

        </article>';

        echo '
        <article class="welcome-panel welcome-card">

            <h3>
                Resumen de hoy
            </h3>

            <div class="welcome-summary-row">

                <span class="
                    welcome-summary-icon
                    verde
                ">
                    <i
                        class="fas fa-shopping-cart"
                        aria-hidden="true"
                    ></i>
                </span>

                <strong>
                    Pedidos:
                </strong>

                <span class="welcome-summary-number">' .
                    $this->escape_html(
                        $resumen["orders"]
                    ) .
                '</span>

            </div>

            <div class="welcome-summary-row">

                <span class="
                    welcome-summary-icon
                    azul
                ">
                    <i
                        class="fas fa-credit-card"
                        aria-hidden="true"
                    ></i>
                </span>

                <strong>
                    Pendientes de cobro:
                </strong>

                <span class="welcome-summary-number">' .
                    $this->escape_html(
                        $resumen["pendientes"]
                    ) .
                '</span>

            </div>

            <div class="welcome-summary-row">

                <span class="
                    welcome-summary-icon
                    naranja
                ">
                    <i
                        class="fas fa-calendar-times"
                        aria-hidden="true"
                    ></i>
                </span>

                <strong>
                    Productos por vencer:
                </strong>

                <span class="welcome-summary-number">' .
                    $this->escape_html(
                        $resumen["por_vencer"]
                    ) .
                '</span>

            </div>

            <a
                class="welcome-link"
                href="/admin/?ui=pharmacy&sui=reports"
            >
                Ver reportes

                <i
                    class="fas fa-chevron-right"
                    aria-hidden="true"
                ></i>
            </a>

        </article>';

        echo '
        </section>

        </div>';
    }


   function is_allowed()
    {
        return $this->allow("admin");
    }

}


?>







