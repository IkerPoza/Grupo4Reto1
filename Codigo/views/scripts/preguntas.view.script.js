/*Coger Datos*/
const svgFavoritos = document.getElementsByClassName("botonFavorito");

const sectionTema = document.getElementsByClassName("tema");

document.getElementById('ordenar')?.addEventListener('change', cambiarOrden)
const tarjetasPregunta = document.querySelectorAll(".tarjetaPregunta");

tarjetasPregunta.forEach((tarjeta) => {
    tarjeta.addEventListener("click", (event) => {
        if (event.target.closest(".autor a")) {
            return;
        }
        if (event.target.closest(".botonFavorito")) {
            return;
        }
        window.location.href = tarjeta.dataset.url;
    });
});

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

    // Icono
    this.setAttribute('fill', esFavorito ? 'none' : '#FFDD33');

    // Contador de "Mis Favoritos"
    const contador = document.querySelector('.tema[data-id="favoritas"] p:last-child');
    contador.textContent = parseInt(contador.textContent) + (esFavorito ? -1 : 1);

    // Si estoy en favoritos y desmarco, quito la tarjeta
    const temaActual = document.getElementById('seleccionado')?.dataset.id;
    if (temaActual === 'favoritas' && esFavorito) {
        this.closest('.tarjetaPregunta').remove();

        if (document.querySelectorAll('.tarjetaPregunta').length === 0) {
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

/* Votación de respuestas en la vista de detalle */
document.querySelectorAll('.accionVoto[data-id-respuesta]').forEach((boton) => {
    boton.addEventListener('click', async () => {
        const grupo = boton.closest('.accionesRespuesta');
        const botones = grupo.querySelectorAll('.accionVoto[data-id-respuesta]');
        const idRespuesta = boton.dataset.idRespuesta;
        const votoSolicitado = Number(boton.dataset.voto);

        botones.forEach((b) => b.disabled = true);
        try {
            const respuesta = await fetch('index.php?controller=PreguntasController&accion=votar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ idRespuesta: Number(idRespuesta), voto: votoSolicitado })
            });
            const datos = await respuesta.json();

            if (!respuesta.ok || !datos.ok) {
                alert(datos.mensaje || 'No se ha podido registrar el voto.');
                return;
            }

            const votoActual = Number(datos.miVoto);
            const contador = grupo.querySelector('.contadorVotos');
            if (contador) contador.textContent = datos.totalVotos;

            botones.forEach((b) => {
                const esArriba = Number(b.dataset.voto) === 1;
                const seleccionado = (votoActual === 1 && esArriba) || (votoActual === -1 && !esArriba);
                const img = b.querySelector('img');
                img.src = 'views/imagenes/' + (esArriba
                    ? (seleccionado ? 'FlechaArribaRellena.svg' : 'FlechaArriba.svg')
                    : (seleccionado ? 'FlechaAbajoRellena.svg' : 'FlechaAbajo.svg'));
                b.classList.toggle('votoSeleccionado', seleccionado);
            });

            if (datos.mensaje) alert(datos.mensaje);
        } catch (error) {
            alert('Error de conexión al registrar el voto.');
        } finally {
            botones.forEach((b) => b.disabled = false);
        }
    });
});