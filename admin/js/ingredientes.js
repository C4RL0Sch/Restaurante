
function filtrarTabla(input, idTabla) {
  const filtro = input.value.toLowerCase();
  const filas = document.querySelectorAll(`#${idTabla} tbody tr`);

  filas.forEach(fila => {
    const texto = fila.innerText.toLowerCase();
    fila.style.display = texto.includes(filtro) ? '' : 'none';
  });
}

function aplicarValidacionCantidad(medidaSelectId, cantidadInputId) {
  const medidaSelect = document.getElementById(medidaSelectId);
  const cantidadInput = document.getElementById(cantidadInputId);
  let valorAnterior = "";

  if (!medidaSelect || !cantidadInput) return;

  function getMedidaActual() {
    return medidaSelect.value;
  }

  function validarInput(e) {
    const medida = getMedidaActual();
    const valor = e.target.value;

    let regex;
    if (medida === "Litros" || medida === "Kilogramos") {
      regex = /^\d{0,5}(\.\d{0,3})?$/;
    } else {
      regex = /^\d{0,5}$/;
    }

    if (regex.test(valor)) {
      valorAnterior = valor;
    } else {
      e.target.value = valorAnterior;
    }
  }

  function bloquearCaracteresInvalidos(e) {
    const medida = getMedidaActual();
    const tecla = e.key;
    const valor = e.target.value;

    const permitido = "0123456789";
    const esPunto = tecla === ".";

    if (medida === "Litros" || medida === "Kilogramos") {
      if (esPunto && valor.includes(".")) e.preventDefault();
      else if (!permitido.includes(tecla) && !esPunto && !e.ctrlKey) e.preventDefault();
    } else {
      if (!permitido.includes(tecla) && !e.ctrlKey) e.preventDefault();
    }
  }

  medidaSelect.addEventListener("change", () => {
    cantidadInput.value = "";
    valorAnterior = "";
  });

  cantidadInput.addEventListener("keypress", bloquearCaracteresInvalidos);
  cantidadInput.addEventListener("input", validarInput);

  medidaSelect.dispatchEvent(new Event("change"));
}

aplicarValidacionCantidad("medida", "cantidad");
aplicarValidacionCantidad("editar-medida", "editar-cantidad");

function aplicarValidacionCosto(costoInputId) {
  const costoInput = document.getElementById(costoInputId);
  let valorAnterior = "";

  if (!costoInput) return;

  function validarCosto(e) {
    const valor = e.target.value;
    const regex = /^\d{0,5}(\.\d{0,2})?$/;

    if (regex.test(valor)) {
      valorAnterior = valor;
    } else {
      e.target.value = valorAnterior;
    }
  }

  function bloquearCaracteresInvalidos(e) {
    const tecla = e.key;
    const valor = e.target.value;
    const permitido = "0123456789";
    const esPunto = tecla === ".";

    if (esPunto && valor.includes(".")) {
      e.preventDefault();
    } else if (!permitido.includes(tecla) && !esPunto && !e.ctrlKey) {
      e.preventDefault();
    }
  }

  costoInput.addEventListener("keypress", bloquearCaracteresInvalidos);
  costoInput.addEventListener("input", validarCosto);
}

aplicarValidacionCosto("costo");
aplicarValidacionCosto("editar-costo");

document.getElementById("btnNuevoIngrediente").addEventListener("click", () => {
  fetch("proveedores/validar_proveedores.php")
    .then(res => res.text())
    .then(respuesta => {
      console.log(respuesta)
      if (respuesta === "OK") {
        const modal = new bootstrap.Modal(document.getElementById("modalIngrediente"));
        modal.show();
      } else if (respuesta === "NO_PROVEEDORES") {
        alert("No puedes registrar ingredientes sin tener al menos un proveedor.");
      } else {
        alert("Acceso denegado o error en la validación.");
      }
    })
    .catch(err => {
      console.error(err);
      alert("Error al validar proveedores.");
    });
});


document.querySelector("#id").addEventListener('keypress', function (e){
    const tecla = e.key;
    const permitido = "0123456789";

    if (!permitido.includes(tecla) && !e.ctrlKey) {
      e.preventDefault();
    }
});

document.getElementById("formIngrediente")?.addEventListener("submit", function (e) {
  e.preventDefault();

  let error1 = document.querySelector("#error-nombre");
  let error2 = document.querySelector("#error-medida");
  let error3 = document.querySelector("#error-cantidad");
  let error4 = document.querySelector("#error-costo");
  let error5 = document.querySelector("#error-idProveedor");
  let error6 = document.querySelector("#error-id");


  if((document.querySelector("#id").value).trim()===""){
        error6.innerText = "Es necesario un ID numerico"
        error6.classList.remove("d-none");
    }
    else{
        error6.classList.add("d-none");
    }

  if ((document.querySelector("#nombre").value).trim() === "") {
    error1.innerText = "El nombre del ingrediente es obligatorio"
    error1.classList.remove("d-none");
  }
  else {
    error1.classList.add("d-none");
  }

  if ((document.querySelector("#medida").value).trim() === "") {
    error2.innerText = "La medida del ingrediente es obligatorio"
    error2.classList.remove("d-none");
  }
  else {
    error2.classList.add("d-none");
  }

  if ((document.querySelector("#cantidad").value).trim() === "") {
    error3.innerText = "Debe ingresar una cantidad para el ingrediente"
    error3.classList.remove("d-none");
  }
  else {
    error3.classList.add("d-none");
  }

  if ((document.querySelector("#costo").value).trim() === "") {
    error4.innerText = "Debe ingresar un costo para el ingrediente"
    error4.classList.remove("d-none");
  }
  else {
    error4.classList.add("d-none");
  }

  if ((document.querySelector("#idProveedor").value).trim() === "") {
    error5.innerText = "Debe seleccionar un proveedor valido para el ingrediente"
    error5.classList.remove("d-none");
  }
  else {
    error5.classList.add("d-none");
  }

  if (!error1.classList.contains("d-none") || !error2.classList.contains("d-none") || !error3.classList.contains("d-none") 
        || !error4.classList.contains("d-none") || !error5.classList.contains("d-none") || !error6.classList.contains("d-none")) {
    return
  }

  const datos = new FormData(this);

  fetch("ingredientes/guardar_ingrediente.php", {
    method: "POST",
    body: datos
  })
    .then(res => res.text())
    .then(res => {
      if(res.includes('El ID ya esta ocupado')){
          error6.innerText = res
          error6.classList.remove("d-none");
      }
      else if (!res.includes('registrado')) {
        error6.classList.add("d-none");
        alert(res);
      }
      else{
        const modal = bootstrap.Modal.getInstance(document.getElementById("modalIngrediente"));
        modal.hide();
        cargarVista("ingredientes.php");
      }
    });
});

document.querySelectorAll(".btn-editar").forEach(btn => {
  btn.addEventListener("click", () => {
    document.getElementById("editar-id").value = btn.dataset.id;
    document.getElementById("editar-nombre").value = btn.dataset.nombre;
    document.getElementById("editar-costo").value = btn.dataset.costo;

    document.getElementById("editar-proveedor").value = btn.dataset.idproveedor;

    let medida = btn.dataset.medida;
    let cantidad = btn.dataset.cantidad;

    cantidad = cantidad.replace(/[^\d.]/g, "");

    if (medida === "Litros" || medida === "Kilogramos") {
      const match = cantidad.match(/^(\d{1,5})(\.(\d{1,3}))?/);
      if (match) {
        const enteros = match[1];
        const decimales = match[3] ?? "";
        cantidad = enteros + (decimales ? "." + decimales : "");
      } else {
        cantidad = "";
      }
    } else {
      cantidad = cantidad.replace(/\D/g, "").substring(0, 5);
    }

    document.getElementById("editar-cantidad").value = cantidad;
    document.getElementById("editar-medida").value = medida;

    new bootstrap.Modal(document.getElementById("modalEditar")).show();
  });
});

document.getElementById("formEditarIngrediente")?.addEventListener("submit", function (e) {
  e.preventDefault();

  let error1 = document.querySelector("#error-editar-nombre");
  let error2 = document.querySelector("#error-editar-medida");
  let error3 = document.querySelector("#error-editar-cantidad");
  let error4 = document.querySelector("#error-editar-costo");
  let error5 = document.querySelector("#error-editar-proveedor");

  if ((document.querySelector("#editar-nombre").value).trim() === "") {
    error1.innerText = "El nombre del ingrediente es obligatorio"
    error1.classList.remove("d-none");
  }
  else {
    error1.classList.add("d-none");
  }

  if ((document.querySelector("#editar-medida").value).trim() === "") {
    error2.innerText = "La medida del ingrediente es obligatorio"
    error2.classList.remove("d-none");
  }
  else {
    error2.classList.add("d-none");
  }

  if ((document.querySelector("#editar-cantidad").value).trim() === "") {
    error3.innerText = "Debe ingresar una cantidad para el ingrediente"
    error3.classList.remove("d-none");
  }
  else {
    error3.classList.add("d-none");
  }

  if ((document.querySelector("#editar-costo").value).trim() === "") {
    error4.innerText = "Debe ingresar un costo para el ingrediente"
    error4.classList.remove("d-none");
  }
  else {
    error4.classList.add("d-none");
  }

  if ((document.querySelector("#editar-proveedor").value).trim() === "") {
    error5.innerText = "Debe seleccionar un proveedor valido para el ingrediente"
    error5.classList.remove("d-none");
  }
  else {
    error5.classList.add("d-none");
  }

  if (!error1.classList.contains("d-none") || !error2.classList.contains("d-none") || !error3.classList.contains("d-none") 
        || !error4.classList.contains("d-none") || !error5.classList.contains("d-none")) {
    return
  }

  const datos = new FormData(this);

  fetch("ingredientes/editar_ingrediente.php", {
    method: "POST",
    body: datos
  })
    .then(res => res.text())
    .then(res => {
      if (!res.includes('actualizado')) {
        alert(res);
      }
      else{
        const modal = bootstrap.Modal.getInstance(document.getElementById("modalEditar"));
        modal.hide();
        cargarVista("ingredientes.php");
      }
    });
});

document.querySelectorAll(".btn-eliminar").forEach(btn => {
  btn.addEventListener("click", () => {
    const id = btn.dataset.id;
    if (confirm("¿Seguro que deseas eliminar este ingrediente?")) {
      fetch("ingredientes/eliminar_ingrediente.php", {
        method: "POST",
        body: new URLSearchParams({ id })
      })
        .then(res => res.text())
        .then(res => {
          if (!res.includes('eliminado')) {
            alert(res);
          }
          cargarVista("ingredientes.php");
        });
    }
  });
});