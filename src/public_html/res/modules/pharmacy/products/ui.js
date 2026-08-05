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

    function iniciarProductos() {
        var pagina = document.querySelector(
            ".productos-page"
        );

        if (
            !pagina ||
            pagina.getAttribute("data-productos-inicializado") === "1"
        ) {
            return;
        }

        pagina.setAttribute(
            "data-productos-inicializado",
            "1"
        );

        var buscador = pagina.querySelector(
            "#buscarProductoActivo"
        );

        var tabla = pagina.querySelector(
            "#tablaProductosActivos"
        );

        var sinResultados = pagina.querySelector(
            "#sinResultadosProductos"
        );

        function filtrarProductos() {
            if (!tabla) {
                return;
            }

            var texto = normalizar(
                buscador ? buscador.value : ""
            );

            var filas = tabla.querySelectorAll(
                "tbody tr:not(.fila-productos-vacia)"
            );

            var visibles = 0;

            filas.forEach(function (fila) {
                var contenido = normalizar(
                    fila.getAttribute("data-busqueda")
                );

                var mostrar =
                    texto === "" ||
                    contenido.indexOf(texto) !== -1;

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
                filtrarProductos
            );
        }

        pagina.addEventListener(
            "submit",
            function (evento) {
                var formulario = evento.target;

                if (
                    !formulario.classList.contains(
                        "form-eliminar-producto"
                    )
                ) {
                    return;
                }

                var nombre = formulario.getAttribute(
                    "data-producto-nombre"
                ) || "este producto";

                var confirmado = window.confirm(
                    "¿Eliminar " + nombre +
                    " de las listas activas?\n\n" +
                    "Los pedidos anteriores se conservarán."
                );

                if (!confirmado) {
                    evento.preventDefault();
                }
            }
        );

        filtrarProductos();
    }

    if (document.readyState === "loading") {
        document.addEventListener(
            "DOMContentLoaded",
            iniciarProductos
        );
    } else {
        iniciarProductos();
    }
})();
