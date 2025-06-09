
function validar(){
    let usuario = document.getElementById("usuario").value

    let contra = document.getElementById("password").value

    //console.log(`usuario: ${usuario} contraseña: ${contra}`)

    if(usuario==""){
        document.getElementById("error").innerHTML = "El campo usuario no puede estar vacío"
        document.getElementById("error").classList.remove("d-none")
        return false
    }

    if(contra==""){
        document.getElementById("error").innerHTML = "El campo contraseña no puede estar vacío"
        document.getElementById("error").classList.remove("d-none")
        return false
    }

    document.getElementById("error").classList.add("d-none")
    iniciarSesion(usuario, contra)
}

function iniciarSesion(user, password){
    formData = new FormData()
    formData.append("usuario", user)
    formData.append("password", password)

    fetch("login.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(respuesta => {
        if (respuesta.startsWith("ERROR:")) {
            document.getElementById("error").innerHTML = respuesta.replace("ERROR:", "").trim();
            document.getElementById("error").classList.remove("d-none");
        } else {
            console.log(respuesta)
            window.location.href = respuesta;
        }
    })
    .catch(err => {
        document.getElementById("error").innerHTML ="Ocurrió un error";
        console.error(err);
    });
}