(() => {
    let proveedorSeleccionado = null;

    document.getElementById("btnNuevaOrden")?.addEventListener("click", () => {
        fetch("ingredientes/validar_ingredientes.php")
            .then(res => res.text())
            .then(respuesta => {
                if (respuesta === "OK") {
                    const modal = new bootstrap.Modal(document.getElementById("modalOrden"));
                    modal.show();
                } else {
                    alert("No puedes registrar órdenes porque no hay ingredientes registrados.");
                }
            })
            .catch(err => {
                console.error("Error al validar ingredientes:", err);
                alert("Ocurrió un error al intentar validar ingredientes.");
            });
    });

    document.getElementById("idProveedor").addEventListener("change", function () {
        proveedorSeleccionado = this.value;

        const container = document.getElementById("ingredientes-container");
        container.innerHTML = "";

        if (!proveedorSeleccionado) {
            document.getElementById("btnAgregarIngrediente").disabled = true;
        } else {
            document.getElementById("btnAgregarIngrediente").disabled = false;
        }
    });

    document.getElementById("btnAgregarIngrediente").addEventListener("click", function () {
        if (!proveedorSeleccionado) return;

        const form = document.getElementById('formOrdenCompra')
        form.classList.remove('was-validated');

        fetch(` compras/plantilla_ingredientes.php?idProveedor=${proveedorSeleccionado}`)
            .then(response => response.text())
            .then(html => {
                const contenedor = document.getElementById("ingredientes-container");
                const temp = document.createElement("div");
                temp.innerHTML = html;
                const grupo = temp.firstElementChild;

                contenedor.appendChild(grupo);

                aplicarValidacionesDinamicas(grupo);

                grupo.querySelector(".btnEliminarIngrediente").addEventListener("click", () => {
                    grupo.remove();
                });
            });
    });


    function aplicarValidacionesDinamicas(grupo) {
        const medidaSelect = grupo.querySelector(".medida-dinamica");
        const cantidadInput = grupo.querySelector(".cantidad-dinamica");
        const costoInput = grupo.querySelector(".costo-dinamico");

        let valorAnteriorCantidad = "";
        let valorAnteriorCosto = "";

        function getMedidaActual() {
            const opcionSeleccionada = medidaSelect.options[medidaSelect.selectedIndex];
            return opcionSeleccionada ? opcionSeleccionada.dataset.medida : "";
        }

        medidaSelect.addEventListener("change", () => {
            cantidadInput.value = "";
            valorAnteriorCantidad = "";
        });

        cantidadInput.addEventListener("keypress", (e) => {
            const medida = getMedidaActual();
            const tecla = e.key;
            const valor = e.target.value;
            const permitido = "0123456789";

            if (medida === "Kilogramos" || medida === "Litros") {
                if (tecla === "." && valor.includes(".")) e.preventDefault();
                else if (!permitido.includes(tecla) && tecla !== "." && !e.ctrlKey) e.preventDefault();
            } else {
                if (!permitido.includes(tecla) && !e.ctrlKey) e.preventDefault();
            }
        });

        cantidadInput.addEventListener("input", (e) => {
            const medida = getMedidaActual();
            const valor = e.target.value;
            const regex = (medida === "Kilogramos" || medida === "Litros")
                ? /^\d{0,5}(\.\d{0,3})?$/
                : /^\d{0,5}$/;

            if (regex.test(valor)) {
                valorAnteriorCantidad = valor;
            } else {
                e.target.value = valorAnteriorCantidad;
            }
        });

        costoInput.addEventListener("keypress", (e) => {
            const tecla = e.key;
            const valor = costoInput.value;
            const permitido = "0123456789";

            if (tecla === "." && valor.includes(".")) {
                e.preventDefault();
            } else if (!permitido.includes(tecla) && tecla !== "." && !e.ctrlKey) {
                e.preventDefault();
            }
        });

        costoInput.addEventListener("input", (e) => {
            const valor = e.target.value;
            const regex = /^\d{0,5}(\.\d{0,2})?$/;

            if (regex.test(valor)) {
                valorAnteriorCosto = valor;
            } else {
                e.target.value = valorAnteriorCosto;
            }
        });
    }


    document.addEventListener('click', e => {
        if (e.target.classList.contains('btnEliminarIngrediente')) {
            e.target.closest('.grupo-ingrediente').remove();
        }
    });

    document.getElementById('formOrdenCompra')?.addEventListener('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const form = document.getElementById('formOrdenCompra')

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const datos = new FormData(this);

        fetch('compras/guardar_orden.php', {
            method: 'POST',
            body: datos
        })
            .then(res => res.text())
            .then(res => {
                if (!res.includes("correctamente")) {
                    alert(res);
                }
                else {
                    const modal = new bootstrap.Modal(document.getElementById("modalOrden"));
                    modal.hide();
                    cargarVista('compras.php');
                }
            })
            .catch(err => {
                console.error(err);
                alert("Error al guardar la orden.");
            });
    });

    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-ver-detalle")) {
            const id = e.target.dataset.id;

            fetch("compras/obtener_detalles.php?id=" + id)
                .then(res => res.text())
                .then(html => {
                    document.getElementById("detalleContenido").innerHTML = html;
                    new bootstrap.Modal(document.getElementById("modalDetalleOrden")).show();
                });
        }
    });

    const buscador = document.getElementById("buscadorOrden");
    const filtroFecha = document.getElementById("filtroFecha");

    function filtrarTabla() {
        const texto = buscador.value.toLowerCase();
        const fecha = filtroFecha.value;

        document.querySelectorAll("table tbody tr").forEach(row => {
            const proveedor = row.children[2].textContent.toLowerCase();
            const fechaFila = row.children[1].textContent;

            const coincideTexto = proveedor.includes(texto);
            const coincideFecha = !fecha || fechaFila === fecha;

            row.style.display = coincideTexto && coincideFecha ? "" : "none";
        });
    }

    buscador.addEventListener("input", filtrarTabla);
    filtroFecha.addEventListener("change", filtrarTabla);

    document.getElementById("btnAbrirModalReporte").addEventListener("click", () => {
        fetch("compras/validar_compras.php")
            .then(res => res.text())
            .then(resp => {
                console.log(resp);
                if (resp.trim() === "1") {
                    const modal = new bootstrap.Modal(document.getElementById("modalReportePDF"));
                    modal.show();
                } else {
                    alert("⚠️ No se han registrado órdenes de compra aún.");
                }
            })
            .catch(err => {
                console.error("Error al verificar órdenes:", err);
                alert("Error al verificar si hay órdenes registradas.");
            });
    });

    document.getElementById("formReportePDF").addEventListener("submit", function (e) {
        const inicio = document.getElementById("fechaInicio").value;
        const fin = document.getElementById("fechaFin").value;

        if (!inicio || !fin) {
            e.preventDefault();
            alert("Debes seleccionar una fecha de inicio y una fecha de fin.");
            return;
        }

        if (inicio > fin) {
            e.preventDefault();
            alert("La fecha de inicio no puede ser mayor que la fecha final.");
            return;
        }
    });

})();