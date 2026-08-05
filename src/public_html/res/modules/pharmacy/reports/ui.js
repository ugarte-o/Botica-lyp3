(function () {
    "use strict";

    function obtener(id) {
        return document.getElementById(id);
    }

    function iniciarReportes() {
        var pagina = document.querySelector(
            ".reportes-page"
        );

        if (
            !pagina ||
            pagina.getAttribute("data-reportes-inicializado") === "1"
        ) {
            return;
        }

        pagina.setAttribute(
            "data-reportes-inicializado",
            "1"
        );

        var periodo = obtener("periodoReporte");
        var fechaDesde = obtener("fechaDesdeReporte");
        var fechaHasta = obtener("fechaHastaReporte");
        var imprimir = obtener("btnImprimirReporte");
        var exportar = obtener("btnExportarReporte");
        var tabla = obtener("tablaHistorialVentas");
        var modal = obtener("modalDetalleVenta");
        var cerrarModal = obtener("btnCerrarDetalleVenta");

        function actualizarFechasPersonalizadas() {
            var personalizado =
                periodo && periodo.value === "personalizado";

            if (fechaDesde) {
                fechaDesde.disabled = !personalizado;
            }

            if (fechaHasta) {
                fechaHasta.disabled = !personalizado;
            }
        }

        function asignarTexto(id, valor) {
            var elemento = obtener(id);

            if (elemento) {
                elemento.textContent = valor || "—";
            }
        }

        function cerrarDetalle() {
            if (!modal) {
                return;
            }

            modal.hidden = true;
            document.body.style.overflow = "";
        }

        function abrirDetalle(datos) {
            if (!modal) {
                return;
            }

            asignarTexto("detallePedidoCodigo", datos.pedido);
            asignarTexto("detalleCliente", datos.cliente);
            asignarTexto("detalleDocumento", datos.documento);
            asignarTexto("detalleFecha", datos.fecha);
            asignarTexto("detalleMetodo", datos.metodo);
            asignarTexto("detalleTotal", datos.total);
            asignarTexto("detalleRecibido", datos.recibido);
            asignarTexto("detalleVuelto", datos.vuelto);

            var lista = obtener("detalleProductosLista");

            if (lista) {
                lista.textContent = "";

                var detalle = Array.isArray(datos.detalle)
                    ? datos.detalle
                    : [];

                if (detalle.length === 0) {
                    detalle = ["Sin detalle disponible"];
                }

                detalle.forEach(function (producto) {
                    var item = document.createElement("li");
                    item.textContent = producto;
                    lista.appendChild(item);
                });
            }

            modal.hidden = false;
            document.body.style.overflow = "hidden";
        }

        function escaparCsv(valor) {
            var texto = String(valor || "").replace(/\s+/g, " ").trim();

            return '"' + texto.replace(/"/g, '""') + '"';
        }

        function exportarCsv() {
            if (!tabla) {
                return;
            }

            var filas = [
                [
                    "Fecha",
                    "Pedido",
                    "Cliente",
                    "Documento",
                    "Método",
                    "Total"
                ]
            ];

            tabla
                .querySelectorAll("tbody tr[data-venta-row]")
                .forEach(function (fila) {
                    var celdas = fila.querySelectorAll("td");

                    filas.push([
                        celdas[0].textContent,
                        celdas[1].textContent,
                        celdas[2].textContent,
                        celdas[3].textContent,
                        celdas[4].textContent,
                        celdas[5].textContent
                    ]);
                });

            if (filas.length === 1) {
                window.alert("No hay ventas para exportar.");
                return;
            }

            var contenido = filas
                .map(function (fila) {
                    return fila.map(escaparCsv).join(";");
                })
                .join("\r\n");

            var archivo = new Blob(
                ["\ufeff" + contenido],
                { type: "text/csv;charset=utf-8" }
            );

            var enlace = document.createElement("a");
            enlace.href = URL.createObjectURL(archivo);
            enlace.download =
                "reporte-ventas-" +
                new Date().toISOString().slice(0, 10) +
                ".csv";

            document.body.appendChild(enlace);
            enlace.click();
            enlace.remove();
            URL.revokeObjectURL(enlace.href);
        }

        if (periodo) {
            periodo.addEventListener(
                "change",
                actualizarFechasPersonalizadas
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

        if (exportar) {
            exportar.addEventListener(
                "click",
                exportarCsv
            );
        }

        pagina.addEventListener(
            "click",
            function (evento) {
                var boton = evento.target.closest(
                    ".btn-ver-detalle"
                );

                if (!boton) {
                    return;
                }

                try {
                    abrirDetalle(
                        JSON.parse(
                            boton.getAttribute("data-venta") || "{}"
                        )
                    );
                } catch (error) {
                    window.alert(
                        "No se pudo abrir el detalle de la venta."
                    );
                }
            }
        );

        if (cerrarModal) {
            cerrarModal.addEventListener(
                "click",
                cerrarDetalle
            );
        }

        if (modal) {
            modal.addEventListener(
                "click",
                function (evento) {
                    if (evento.target === modal) {
                        cerrarDetalle();
                    }
                }
            );
        }

        document.addEventListener(
            "keydown",
            function (evento) {
                if (
                    evento.key === "Escape" &&
                    modal &&
                    !modal.hidden
                ) {
                    cerrarDetalle();
                }
            }
        );

        actualizarFechasPersonalizadas();
    }

    if (document.readyState === "loading") {
        document.addEventListener(
            "DOMContentLoaded",
            iniciarReportes
        );
    } else {
        iniciarReportes();
    }
})();
