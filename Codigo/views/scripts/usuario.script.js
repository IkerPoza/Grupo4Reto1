/* coer datos */
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
}


/* quitar pestaña editar y no guardar los datos*/
function cancelarEdicion() {
    formPerfil.reset();

    vistaEdicion.hidden = true;
    vistaPerfil.hidden = false;
}


/* actualizar lista favoritas */
function actualizarFavoritas() {
    const preguntasAnteriores =
        listaFavoritas.querySelectorAll(".pregunta");

    preguntasAnteriores.forEach(function (pregunta) {
        pregunta.remove();
    });

    const botonesFavoritos = listaPreguntas.querySelectorAll(
        ".favorito[aria-pressed='true']"
    );

    sinFavoritas.hidden = botonesFavoritos.length > 0;

    botonesFavoritos.forEach(function (boton) {
        const pregunta = boton.closest(".pregunta");
        const copia = pregunta.cloneNode(true);

        const botonCopia = copia.querySelector(".favorito");

        if (botonCopia) {
            botonCopia.onclick = function () {
                alternarFavorito(boton);
            };
        }

        listaFavoritas.appendChild(copia);
    });
}



/* coger la foto que elijas */
subirImagen.addEventListener("change", function () {
    if (subirImagen.files.length > 0) {
        subirImagen.form.submit();
    }
});


/* para que la foto de favoritos se cambie dependiendo de si esta en favoritos o no */
function alternarFavorito(boton) {
    const imagen = boton.querySelector("img");

    const esFavorita =
        boton.getAttribute("aria-pressed") === "true";

    if (esFavorita) {
        boton.setAttribute("aria-pressed", "false");
        imagen.src = "imagenes/FavoritoDesactivado.png";
        imagen.alt = "Añadir a favoritas";
    } else {
        boton.setAttribute("aria-pressed", "true");
        imagen.src = "imagenes/FavoritoActivado.png";
        imagen.alt = "Quitar de favoritas";
    }

    actualizarFavoritas();
}



/* para que se vea la imagen de favoritos */
document.querySelectorAll("#listaPreguntas .favorito").forEach(
    function (boton) {
        const imagen = boton.querySelector("img");

        const esFavorita =
            boton.getAttribute("aria-pressed") === "true";

        if (esFavorita) {
            imagen.src = "imagenes/FavoritoActivado.png";
            imagen.alt = "Quitar de favoritas";
        } else {
            imagen.src = "imagenes/FavoritoDesactivado.png";
            imagen.alt = "Añadir a favoritas";
        }
    }
);


actualizarFavoritas();