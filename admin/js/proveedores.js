$(document).ready(function () {
    $("#formProveedor").validate({
        rules: {
            id: {
                required: true,
                digits: true
            },
            nombre: {
                required: true
            },
            telefono: {
                required: true,
                digits: true
            },
            direccion: {
                required: true
            }
        },
        messages: {
            id: {
                required: "Por favor ingresa un ID",
                digits: "El ID debe ser un número"
            },
            nombre: {
                required: "Por favor ingresa el nombre del proveedor"
            },
            telefono: {
                required: "Por favor ingresa el teléfono del proveedor",
                digits: "El teléfono debe ser un número"
            },
            direccion: {
                required: "Por favor ingresa la dirección del proveedor"
            }
        },
        submitHandler: function (form) {
            guardarProveedor();
        }
    });

    $("#formEditarProveedor").validate({
        rules: {
            id: {
                required: true,
                digits: true
            },
            nombre: {
                required: true
            },
            telefono: {
                required: true,
                digits: true
            },
            direccion: {
                required: true
            }
        },
        messages: {
            id: {
                required: "Por favor ingresa un ID",
                digits: "El ID debe ser un número"
            },
            nombre: {
                required: "Por favor ingresa el nombre del proveedor"
            },
            telefono: {
                required: "Por favor ingresa el teléfono del proveedor",
                digits: "El teléfono debe ser un número"
            },
            direccion: {
                required: "Por favor ingresa la dirección del proveedor"
            }
        },
        submitHandler: function (form) {
            editarProveedor();
        }
    });
});

function guardarProveedor() {
    const id = $("#id").val().trim();
    const nombre = $("#nombre").val().trim();
    const telefono = $("#telefono").val().trim();
    const direccion = $("#direccion").val().trim();

    const datos = {
        id: id,
        nombre: nombre,
        telefono: telefono,
        direccion: direccion
    }

    $.post("proveedores/guardar_proveedor.php", datos, function (res) {
        if (res.includes('El ID ya esta ocupado')) {
            $("#id").focus();
            $("#formProveedor").validate().showErrors({
                id: res
            });
        }
        else if (!res.includes('correctamente')) {
            alert(res);
        }
        else {
            const modal = bootstrap.Modal.getInstance(document.getElementById("modalProveedor"));
            modal.hide();
            cargarVista("proveedores.php");
        }
    });
}

$(".btn-editar").each(function (){
    const btn = $(this);
    btn.on("click", () => {
        $("#editar-id").val(btn.data("id"));
        $("#editar-nombre").val(btn.data("nombre"));
        $("#editar-telefono").val(btn.data("telefono"));
        $("#editar-direccion").val(btn.data("direccion"));
        console.log("VALIDANDO DE NUEVOOOOO");
        const modal = new bootstrap.Modal(document.getElementById("modalEditar"));
        modal.show();
    });
});

function editarProveedor() {
    const id = $("#editar-id").val().trim();
    const nombre = $("#editar-nombre").val().trim();
    const telefono = $("#editar-telefono").val().trim();
    const direccion = $("#editar-direccion").val().trim();

    const datos = {
        id: id,
        nombre: nombre,
        telefono: telefono,
        direccion: direccion
    }

    $.post("proveedores/editar_proveedor.php", datos, function (res) {
        if (!res.includes('actualizado')) {
                alert(res);
            }
            bootstrap.Modal.getInstance(document.getElementById("modalEditar")).hide();
            cargarVista("proveedores.php");
    });
}

$(".btn-eliminar").each(function () {
    const btn = $(this);
    btn.on("click", () => {
        const id = btn.data("id");
        if (confirm("¿Estás seguro de eliminar este proveedor?")) {
            $.post("proveedores/eliminar_proveedor.php", { id }, function (res) {
                if (!res.includes('eliminado')) {
                    alert(res);
                }
                    cargarVista("proveedores.php");
                });
        }
    });
});