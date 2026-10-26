/*Coger Datos*/
const svgFavoritos = document.getElementsByClassName("botonFavorito");

const sectionTema = document.getElementsByClassName("tema");

document.getElementById('ordenar').addEventListener('change', cambiarOrden)

Array.from(svgFavoritos).forEach((boton) => {
    boton.addEventListener('click', Favoritos);
});

Array.from(sectionTema).forEach((boton) => {
    boton.addEventListener('click', temaSeleccionado);
});

async function Favoritos() {
    const esFavorito = this.getAttribute('fill') !== 'none';
    const accion = esFavorito ? 'eliminarFavoritos' : 'anadirFavoritos';

    const response = await fetch("index.php?accion=" + accion, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ pregunta_id: this.dataset.id })
    });

    if (!response.ok) {
        alert("Problemas con el servidor. Inténtalo más tarde.");
        return;
    }

    this.setAttribute('fill', esFavorito ? 'none' : '#FFDD33');

    const contador = document.querySelector('.tema[data-id="favoritas"] p:last-child');
    contador.textContent = parseInt(contador.textContent) + (esFavorito ? -1 : 1);

    const temaActual = document.getElementById('seleccionado')?.dataset.id;
    if (temaActual === 'favoritas' && esFavorito) {
        this.closest('.tarjeta-pregunta').remove();
        if (document.querySelectorAll('.tarjeta-pregunta').length === 0) {
            document.querySelector('.mensajeVacio').hidden = false;
        }
    }
}

function temaSeleccionado() {
    if (this.id != "seleccionado") {
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "index.php";

        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "tema";
        input.value = this.dataset.id;

        const input2 = document.createElement("input");
        input2.type = "hidden";
        input2.name = "accion";
        input2.value = "buscarPorTema";

        form.append(input, input2);
        document.body.appendChild(form);
        form.submit();
    }
}

function cambiarOrden() {
        const temaActual = document.getElementById('seleccionado')?.dataset.id ?? 'todas';

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php';

        [['accion', 'buscarPorTema'], ['tema', temaActual], ['ordenar', this.value]].forEach(([name, value]) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit(); 
}