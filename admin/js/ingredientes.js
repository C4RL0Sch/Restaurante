$(document).ready(function () {

  $("#btnNuevoIngrediente").click(function () {
    $.get("proveedores/validar_proveedores.php", function (respuesta) {
      if (respuesta === "OK") {
        const modal = new bootstrap.Modal(document.getElementById("modalIngrediente"));
        modal.show();
      } else if (respuesta === "NO_PROVEEDORES") {
        alert("No puedes registrar ingredientes sin tener al menos un proveedor.");
      } else {
        alert("Acceso denegado o error en la validación.");
      }
    });
  });

  $("#formIngrediente").validate({
    rules: {
      id: {
        required: true,
      },
      nombre: {
        required: true,
      },
      medida: {
        required: true
      },
      cantidad: {
        required: true
      },
      costo: {
        required: true
      },
      idProveedor: {
        required: true
      }
    },
    messages: {
      id: {
        required: "Debe introducir un ID numerico",
      },
      nombre: {
        required: "Debe introducir un nombre para el ingrediente",
      },
      medida: {
        required: "Debe seleccionar una medida para el ingrediente"
      },
      cantidad: {
        required: "Debe indicar la cantidad del ingrediente"
      },
      costo: {
        required: "Debe indicar el costo del ingrediente"
      },
      idProveedor: {
        required: "Debe seleccionar un proveedor para el ingrediente"
      }
    },
    submitHandler: function (form) {
      guardarIngrediente();
    }
  });

  $("#formEditarIngrediente").validate({
    rules: {
      id: {
        required: true,
      },
      nombre: {
        required: true,
      },
      medida: {
        required: true
      },
      cantidad: {
        required: true
      },
      costo: {
        required: true
      },
      idProveedor: {
        required: true
      }
    },
    messages: {
      id: {
        required: "Debe introducir un ID numerico",
      },
      nombre: {
        required: "Debe introducir un nombre para el ingrediente",
      },
      medida: {
        required: "Debe seleccionar una medida para el ingrediente"
      },
      cantidad: {
        required: "Debe indicar la cantidad del ingrediente"
      },
      costo: {
        required: "Debe indicar el costo del ingrediente"
      },
      idProveedor: {
        required: "Debe seleccionar un proveedor para el ingrediente"
      }
    },
    submitHandler: function (form) {
      editarIngrediente();
    }
  });
});

function guardarIngrediente() {

  const id = $("#id").val().trim();
  const nombre = $("#nombre").val().trim();
  const medida = $("#medida").val().trim();
  const cantidad = $("#cantidad").val().trim();
  const costo = $("#costo").val().trim();
  const idProveedor = $("#idProveedor").val().trim();

  const datos = {
    id: id,
    nombre: nombre,
    medida: medida,
    cantidad: cantidad,
    costo: costo,
    idProveedor: idProveedor
  }

  $.post("ingredientes/guardar_ingrediente.php", datos, function (respuesta) {
    if (respuesta.includes('El ID ya esta ocupado')) {
      $("#id").focus();
      $("#formIngrediente").validate().showErrors({
        id: respuesta
      });
    }
    else if (!respuesta.includes('registrado')) {
      alert(respuesta);
    }
    else {
      const modal = bootstrap.Modal.getInstance(document.getElementById("modalIngrediente"));
      modal.hide();
      cargarVista("ingredientes.php");
    }
  });
}

$(".btn-editar").each(function () {
  $(this).click(function () {
    $("#editar_id").val($(this).data("id"));
    $("#editar_nombre").val($(this).data("nombre"));
    $("#editar_costo").val($(this).data("costo"));
    $("#editar_proveedor").val($(this).data("idproveedor"));
    $("#editar_cantidad").val($(this).data("cantidad"));
    $("#editar_medida").val($(this).data("medida"));
    new bootstrap.Modal(document.getElementById("modalEditar")).show();
  });
});

function editarIngrediente() {
  const id = $("#editar_id").val().trim();
  const nombre = $("#editar_nombre").val().trim();
  const medida = $("#editar_medida").val().trim();
  const cantidad = $("#editar_cantidad").val().trim();
  const costo = $("#editar_costo").val().trim();
  const idProveedor = $("#editar_proveedor").val().trim();

  const datos = {
    id: id,
    nombre: nombre,
    medida: medida,
    cantidad: cantidad,
    costo: costo,
    idProveedor: idProveedor
  }

  $.post("ingredientes/editar_ingrediente.php", datos, function (res) {
    if (!res.includes('actualizado')) {
      alert(res);
    }
    else {
      const modal = bootstrap.Modal.getInstance(document.getElementById("modalEditar"));
      modal.hide();
      cargarVista("ingredientes.php");
    }
  });
}

$(".btn-eliminar").each(function () {
  $(this).click(function () {
    const id = $(this).data("id");
    if (confirm("¿Seguro que deseas eliminar este ingrediente?")) {
      $.post("ingredientes/eliminar_ingrediente.php", { id }, function (res) {
        if (!res.includes('eliminado')) {
          alert(res);
        }
        cargarVista("ingredientes.php");
      });
    }
  });
});