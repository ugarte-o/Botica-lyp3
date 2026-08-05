(function () {
    "use strict";

    function iniciarCobranza() {
        var buscador =
            document.getElementById("buscarPedido");

        var tabla =
            document.getElementById("tablaPedidos");

        var monto =
            document.getElementById("montoRecibido");

        var vuelto =
            document.getElementById("montoVuelto");

        var previewRecibido =
            document.getElementById("previewRecibido");

        var previewVuelto =
            document.getElementById("previewVuelto");

        var formulario =
            document.getElementById("formPago");

        var actualizar =
            document.getElementById("btnActualizarCobranza");

        var imprimir =
            document.getElementById("btnImprimirTicket");

        var cerrar =
            document.getElementById("btnCerrarTicket");

        if (buscador && tabla) {
            buscador.addEventListener("input", function () {
                var texto = this.value
                    .toLowerCase()
                    .trim();

                var filas =
                    tabla.querySelectorAll("tbody tr");

                filas.forEach(function (fila) {
                    fila.style.display =
                        fila.textContent
                            .toLowerCase()
                            .includes(texto)
                            ? ""
                            : "none";
                });
            });
        }

        function calcularVuelto() {
            if (!monto) {
                return;
            }

            var total = parseFloat(
                monto.getAttribute("data-total") || "0"
            );

            var recibido =
                parseFloat(monto.value) || 0;

            var resultado =
                Math.max(recibido - total, 0);

            if (vuelto) {
                vuelto.textContent =
                    "S/ " + resultado.toFixed(2);
            }

            if (previewRecibido) {
                previewRecibido.textContent =
                    "S/ " + recibido.toFixed(2);
            }

            if (previewVuelto) {
                previewVuelto.textContent =
                    "S/ " + resultado.toFixed(2);
            }
        }

        if (monto) {
            monto.addEventListener(
                "input",
                calcularVuelto
            );

            calcularVuelto();
        }

        if (formulario) {
            formulario.addEventListener(
                "submit",
                function (evento) {
                    if (
                        !window.confirm(
                            "¿Confirmar el pago?"
                        )
                    ) {
                        evento.preventDefault();
                    }
                }
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

        if (imprimir) {
            imprimir.addEventListener(
                "click",
                function () {
                    window.print();
                }
            );
        }

        if (cerrar) {
            cerrar.addEventListener(
                "click",
                function () {
                    var ventana =
                        document.getElementById(
                            "ticketOverlay"
                        );

                    if (ventana) {
                        ventana.remove();
                    }
                }
            );
        }
    }

    if (document.readyState === "loading") {
        document.addEventListener(
            "DOMContentLoaded",
            iniciarCobranza
        );
    } else {
        iniciarCobranza();
    }
})();