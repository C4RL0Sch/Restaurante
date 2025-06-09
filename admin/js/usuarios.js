window.abrirModal = function (rol) {
    document.getElementById("modal-title").innerText = "Registrar Usuario";
    document.getElementById("formUsuario").reset();
    document.getElementById("usuario-id").removeAttribute('disabled');
    document.getElementById("usuario-id").value = "";
    document.getElementById("usuario-rol").value = rol;
    document.getElementById("password-group").style.display = "block";
    new bootstrap.Modal(document.getElementById("modalUsuario")).show();
};

function filtrarTabla(input, idTabla) {
    const filtro = input.value.toLowerCase();
    const filas = document.querySelectorAll(`#${idTabla} tbody tr`);

    filas.forEach(fila => {
        const texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
    });
}

document.querySelector("#usuario-id").addEventListener('keypress', function (e) {
    const tecla = e.key;
    const permitido = "0123456789";

    if (!permitido.includes(tecla) && !e.ctrlKey) {
        e.preventDefault();
    }
});

document.querySelectorAll(".btn-editar").forEach(btn => {
    btn.addEventListener("click", () => {
        document.getElementById("modal-title").innerText = "Editar Usuario";
        document.getElementById("usuario-id").setAttribute('disabled', 'true');
        document.getElementById("usuario-id").value = btn.dataset.id;
        document.getElementById("usuario-nombre").value = btn.dataset.nombre;
        document.getElementById("usuario-usuario").value = btn.dataset.usuario;
        document.getElementById("usuario-rol").value = btn.dataset.rol;
        document.getElementById("password-group").style.display = "none";
        new bootstrap.Modal(document.getElementById("modalUsuario")).show();
    });
});

document.getElementById("formUsuario")?.addEventListener("submit", function (e) {
    e.preventDefault();

    let error1 = document.querySelector("#error-id");
    let error2 = document.querySelector("#error-nombre");
    let error3 = document.querySelector("#error-usuario");
    let error4 = document.querySelector("#error-contra");


    if ((document.querySelector("#usuario-id").value).trim() === "") {
        error1.innerText = "Es necesario un ID numerico"
        error1.classList.remove("d-none");
    }
    else {
        error1.classList.add("d-none");
    }

    if ((document.querySelector("#usuario-nombre").value).trim() === "") {
        error2.innerText = "El nombre completo del usuario es obligatorio"
        error2.classList.remove("d-none");
    }
    else {
        error2.classList.add("d-none");
    }

    if ((document.querySelector("#usuario-usuario").value).trim() === "") {
        error3.innerText = "La nombre unico de usuario el obligatorio"
        error3.classList.remove("d-none");
    }
    else {
        error3.classList.add("d-none");
    }

    if ((document.querySelector("#usuario-contra").value).trim() === "") {
        error4.innerText = "La contraseña del usuario es obligatorio"
        error4.classList.remove("d-none");
    }
    else {
        error4.classList.add("d-none");
    }

    if (!error1.classList.contains("d-none") || !error2.classList.contains("d-none") || !error3.classList.contains("d-none")
        || !error4.classList.contains("d-none")) {
        return
    }

    const datos = new FormData(this);
    const archivo = (document.getElementById("usuario-id").hasAttribute('disabled')) ? "usuarios/editar_usuario.php" : "usuarios/guardar_usuario.php";

    console.log(archivo);

    fetch(archivo, {
        method: "POST",
        body: datos,
    })
        .then(res => res.text())
        .then(respuesta => {
            if (respuesta.includes('El ID ya esta ocupado')) {
                error1.innerText = respuesta;
                error1.classList.remove("d-none");
            }
            else if (!respuesta.includes("correctamente")) {
                alert(respuesta);
            } else {
                bootstrap.Modal.getInstance(document.getElementById("modalUsuario")).hide();
                cargarVista("usuarios.php");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error al guardar el usuario.");
        });
});

document.querySelectorAll(".btn-eliminar").forEach(btn => {
    btn.addEventListener("click", () => {
        const id = btn.dataset.id;
        if (!confirm("¿Estás seguro de eliminar este usuario?")) return;

        fetch("usuarios/eliminar_usuario.php", {
            method: "POST",
            body: new URLSearchParams({ id })
        })
            .then(res => res.text())
            .then(respuesta => {
                if (!respuesta.includes("eliminado")) {
                    alert(respuesta);
                } else {
                    cargarVista("usuarios.php");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Error al eliminar el usuario.");
            });
    });
});