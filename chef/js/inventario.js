document.getElementById("buscadorInventario").addEventListener("input", () => {
    const filtro = document.getElementById("buscadorInventario").value.toLowerCase();
    document
      .querySelectorAll("#tablaInventario tbody tr")
      .forEach(row => {
        row.style.display = 
          row.innerText.toLowerCase().includes(filtro) ? "" : "none";
      });
  });