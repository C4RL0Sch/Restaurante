function filtrarTabla(input, idTabla) {
  const filtro = input.value.toLowerCase();
  const filas = document.querySelectorAll(`#${idTabla} tbody tr`);

  filas.forEach(fila => {
    const texto = fila.innerText.toLowerCase();
    fila.style.display = texto.includes(filtro) ? '' : 'none';
  });
}

//VALIDAR NUMEROS
document.querySelector("#telefono").addEventListener('keypress', function (e){
    const tecla = e.key;
    const permitido = "0123456789";

    if (!permitido.includes(tecla) && !e.ctrlKey) {
      e.preventDefault();
    }
});

document.querySelector("#editar-telefono").addEventListener('keypress', function (e){
    const tecla = e.key;
    const permitido = "0123456789";

    if (!permitido.includes(tecla) && !e.ctrlKey) {
      e.preventDefault();
    }
});

document.querySelector("#id").addEventListener('keypress', function (e){
    const tecla = e.key;
    const permitido = "0123456789";

    if (!permitido.includes(tecla) && !e.ctrlKey) {
      e.preventDefault();
    }
});

document.getElementById("formProveedor")?.addEventListener("submit", function (e) {
    e.preventDefault();
    let error1 = document.querySelector("#error-nombre");
    let error2 = document.querySelector("#error-telefono");
    let error3 = document.querySelector("#error-direccion");
    let error4 = document.querySelector("#error-id");

    if((document.querySelector("#id").value).trim()===""){
        error4.innerText = "Es necesario un ID numerico"
        error4.classList.remove("d-none");
    }
    else{
        error4.classList.add("d-none");
    }

    if((document.querySelector("#nombre").value).trim()===""){
        error1.innerText = "El nombre del proveedor es obligatorio"
        error1.classList.remove("d-none");
    }
    else{
        error1.classList.add("d-none");
    }

    if((document.querySelector("#telefono").value).trim()===""){
        error2.innerText = "El telefono del proveedor es obligatorio"
        error2.classList.remove("d-none");
    }
    else{
        error2.classList.add("d-none");
    }

    if((document.querySelector("#direccion").value).trim()===""){
        error3.innerText = "La direccion del proveedor es obligatorio"
        error3.classList.remove("d-none");
    }
    else{
        error3.classList.add("d-none");
    }

    if(!error1.classList.contains("d-none") || !error2.classList.contains("d-none") || !error3.classList.contains("d-none")
            || !error4.classList.contains("d-none")){
        return
    }

    const datos = new FormData(this);

    fetch("proveedores/guardar_proveedor.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.text())
        .then(res => {
            if(res.includes('El ID ya esta ocupado')){
                error4.innerText = res
                error4.classList.remove("d-none");
            }
            else if (!res.includes('correctamente')) {
                alert(res);
                error4.classList.add("d-none");
            }
            else{
                error4.classList.add("d-none");
                const modal = bootstrap.Modal.getInstance(document.getElementById("modalProveedor"));
                modal.hide();
                cargarVista("proveedores.php");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error al guardar el proveedor.");
        });
});

document.querySelectorAll(".btn-editar").forEach(btn => {
    btn.addEventListener("click", () => {
        document.getElementById("editar-id").value = btn.dataset.id;
        document.getElementById("editar-nombre").value = btn.dataset.nombre;
        document.getElementById("editar-telefono").value = btn.dataset.telefono;
        document.getElementById("editar-direccion").value = btn.dataset.direccion;

        const modal = new bootstrap.Modal(document.getElementById("modalEditar"));
        modal.show();
    });
});

document.getElementById("formEditarProveedor")?.addEventListener("submit", function (e) {
    e.preventDefault();

    let error1 = document.querySelector("#error-editar-nombre");
    let error2 = document.querySelector("#error-editar-telefono");
    let error3 = document.querySelector("#error-editar-direccion");

    if((document.querySelector("#editar-nombre").value).trim()===""){
        error1.innerText = "El nombre del proveedor es obligatorio"
        error1.classList.remove("d-none");
    }
    else{
        error1.classList.add("d-none");
    }

    if((document.querySelector("#editar-telefono").value).trim()===""){
        error2.innerText = "El telefono del proveedor es obligatorio"
        error2.classList.remove("d-none");
    }
    else{
        error2.classList.add("d-none");
    }

    if((document.querySelector("#editar-direccion").value).trim()===""){
        error3.innerText = "La direccion del proveedor es obligatorio"
        error3.classList.remove("d-none");
    }
    else{
        error3.classList.add("d-none");
    }

    if(!error1.classList.contains("d-none") || !error2.classList.contains("d-none") || !error3.classList.contains("d-none")){
        return
    }

    const datos = new FormData(this);

    fetch("proveedores/editar_proveedor.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.text())
        .then(res => {
            if (!res.includes('actualizado')) {
                alert(res);
            }
            bootstrap.Modal.getInstance(document.getElementById("modalEditar")).hide();
            cargarVista("proveedores.php");
        });
});

document.querySelectorAll(".btn-eliminar").forEach(btn => {
    btn.addEventListener("click", () => {
        const id = btn.dataset.id;
        if (confirm("¿Estás seguro de eliminar este proveedor?")) {
            fetch("proveedores/eliminar_proveedor.php", {
                method: "POST",
                body: new URLSearchParams({ id })
            })
            .then(res => res.text())
            .then(res => {
                if (!res.includes('eliminado')) {
                    alert(res);
                }
                cargarVista("proveedores.php");
            });
        }
    });
});