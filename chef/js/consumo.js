$(document).ready(function () {

    $("#formConsumo").submit(function(e) {
        e.preventDefault();
        e.stopPropagation();

        const datos = new FormData(e.target);

        const grupos = $(".grupo-consumo");
        if (grupos.length === 0) {
            e.preventDefault();
            alert("⚠️ Debes agregar al menos un ingrediente antes de guardar.");
            return;
        }

        $.ajax({
            url: 'consumo/guardar_consumo.php',
            method: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (msg) {
                alert(msg);
                let modal = bootstrap.Modal.getInstance(document.getElementById("modalConsumo"))
                modal.hide();
                cargarVista('consumo.php');
            },
            error: function () {
                alert("Error al guardar el consumo.");
            }
        });
    });

    $("#btnAgregarConsumo").click(function () {
        $.get('plantilla_consumo.php', function (html) {
            const cont = $("#consumo-container");
            const tmp = $("<div>");
            tmp.html(html);
            const grp = tmp.children().first();

            cont.append(grp);

            grp.find(".btn-eliminar-consumo").on("click", () => {
                grp.remove();
                reindexarCampos();
            });

            reindexarCampos();
            validarPlantillas();
        });
    });

    $(".btn-ver-consumo").each(function () {
        $(this).click(function () {
            const id = $(this).data("id");

            $.get(`consumo/obtener_detalle.php?id=${id}`, function (html) {
                $("#detalleConsumo").html(html);
                const modal = new bootstrap.Modal(document.getElementById("modalVerConsumo"));
                modal.show();
                console.log("MOSTRANDO");
            });
        });
    });

    $("#btnNuevoConsumo").click(function() {
        $("#consumo-container").html("");
        new bootstrap.Modal(document.getElementById("modalConsumo")).show();
    });

    function reindexarCampos() {
        $("#consumo-container .grupo-consumo").each(function (index) {
            $(this).find("[name^='ingredientes']").attr("name", `ingredientes[${index}]`);
            $(this).find("[name^='cantidades']").attr("name", `cantidades[${index}]`);
        });
    }

    function validarPlantillas() {
        $(".select-consumo").each(function () {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Debe seleccionar un ingrediente válido"
                }
            });
        });

        $(".input-cant").each(function () {
            $(this).rules("add", {
                required: true,
                number: true,
                messages: {
                    required: "Debe indicar la cantidad a comprar del ingrediente",
                    number: "Debe ser un número válido"
                }
            });
        });
    }
});