/* coer datos */
const navegacion = document.querySelector(".navegacion");
const vistaPerfil = document.getElementById("vistaPerfil");
const vistaEdicion = document.getElementById("vistaEdicion");

const listaPreguntas = document.getElementById("listaPreguntas");
const listaFavoritas = document.getElementById("listaFavoritas");
const sinFavoritas = document.getElementById("sinFavoritas");

const botonPreguntas = document.getElementById("botonPreguntas");
const botonFavoritas = document.getElementById("botonFavoritas");

const formPerfil = document.getElementById("formPerfil");

const nombre = document.getElementById("nombre");
const area = document.getElementById("area");
const email = document.getElementById("email");
const antiguedad = document.getElementById("antiguedad");

const subirImagen = document.getElementById("subirImagen");


/* pestaña preguntas */
function mostrarPreguntas() {
    listaPreguntas.hidden = false;
    listaFavoritas.hidden = true;

    botonPreguntas.classList.add("activa");
    botonFavoritas.classList.remove("activa");

    botonPreguntas.setAttribute("aria-pressed", "true");
    botonFavoritas.setAttribute("aria-pressed", "false");
}


/* pestaña favoritos */
function mostrarFavoritas() {
    listaPreguntas.hidden = true;
    listaFavoritas.hidden = false;

    botonPreguntas.classList.remove("activa");
    botonFavoritas.classList.add("activa");

    botonPreguntas.setAttribute("aria-pressed", "false");
    botonFavoritas.setAttribute("aria-pressed", "true");
}

/*pestaña ediar*/
function mostrarEdicion() {
    vistaPerfil.hidden = true;
    vistaEdicion.hidden = false;
    navegacion.hidden = true;
}


/* quitar pestaña editar y no guardar los datos*/
function cancelarEdicion() {
    formPerfil.reset();

    vistaEdicion.hidden = true;
    vistaPerfil.hidden = false;
    navegacion.hidden = false;
}


/* DETECTAR LOS CLICS EN LOS FAVORITOS */

document.addEventListener("click", async function (evento) {
    const boton = evento.target.closest(".favorito");

    // Si el clic no es en un favorito, no hacemos nada.
    if (!boton) {
        return;
    }

    const idPregunta = boton.dataset.id;

    const esFavorita =
        boton.getAttribute("aria-pressed") === "true";

    const nuevaEstado = !esFavorita;

    try {
        // Enviar el cambio a PHP.
        const respuesta = await fetch("favoritos.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                idPregunta: idPregunta,
                favorita: nuevaEstado
            })
        });

        if (!respuesta.ok) {
            throw new Error("Error al guardar el favorito.");
        }

        const resultado = await respuesta.json();

        if (!resultado.ok) {
            throw new Error(
                resultado.mensaje || "No se pudo actualizar el favorito."
            );
        }

        // Actualizar el estado con la respuesta del servidor.
        actualizarEstadoFavorito(
            idPregunta,
            resultado.favorita
        );

    } catch (error) {
        console.error(error);
        alert("No se ha podido actualizar el favorito.");
    }
});


/* ACTUALIZAR EL ICONO Y LAS DOS LISTAS */

function actualizarEstadoFavorito(idPregunta, esFavorita) {
    // Buscar el botón original de la pregunta.
    const botonOriginal = document.querySelector(
        '#listaPreguntas .favorito[data-id="' + idPregunta + '"]'
    );

    if (!botonOriginal) {
        return;
    }

    // Actualizar el botón original.
    cambiarIcono(botonOriginal, esFavorita);

    // Buscar la copia de esa pregunta en Favoritas.
    const preguntaFavorita = Array.from(
        listaFavoritas.querySelectorAll(".pregunta")
    ).find(function (pregunta) {
        return pregunta.querySelector(".favorito")?.dataset.id
            === idPregunta;
    });

    if (esFavorita) {
        // Si no está en Favoritas, añadir una copia.
        if (!preguntaFavorita) {
            const pregunta = botonOriginal.closest(".pregunta");
            const copia = pregunta.cloneNode(true);

            listaFavoritas.appendChild(copia);
        }
    } else {
        // Si se ha desmarcado, quitarla de Favoritas.
        if (preguntaFavorita) {
            preguntaFavorita.remove();
        }
    }

    // Mostrar el mensaje solo si no hay preguntas favoritas.
    const hayFavoritas =
        listaFavoritas.querySelector(".pregunta") !== null;

    sinFavoritas.hidden = hayFavoritas;
}


/* CAMBIAR EL ICONO */

function cambiarIcono(boton, esFavorita) {
    boton.setAttribute("aria-pressed", String(esFavorita));

    const imagen = boton.querySelector("img");

    if (imagen) {
        if (esFavorita) {
            imagen.src = "imagenes/FavoritoActivado.png";
            imagen.alt = "Quitar de favoritas";
        } else {
            imagen.src = "imagenes/FavoritoDesactivado.png";
            imagen.alt = "Añadir a favoritas";
        }
    }
}
