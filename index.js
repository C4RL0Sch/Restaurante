$(document).ready(function () {

    $("#form-login").validate({
        rules: {
            usuario: {
                required: true
            },
            password: {
                required: true
            }
        },
        messages: {
            usuario: {
                required: "Debe ingresar un usuario",
            },
            password: {
                required: "Debe ingresar una contraseña"
            }
        },
        submitHandler: function () {
            iniciarSesion();
        }
    });

    function iniciarSesion() {
        let user = $("#usuario").val();
        let password = $("#password").val();

        const datos = {
            usuario: user,
            password: password
        };

        $.post("login.php", datos, function (respuesta) {
            if (respuesta.startsWith("ERROR:")) {
                $('#usuario').focus();
                $("#form-login").validate().showErrors({
                    usuario: respuesta.replace("ERROR:", "").trim()
                });
                return;
            } else {
                console.log(respuesta)
                window.location.href = respuesta;
            }
        });
    }
});