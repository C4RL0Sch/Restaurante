$(document).ready(function () {
    $("#formUsuario").validate({
        rules: {
            id: {
                required: true,
                digits: true
            },
            nombre: {
                required: true
            },
            usuario: {
                required: true
            },
            contra: {
                required: true
            }
        },
        messages: {
            id: {
                required: "Es necesario un ID numérico",
                digits: "El ID debe ser un número"
            },
            nombre: {
                required: "El nombre completo del usuario es obligatorio"
            },
            usuario: {
                required: "El nombre único de usuario es obligatorio"
            },
            contra: {
                required: "La contraseña del usuario es obligatoria"
            }
        },
        submitHandler: function (form) {
            guardarUsuario();
        }
    });

});

function abrirModal(rol) {
    $("#modal-title").text("Registrar Usuario");
    $("#formUsuario")[0].reset();
    $("#usuario-id").removeAttr('readonly');
    $("#usuario-rol").val(rol);
    $("#password-group").show();
    new bootstrap.Modal(document.getElementById("modalUsuario")).show();
}

$(".btn-editar").each(function () {
    const btn = $(this);
    btn.on("click", () => {
        $("#modal-title").text("Editar Usuario");
        $("#usuario-id").attr('readonly', 'true').val(btn.data("id"));
        $("#usuario-nombre").val(btn.data("nombre"));
        $("#usuario-usuario").val(btn.data("usuario"));
        $("#usuario-rol").val(btn.data("rol"));
        $("#password-group").hide();
        new bootstrap.Modal(document.getElementById("modalUsuario")).show();
    });
});

function guardarUsuario() {
    const id = $("#usuario-id").val().trim();
    const nombre = $("#usuario-nombre").val().trim();
    const usuario = $("#usuario-usuario").val().trim();
    const contra = $("#usuario-contra").val().trim();

    const datos = {
        id: id,
        nombre: nombre,
        usuario: usuario,
        contra: contra,
        rol: $("#usuario-rol").val().trim()
    }
    const archivo = (document.getElementById("usuario-id").hasAttribute('readonly')) ? "usuarios/editar_usuario.php" : "usuarios/guardar_usuario.php";

    console.log(archivo);

    $.post(archivo, datos, function (respuesta) {
        if (respuesta.includes('El ID ya esta ocupado')) {
            $("#usuario-id").focus();
            $("#formUsuario").validate().showErrors({
                id: respuesta
            });
        } else if (!respuesta.includes("correctamente")) {
            alert(respuesta);
        } else {
            const modal = bootstrap.Modal.getInstance(document.getElementById("modalUsuario"));
            modal.hide();
            cargarVista("usuarios.php");
        }
    });
}

$(".btn-eliminar").each(function () {
    const btn = $(this);
    btn.on("click", () => {
        const id = btn.data("id");
        if (!confirm("¿Estás seguro de eliminar este usuario?")) return;

        $.post("usuarios/eliminar_usuario.php", { id }, function (respuesta) {
            if (!respuesta.includes("eliminado")) {
                alert(respuesta);
            } else {
                cargarVista("usuarios.php");
            }
        });
    });
});