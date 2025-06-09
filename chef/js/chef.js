function cargarVista(pagina) {
  fetch(pagina)
    .then(res => res.text())
    .then(html => {
      const contenido = document.getElementById("contenido");
      contenido.innerHTML = html;

      const contenedorScripts = document.getElementById("scripts");
      contenedorScripts.innerHTML = "";

      const tempDiv = document.createElement("div");
      tempDiv.innerHTML = html;
      const scripts = tempDiv.querySelectorAll("script[src]");

      const yaCargados = new Set(
        Array.from(document.querySelectorAll("script[src]")).map(s => s.src)
      );

      scripts.forEach(script => {
        const src = script.getAttribute("src");
        if (!yaCargados.has(src)) {
          const nuevoScript = document.createElement("script");
          nuevoScript.src = src;
          nuevoScript.defer = true;
          contenedorScripts.appendChild(nuevoScript);
        }
      });
    })
    .catch(err => {
      document.getElementById("contenido").innerHTML = "<div class='alert alert-danger'>Error al cargar la vista.</div>";
      console.error(err);
    });
}