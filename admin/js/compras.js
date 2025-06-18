$(document).ready(function () {
    let proveedorSeleccionado = null;

    $("#formOrdenCompra").validate({
        rules: {
            idProveedor: {
                required: true
            },
            fecha: {
                required: true
            }
        },
        messages: {
            idProveedor: {
                required: "Se debe de seleccionar un proveedor valido"
            },
            fecha: {
                required: "Debe indicar la fecha de la compra"
            }
        },
        submitHandler: function (form) {
            guardarCompra();
        }
    });

    $("#btnNuevaOrden").on("click", () => {
        $.post("ingredientes/validar_ingredientes.php", function (respuesta) {
            if (respuesta === "OK") {
                const modal = new bootstrap.Modal(document.getElementById("modalOrden"));
                modal.show();
            } else {
                alert("No puedes registrar órdenes porque no hay ingredientes registrados.");
            }
        });
    });

    $("#idProveedor").on("change", function () {
        proveedorSeleccionado = $(this).val();

        const container = $("#ingredientes-container");
        container.empty();

        if (!proveedorSeleccionado) {
            $("#btnAgregarIngrediente").prop("disabled", true);
        } else {
            $("#btnAgregarIngrediente").prop("disabled", false);
        }
    });

    $("#btnAgregarIngrediente").on("click", function () {
        if (!proveedorSeleccionado) return;

        $.post(`compras/plantilla_ingredientes.php?idProveedor=${proveedorSeleccionado}`, function (html) {
            const contenedor = $("#ingredientes-container");
            const temp = $("<div>").html(html);
            const grupo = temp.children().first();

            contenedor.append(grupo);

            grupo.find(".btnEliminarIngrediente").on("click", () => {
                grupo.remove();
                reindexarCampos();
            });

            reindexarCampos();
            validarPlantillas();
        });
    });

    function validarPlantillas() {
        $(".medida-dinamica").each(function () {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Debe seleccionar un ingrediente válido"
                }
            });
        });

        $(".cantidad-dinamica").each(function () {
            $(this).rules("add", {
                required: true,
                number: true,
                messages: {
                    required: "Debe indicar la cantidad a comprar del ingrediente",
                    number: "Debe ser un número válido"
                }
            });
        });

        $(".costo-dinamico").each(function () {
            $(this).rules("add", {
                required: true,
                number: true,
                messages: {
                    required: "Debe indicar el costo del ingrediente",
                    number: "Debe ser un número válido"
                }
            });
        });
    }

    function guardarCompra() {
        const form = document.getElementById("formOrdenCompra");

        const datos = new FormData(form);

        $.ajax({
            url: 'compras/guardar_orden.php',
            method: 'POST',
            data: new FormData(document.getElementById("formOrdenCompra")),
            processData: false,
            contentType: false,
            success: function (res) {
                if (!res.includes("correctamente")) {
                    alert(res);
                } else {
                    const modal = bootstrap.Modal.getInstance(document.getElementById("modalOrden"));
                    modal.hide();
                    cargarVista('compras.php');
                }
            },
            error: function () {
                alert("Error al guardar la orden.");
            }
        });
    }

    $(".btn-ver-detalle").each(function () {
        $(this).click(function () {
            const id = $(this).data("id");

            $.get("compras/obtener_detalles.php?id=" + id, function (html) {
                $("#detalleContenido").html(html);
                const modal = new bootstrap.Modal(document.getElementById("modalDetalleOrden"));
                modal.show();
                console.log("MOSTRANDO");
            });
        });
    });

    const buscador = document.getElementById("buscadorOrden");
    const filtroFecha = document.getElementById("filtroFecha");

    function filtrarTablaCompras() {
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

    buscador.addEventListener("input", filtrarTablaCompras);
    filtroFecha.addEventListener("change", filtrarTablaCompras);

    $("#btnAbrirModalReporte").click(function () {
        $.get("compras/validar_compras.php", function (resp) {
            if (resp.trim() === "1") {
                const modal = new bootstrap.Modal(document.getElementById("modalReportePDF"));
                modal.show();
            } else {
                alert("⚠️ No se han registrado órdenes de compra aún.");
            }
        });
    });

    $("#formReportePDF").submit(function(e){
        const inicio = $("#fechaInicio").val();
        const fin = $("#fechaFin").val();

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

    function reindexarCampos() {
        $("#ingredientes-container .grupo-ingrediente").each(function (index) {
            $(this).find("[name^='ingredientes']").attr("name", `ingredientes[${index}]`);
            $(this).find("[name^='cantidades']").attr("name", `cantidades[${index}]`);
            $(this).find("[name^='costos']").attr("name", `costos[${index}]`);
        });
    }
});