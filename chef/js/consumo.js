(() => {
    document.getElementById("btnAgregarConsumo").addEventListener("click", () => {
        const form = document.getElementById('formConsumo')
        form.classList.remove('was-validated');

        fetch('plantilla_consumo.php')
            .then(r => r.text())
            .then(html => {
                const cont = document.getElementById("consumo-container");
                const tmp = document.createElement("div");
                tmp.innerHTML = html;
                const grp = tmp.firstElementChild;
                cont.appendChild(grp);
                inicializarValidaciones(grp);
            });
    });

    document.addEventListener("click", e => {
        if (e.target.classList.contains("btn-eliminar-consumo")) {
            e.target.closest(".grupo-consumo").remove();
        }
        
        if (e.target.classList.contains("btn-ver-consumo")) {
            const id = e.target.dataset.id;
            fetch(`consumo/obtener_detalle.php?id=${id}`)
                .then(r => r.text())
                .then(html => {
                    document.getElementById("detalleConsumo").innerHTML = html;
                    new bootstrap.Modal(document.getElementById("modalVerConsumo")).show();
                });
        }
    });

    document.getElementById("formConsumo").addEventListener("submit", e => {
        e.preventDefault();
        e.stopPropagation();

        const form = document.getElementById('formConsumo')

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const datos = new FormData(e.target);

        const grupos = document.querySelectorAll(".grupo-consumo");
        if (grupos.length === 0) {
            e.preventDefault();
            alert("⚠️ Debes agregar al menos un ingrediente antes de guardar.");
            return;
        }

        fetch('consumo/guardar_consumo.php', { method: 'POST', body: datos })
            .then(r => r.text())
            .then(msg => {
                alert(msg);
                let modal = bootstrap.Modal.getInstance(document.getElementById("modalConsumo"))
                modal.hide();
                cargarVista('consumo.php');
            });
    });

    document.getElementById("btnNuevoConsumo").addEventListener("click", () => {
        document.getElementById("consumo-container").innerHTML = "";
        new bootstrap.Modal(document.getElementById("modalConsumo")).show();
    });

    function inicializarValidaciones(grupo) {
        const sel = grupo.querySelector(".select-consumo");
        const inp = grupo.querySelector(".input-cant");
        let prev = '';
        sel.addEventListener("change", () => { inp.value = ''; prev = ''; });
        inp.addEventListener("keypress", e => {
            const m = sel.options[sel.selectedIndex].dataset.medida;
            const t = e.key, v = inp.value, p = "0123456789";
            if (m === "Litros" || m === "Kilogramos") {
                if (t === "." && v.includes(".")) e.preventDefault();
                else if (!p.includes(t) && t !== '.') e.preventDefault();
            } else {
                if (!p.includes(t)) e.preventDefault();
            }
        });
        inp.addEventListener("input", e => {
            const m = sel.options[sel.selectedIndex].dataset.medida;
            const v = e.target.value;
            const re = (m === "Litros" || m === "Kilogramos")
                ? /^\d{0,5}(\.\d{0,3})?$/
                : /^\d{0,5}$/;
            if (re.test(v)) prev = v; else e.target.value = prev;
        });
    }
})();