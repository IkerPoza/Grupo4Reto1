const navegacion = document.querySelector(".navegacion");
const flechaVolver = document.querySelector(".navegacion > a");
const vistaPerfil = document.getElementById("vistaPerfil");
const vistaEdicion = document.getElementById("vistaEdicion");
const listaPreguntas = document.getElementById("listaPreguntas");
const listaFavoritas = document.getElementById("listaFavoritas");
const sinFavoritas = document.getElementById("sinFavoritas");
const botonPreguntas = document.getElementById("botonPreguntas");
const botonFavoritas = document.getElementById("botonFavoritas");
const formPerfil = document.getElementById("formPerfil");

function mostrarPreguntas() {
    if (!listaPreguntas || !listaFavoritas) return;
    listaPreguntas.hidden = false;
    listaFavoritas.hidden = true;
    botonPreguntas?.classList.add("activa");
    botonFavoritas?.classList.remove("activa");
    botonPreguntas?.setAttribute("aria-pressed", "true");
    botonFavoritas?.setAttribute("aria-pressed", "false");
}

function mostrarFavoritas() {
    if (!listaPreguntas || !listaFavoritas) return;
    listaPreguntas.hidden = true;
    listaFavoritas.hidden = false;
    botonPreguntas?.classList.remove("activa");
    botonFavoritas?.classList.add("activa");
    botonPreguntas?.setAttribute("aria-pressed", "false");
    botonFavoritas?.setAttribute("aria-pressed", "true");
}

function mostrarEdicion() {
    if (vistaPerfil && vistaEdicion) {
        vistaPerfil.hidden = true;
        vistaEdicion.hidden = false;
        const cerrarSesion = document.querySelector('form[action*="accion=logout"]');
        if (cerrarSesion) {
            cerrarSesion.hidden = true;
        }
        if (flechaVolver) {
            flechaVolver.hidden = true;
        }
    }
}

function cancelarEdicion() {
    formPerfil?.reset();
    if (vistaEdicion) {
        vistaEdicion.hidden = true;
    }
    if (vistaPerfil) {
        vistaPerfil.hidden = false;
    }
    const cerrarSesion = document.querySelector('form[action*="accion=logout"]');
    if (cerrarSesion) {
        cerrarSesion.hidden = false;
    }
    if (flechaVolver) {
        flechaVolver.hidden = false;
    }
}

/* Añadir/quitar favoritos guardados en la base de datos */
document.addEventListener("click", async (evento) => {
    const boton = evento.target.closest(".favorito");
    if (!boton || boton.disabled) return;

    const idPregunta = boton.dataset.id;
    const nuevaEstado = boton.getAttribute("aria-pressed") !== "true";
    boton.disabled = true;

    try {
        const respuesta = await fetch("index.php?controller=PreguntasController&accion=favorita", {
            method: "POST",
            headers: { "Content-Type": "application/json", "Accept": "application/json" },
            body: JSON.stringify({ idPregunta, favorita: nuevaEstado })
        });
        const resultado = await respuesta.json();
        if (!respuesta.ok || !resultado.ok) throw new Error(resultado.mensaje || "No se pudo actualizar el favorito.");
        actualizarEstadoFavorito(String(idPregunta), Boolean(resultado.favorita));
    } catch (error) {
        console.error(error);
        alert(error.message || "No se ha podido actualizar el favorito.");
    } finally {
        boton.disabled = false;
    }
});

function actualizarEstadoFavorito(idPregunta, esFavorita) {
    const botonOriginal = listaPreguntas?.querySelector(`.favorito[data-id="${idPregunta}"]`);
    const botonGuardado = listaFavoritas?.querySelector(`.favorito[data-id="${idPregunta}"]`);

    if (botonOriginal) cambiarIcono(botonOriginal, esFavorita);

    if (esFavorita) {
        if (!botonGuardado && botonOriginal) {
            const copia = botonOriginal.closest(".tarjetaPregunta").cloneNode(true);
            const botonCopia = copia.querySelector(".favorito");
            botonCopia.disabled = false;
            cambiarIcono(botonCopia, true);
            listaFavoritas.prepend(copia);
        }
    } else if (botonGuardado) {
        botonGuardado.closest(".tarjetaPregunta")?.remove();
    }

    if (sinFavoritas) {
        sinFavoritas.hidden = Boolean(listaFavoritas?.querySelector(".tarjetaPregunta"));
    }
}

function cambiarIcono(boton, esFavorita) {
    boton.classList.toggle("guardado", esFavorita);
    boton.setAttribute("aria-pressed", String(esFavorita));
    const texto = esFavorita ? "Quitar de favoritos" : "Añadir a favoritos";
    boton.setAttribute("aria-label", texto);
    boton.title = texto;
}

window.addEventListener("pageshow", (evento) => {
    if (evento.persisted) window.location.reload();
});