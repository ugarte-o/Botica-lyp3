(function () {
    "use strict";

    function normalizar(valor) {
        var texto = String(valor || "").toLowerCase();

        if (typeof texto.normalize === "function") {
            texto = texto
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "");
        }

        return texto.trim();
    }

    function iniciarInventario() {
        var pagina = document.querySelector(
            ".inventario-page"
        );

        if (
            !pagina ||
            pagina.getAttribute("data-inventario-inicializado") === "1"
        ) {
            return;
        }

        pagina.setAttribute(
            "data-inventario-inicializado",
            "1"
        );

        var buscador = pagina.querySelector(
            "#buscarProducto"
        );

        var filtroCategoria = pagina.querySelector(
            "#filtroCategoria"
        );

        var filtroEstado = pagina.querySelector(
            "#filtroEstado"
        );

        var tabla = pagina.querySelector(
            "#tablaInventario"
        );

        var actualizar = pagina.querySelector(
            "#btnActualizarInventario"
        );

        var sinResultados = pagina.querySelector(
            "#sinResultadosInventario"
        );

        function filtrarInventario() {
            if (!tabla) {
                return;
            }

            var texto = normalizar(
                buscador ? buscador.value : ""
            );

            var categoria = normalizar(
                filtroCategoria
                    ? filtroCategoria.value
                    : ""
            );

            var estado = normalizar(
                filtroEstado
                    ? filtroEstado.value
                    : ""
            );

            var filas = tabla.querySelectorAll(
                "tbody tr:not(.fila-inventario-vacia)"
            );

            var visibles = 0;

            filas.forEach(function (fila) {
                var busquedaFila = normalizar(
                    fila.getAttribute("data-busqueda")
                );

                var categoriaFila = normalizar(
                    fila.getAttribute("data-categoria")
                );

                var estadoFila = normalizar(
                    fila.getAttribute("data-estado")
                );

                var coincideTexto =
                    texto === "" ||
                    busquedaFila.indexOf(texto) !== -1;

                var coincideCategoria =
                    categoria === "" ||
                    categoriaFila === categoria;

                var coincideEstado =
                    estado === "" ||
                    estadoFila === estado;

                var mostrar =
                    coincideTexto &&
                    coincideCategoria &&
                    coincideEstado;

                fila.style.display = mostrar ? "" : "none";

                if (mostrar) {
                    visibles++;
                }
            });

            if (sinResultados) {
                sinResultados.hidden =
                    filas.length === 0 ||
                    visibles > 0;
            }
        }

        if (buscador) {
            buscador.addEventListener(
                "input",
                filtrarInventario
            );
        }

        if (filtroCategoria) {
            filtroCategoria.addEventListener(
                "change",
                filtrarInventario
            );
        }

        if (filtroEstado) {
            filtroEstado.addEventListener(
                "change",
                filtrarInventario
            );
        }

        if (actualizar) {
            actualizar.addEventListener(
                "click",
                function () {
                    window.location.reload();
                }
            );
        }

        filtrarInventario();
    }

    if (document.readyState === "loading") {
        document.addEventListener(
            "DOMContentLoaded",
            iniciarInventario
        );
    } else {
        iniciarInventario();
    }
})();
