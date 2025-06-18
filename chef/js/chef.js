function cargarVista(pagina) {
  $.get(pagina, function(html){
    const contenido = $("#contenido");
    contenido.html(html);
  });
}

function filtrarTabla(input, idTabla) {
  const filtro = input.value.toLowerCase();
  const filas = $(`#${idTabla} tbody tr`);

  filas.each(function () {
    const texto = $(this).text().toLowerCase();
    $(this).toggle(texto.includes(filtro));
  });
}