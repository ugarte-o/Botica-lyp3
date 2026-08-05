function mw_modules_pharmacy_orders_ui(info) {
    this.info = new mw_obj();
    this.info.set_params(info);

    this.productos = {};
    this.carrito = {};

    this.claveCarrito = "botica_pedido_carrito";
    this.claveCliente = "botica_pedido_cliente";

    this.get_elemento = function (id) {
        return document.getElementById(id);
    };

    this.get_valor = function (id) {
        var elemento = this.get_elemento(id);

        return elemento
            ? elemento.value
            : "";
    };

    this.set_valor = function (id, valor) {
        var elemento = this.get_elemento(id);

        if (elemento) {
            elemento.value = valor || "";
        }
    };

    this.cargar_productos = function () {
        var datos = this.params.get_param_or_def(
            "productos",
            []
        );

        this.productos = {};

        if (Array.isArray(datos)) {
            datos.forEach(function (producto) {
                if (
                    producto &&
                    producto.id !== undefined
                ) {
                    this.productos[
                        Number(producto.id)
                    ] = producto;
                }
            }, this);

            return;
        }

        if (
            datos &&
            typeof datos === "object"
        ) {
            Object.keys(datos).forEach(function (clave) {
                var producto = datos[clave];

                if (
                    producto &&
                    producto.id !== undefined
                ) {
                    this.productos[
                        Number(producto.id)
                    ] = producto;
                }
            }, this);
        }
    };

    this.cargar_carrito = function () {
        var pedidoRegistrado = Boolean(
            this.params.get_param_or_def(
                "limpiarAlRegistrar",
                false
            )
        );

        if (pedidoRegistrado) {
            this.carrito = {};

            try {
                localStorage.removeItem(
                    this.claveCarrito
                );

                localStorage.removeItem(
                    this.claveCliente
                );
            } catch (error) {
            }

            return;
        }

        try {
            var guardado = JSON.parse(
                localStorage.getItem(
                    this.claveCarrito
                ) || "{}"
            );

            this.carrito =
                guardado &&
                typeof guardado === "object"
                    ? guardado
                    : {};
        } catch (error) {
            this.carrito = {};
        }
    };

    this.guardar_datos_temporales = function () {
        try {
            localStorage.setItem(
                this.claveCarrito,
                JSON.stringify(this.carrito)
            );

            var cliente = {
                nombre:
                    this.get_valor(
                        "clienteNombre"
                    ),
                documento:
                    this.get_valor(
                        "clienteDocumento"
                    ),
                telefono:
                    this.get_valor(
                        "clienteTelefono"
                    ),
                direccion:
                    this.get_valor(
                        "clienteDireccion"
                    ),
                observaciones:
                    this.get_valor(
                        "observaciones"
                    )
            };

            localStorage.setItem(
                this.claveCliente,
                JSON.stringify(cliente)
            );
        } catch (error) {
        }
    };

    this.restaurar_cliente = function () {
        var pedidoRegistrado = Boolean(
            this.params.get_param_or_def(
                "limpiarAlRegistrar",
                false
            )
        );

        if (pedidoRegistrado) {
            return;
        }

        try {
            var cliente = JSON.parse(
                localStorage.getItem(
                    this.claveCliente
                ) || "{}"
            );

            if (
                !cliente ||
                typeof cliente !== "object"
            ) {
                return;
            }

            if (cliente.nombre !== undefined) {
                this.set_valor(
                    "clienteNombre",
                    cliente.nombre
                );
            }

            if (cliente.documento !== undefined) {
                this.set_valor(
                    "clienteDocumento",
                    cliente.documento
                );
            }

            if (cliente.telefono !== undefined) {
                this.set_valor(
                    "clienteTelefono",
                    cliente.telefono
                );
            }

            if (cliente.direccion !== undefined) {
                this.set_valor(
                    "clienteDireccion",
                    cliente.direccion
                );
            }

            if (
                cliente.observaciones !== undefined
            ) {
                this.set_valor(
                    "observaciones",
                    cliente.observaciones
                );
            }
        } catch (error) {
        }
    };

    this.cantidad_en_carrito = function (id) {
        return this.carrito[id]
            ? Number(
                this.carrito[id].cantidad
            ) || 0
            : 0;
    };

    this.agregar_producto = function (id) {
        id = Number(id);

        var producto = this.productos[id];

        if (!producto) {
            return;
        }

        var cantidadActual =
            this.cantidad_en_carrito(id);

        if (
            cantidadActual >=
            Number(producto.stock)
        ) {
            alert(
                "No hay más stock disponible."
            );

            return;
        }

        this.carrito[id] = {
            id: id,
            cantidad: cantidadActual + 1
        };

        this.guardar_datos_temporales();
        this.renderizar();
    };

    this.cambiar_cantidad = function (
        id,
        cambio
    ) {
        id = Number(id);

        var producto = this.productos[id];

        if (
            !producto ||
            !this.carrito[id]
        ) {
            return;
        }

        var nuevaCantidad =
            Number(
                this.carrito[id].cantidad
            ) + Number(cambio);

        if (nuevaCantidad <= 0) {
            delete this.carrito[id];
        } else if (
            nuevaCantidad >
            Number(producto.stock)
        ) {
            alert(
                "No hay más stock disponible."
            );

            return;
        } else {
            this.carrito[id].cantidad =
                nuevaCantidad;
        }

        this.guardar_datos_temporales();
        this.renderizar();
    };

    this.eliminar_producto = function (id) {
        delete this.carrito[Number(id)];

        this.guardar_datos_temporales();
        this.renderizar();
    };

    this.cancelar_pedido = function () {
        if (
            !confirm(
                "¿Cancelar el pedido actual?"
            )
        ) {
            return;
        }

        this.carrito = {};

        try {
            localStorage.removeItem(
                this.claveCarrito
            );

            localStorage.removeItem(
                this.claveCliente
            );
        } catch (error) {
        }

        this.set_valor(
            "clienteNombre",
            ""
        );

        this.set_valor(
            "clienteDocumento",
            ""
        );

        this.set_valor(
            "clienteTelefono",
            ""
        );

        this.set_valor(
            "clienteDireccion",
            ""
        );

        this.set_valor(
            "observaciones",
            ""
        );

        this.renderizar();
    };

    this.escapar_html = function (texto) {
        var div =
            document.createElement("div");

        div.textContent =
            String(texto || "");

        return div.innerHTML;
    };

    this.actualizar_texto = function (
        id,
        texto
    ) {
        var elemento =
            this.get_elemento(id);

        if (elemento) {
            elemento.textContent = texto;
        }
    };

    this.renderizar = function () {
        var detalle =
            this.get_elemento(
                "detalleCarrito"
            );

        if (!detalle) {
            return;
        }

        var html = "";
        var subtotal = 0;
        var carritoParaEnviar = [];

        Object.keys(
            this.carrito
        ).forEach(function (clave) {
            var id = Number(clave);
            var producto =
                this.productos[id];

            if (!producto) {
                delete this.carrito[id];
                return;
            }

            var cantidad =
                Number(
                    this.carrito[id].cantidad
                ) || 0;

            if (
                cantidad >
                Number(producto.stock)
            ) {
                cantidad =
                    Number(producto.stock);

                this.carrito[id].cantidad =
                    cantidad;
            }

            if (cantidad <= 0) {
                delete this.carrito[id];
                return;
            }

            var subtotalProducto =
                Number(producto.precio) *
                cantidad;

            subtotal += subtotalProducto;

            carritoParaEnviar.push({
                id: id,
                cantidad: cantidad
            });

            html +=
                '<tr>' +
                    '<td>' +
                        this.escapar_html(
                            producto.nombre
                        ) +
                    '</td>' +

                    '<td>' +
                        '<div class="cantidad-control">' +

                            '<button ' +
                                'type="button" ' +
                                'class="btn-cantidad" ' +
                                'data-accion="restar" ' +
                                'data-producto-id="' +
                                id +
                                '">' +
                                '−' +
                            '</button>' +

                            '<span>' +
                                cantidad +
                            '</span>' +

                            '<button ' +
                                'type="button" ' +
                                'class="btn-cantidad" ' +
                                'data-accion="sumar" ' +
                                'data-producto-id="' +
                                id +
                                '">' +
                                '+' +
                            '</button>' +

                        '</div>' +
                    '</td>' +

                    '<td>' +
                        'S/ ' +
                        Number(
                            producto.precio
                        ).toFixed(2) +
                    '</td>' +

                    '<td>' +
                        'S/ ' +
                        subtotalProducto.toFixed(2) +
                    '</td>' +

                    '<td>' +
                        '<button ' +
                            'type="button" ' +
                            'class="btn-eliminar" ' +
                            'data-accion="eliminar" ' +
                            'data-producto-id="' +
                            id +
                            '">' +
                            'Eliminar' +
                        '</button>' +
                    '</td>' +
                '</tr>';
        }, this);

        if (html === "") {
            html =
                '<tr>' +
                    '<td ' +
                        'colspan="5" ' +
                        'class="tabla-vacia"' +
                    '>' +
                        'Aún no agregaste productos.' +
                    '</td>' +
                '</tr>';
        }

        detalle.innerHTML = html;

        var igv = subtotal * 0.18;
        var total = subtotal + igv;

        this.actualizar_texto(
            "subtotalDetalle",
            "S/ " + subtotal.toFixed(2)
        );

        this.actualizar_texto(
            "resumenSubtotal",
            "S/ " + subtotal.toFixed(2)
        );

        this.actualizar_texto(
            "resumenIgv",
            "S/ " + igv.toFixed(2)
        );

        this.actualizar_texto(
            "resumenTotal",
            "S/ " + total.toFixed(2)
        );

        var carritoJson =
            this.get_elemento(
                "carritoJson"
            );

        if (carritoJson) {
            carritoJson.value =
                JSON.stringify(
                    carritoParaEnviar
                );
        }

        var btnRegistrar =
            this.get_elemento(
                "btnRegistrarPedido"
            );

        if (btnRegistrar) {
            btnRegistrar.disabled =
                carritoParaEnviar.length === 0;
        }

        Object.keys(
            this.productos
        ).forEach(function (clave) {
            var id = Number(clave);

            var disponible =
                Number(
                    this.productos[id].stock
                ) -
                this.cantidad_en_carrito(id);

            var fila =
                this.get_elemento(
                    "filaProducto_" + id
                );

            var boton =
                this.get_elemento(
                    "btnAgregar_" + id
                );

            if (
                fila &&
                fila.cells &&
                fila.cells[4]
            ) {
                fila.cells[4].textContent =
                    String(
                        Math.max(
                            disponible,
                            0
                        )
                    );
            }

            if (boton) {
                boton.disabled =
                    disponible <= 0;
            }
        }, this);

        this.guardar_datos_temporales();
    };

    this.filtrar_productos = function () {
        var buscador =
            this.get_elemento(
                "buscarProducto"
            );

        var filtro =
            this.get_elemento(
                "filtroCategoria"
            );

        var tabla =
            this.get_elemento(
                "tablaProductos"
            );

        if (
            !buscador ||
            !filtro ||
            !tabla
        ) {
            return;
        }

        var texto =
            buscador.value
                .toLowerCase()
                .trim();

        var categoria =
            filtro.value
                .toLowerCase()
                .trim();

        tabla
            .querySelectorAll("tbody tr")
            .forEach(function (fila) {
                var contenido =
                    fila.textContent
                        .toLowerCase();

                var categoriaFila =
                    String(
                        fila.dataset.categoria ||
                        ""
                    ).toLowerCase();

                fila.style.display =
                    contenido.includes(texto) &&
                    (
                        categoria === "" ||
                        categoriaFila === categoria
                    )
                        ? ""
                        : "none";
            });
    };

    this.validar_envio = function (evento) {
        var nombre =
            this.get_valor(
                "clienteNombre"
            ).trim();

        var dni =
            this.get_valor(
                "clienteDocumento"
            ).trim();

        var telefono =
            this.get_valor(
                "clienteTelefono"
            ).trim();

        var direccion =
            this.get_valor(
                "clienteDireccion"
            ).trim();

        if (
            Object.keys(
                this.carrito
            ).length === 0
        ) {
            evento.preventDefault();

            alert(
                "Agrega al menos un producto."
            );

            return;
        }

        if (nombre === "") {
            evento.preventDefault();

            alert(
                "Ingresa el nombre del cliente."
            );

            this.get_elemento(
                "clienteNombre"
            ).focus();

            return;
        }

        if (
            !/^[0-9]{8}$/.test(dni)
        ) {
            evento.preventDefault();

            alert(
                "El DNI debe tener exactamente 8 dígitos."
            );

            this.get_elemento(
                "clienteDocumento"
            ).focus();

            return;
        }

        if (
            !/^9[0-9]{8}$/.test(
                telefono
            )
        ) {
            evento.preventDefault();

            alert(
                "El teléfono debe comenzar en 9 " +
                "y tener exactamente 9 dígitos."
            );

            this.get_elemento(
                "clienteTelefono"
            ).focus();

            return;
        }

        if (direccion === "") {
            evento.preventDefault();

            alert(
                "Ingresa la dirección del cliente."
            );

            this.get_elemento(
                "clienteDireccion"
            ).focus();

            return;
        }

        this.renderizar();

        if (
            !confirm(
                "¿Registrar este pedido?"
            )
        ) {
            evento.preventDefault();
        }
    };

    this.preparar_eventos = function () {
        var _this = this;

        var tablaProductos =
            this.get_elemento(
                "tablaProductos"
            );

        var detalleCarrito =
            this.get_elemento(
                "detalleCarrito"
            );

        var buscador =
            this.get_elemento(
                "buscarProducto"
            );

        var filtro =
            this.get_elemento(
                "filtroCategoria"
            );

        var btnActualizar =
            this.get_elemento(
                "btnActualizarProductos"
            );

        var btnCancelar =
            this.get_elemento(
                "btnCancelarPedido"
            );

        var formulario =
            this.get_elemento(
                "formRegistrarPedido"
            );

        if (tablaProductos) {
            tablaProductos.addEventListener(
                "click",
                function (evento) {
                    var boton =
                        evento.target.closest(
                            "[data-producto-id]"
                        );

                    if (
                        !boton ||
                        !tablaProductos.contains(
                            boton
                        )
                    ) {
                        return;
                    }

                    _this.agregar_producto(
                        boton.getAttribute(
                            "data-producto-id"
                        )
                    );
                }
            );
        }

        if (detalleCarrito) {
            detalleCarrito.addEventListener(
                "click",
                function (evento) {
                    var boton =
                        evento.target.closest(
                            "[data-accion]"
                        );

                    if (
                        !boton ||
                        !detalleCarrito.contains(
                            boton
                        )
                    ) {
                        return;
                    }

                    var id =
                        boton.getAttribute(
                            "data-producto-id"
                        );

                    var accion =
                        boton.getAttribute(
                            "data-accion"
                        );

                    if (accion === "restar") {
                        _this.cambiar_cantidad(
                            id,
                            -1
                        );
                    } else if (
                        accion === "sumar"
                    ) {
                        _this.cambiar_cantidad(
                            id,
                            1
                        );
                    } else if (
                        accion === "eliminar"
                    ) {
                        _this.eliminar_producto(
                            id
                        );
                    }
                }
            );
        }

        if (buscador) {
            buscador.addEventListener(
                "input",
                function () {
                    _this.filtrar_productos();
                }
            );
        }

        if (filtro) {
            filtro.addEventListener(
                "change",
                function () {
                    _this.filtrar_productos();
                }
            );
        }

        if (btnActualizar) {
            btnActualizar.addEventListener(
                "click",
                function () {
                    window.location.reload();
                }
            );
        }

        if (btnCancelar) {
            btnCancelar.addEventListener(
                "click",
                function () {
                    _this.cancelar_pedido();
                }
            );
        }

        [
            "clienteNombre",
            "clienteDocumento",
            "clienteTelefono",
            "clienteDireccion",
            "observaciones"
        ].forEach(function (id) {
            var campo =
                _this.get_elemento(id);

            if (campo) {
                campo.addEventListener(
                    "input",
                    function () {
                        _this
                            .guardar_datos_temporales();
                    }
                );
            }
        });

        var campoDni =
            this.get_elemento(
                "clienteDocumento"
            );

        if (campoDni) {
            campoDni.addEventListener(
                "input",
                function () {
                    this.value =
                        this.value
                            .replace(/\D/g, "")
                            .slice(0, 8);
                }
            );
        }

        var campoTelefono =
            this.get_elemento(
                "clienteTelefono"
            );

        if (campoTelefono) {
            campoTelefono.addEventListener(
                "input",
                function () {
                    this.value =
                        this.value
                            .replace(/\D/g, "")
                            .slice(0, 9);
                }
            );
        }

        if (formulario) {
            formulario.addEventListener(
                "submit",
                function (evento) {
                    _this.validar_envio(
                        evento
                    );
                }
            );
        }
    };

    this.after_init = function () {
        var elemento =
            this.get_ui_elem(
                "container"
            );

        if (elemento) {
            this.set_container(elemento);
        }

        this.cargar_productos();
        this.cargar_carrito();
        this.preparar_eventos();
        this.restaurar_cliente();
        this.renderizar();
    };
}

mw_modules_pharmacy_orders_ui.prototype =
    new mw_ui();