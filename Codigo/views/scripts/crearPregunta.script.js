/* espera a que el html este cargado  */
document.addEventListener("DOMContentLoaded", () => {

    /* guardar constantes  */
    const form = document.querySelector(".crearPregunta-form");
    const tituloInput = document.getElementById("titulo");
    const descripcionInput = document.getElementById("descripcion");
    const temaSelect = document.getElementById("tema");
    const btnAnadir = document.getElementById("bAnadir");
    const btnEliminar = document.getElementById("bEliminar");

    /* vaalida etitulo */
    tituloInput.addEventListener("blur", () => {
        if (tituloInput.value.trim() !== "") {
            validarTitulo();
        } else {
            mostrarError("error-titulo", "* El campo no puede estar vacío");
        }
    });

    /* valida los apelldios cuando el usuario sale de ese campo */
    descripcionInput.addEventListener("blur", () => {
        if (descripcionInput.value.trim() !== "") {
            validarDescripcion();
        } else {
            mostrarError("error-descripcion", "* El campo no puede estar vacío");
        }
    });

    /*validar select cuando el usuario sale de ese campo */
    temaSelect.addEventListener("blur", () => {
        if (temaSelect.value !== "") {
            validarTema();
        } else {
            mostrarError("error-tema", "* Tienes que selecionar una opción");
        }
    });


    //boton eliminar
    btnEliminar.addEventListener("click", () => {
        form.reset(); 

        
        limpiarError("error-titulo");
        limpiarError("error-descripcion");
        limpiarError("error-tema");
    });

});


//VALIDACIONES

    /* validar titulo */
    function validarTitulo() {
        const titulo = document.getElementById("titulo").value.trim();
        const expregTitulos = /^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ\s]+$/;

        if (!expregTitulos.test(titulo)) {
            mostrarError("error-titulo", "* Formato de título no válido");
            return false;
        }
        limpiarError("error-titulo");
        return true;
    }

    /* validar descripcion */
    function validarDescripcion() {
        const descripcion = document.getElementById("descripcion").value.trim();

        if (descripcion.length < 10) {
            mostrarError("error-descripcion", "* La descripción debe tener al menos 10 caracteres");
            return false;
        }
        
        limpiarError("error-descripcion");
        return true;
    }
    
    /* validar select */
    function validarTema() {
        const tema = document.getElementById("tema").value;
        if (tema === "") {
            mostrarError("error-tema", "* Debes seleccionar un tema");
            return false;
        }
        limpiarError("error-tema");
        return true;
    }

    /* mestra mensaje en el elemento de error  */
    function mostrarError(idElemento, mensaje) {
        // busca el elemento donde se mostrara el error
        const errorContenedor = document.getElementById(idElemento);
        // si existe escribe el mensaje
        if (errorContenedor) {
            errorContenedor.textContent = mensaje;
        }
    }

    /* elimina el mensaje de error del elemento indicado */
    function limpiarError(idElemento) {
        // busca el elemento tiene el error
        const errorContenedor = document.getElementById(idElemento);
        // si existe deja  contenido vacio
        if (errorContenedor) {
            errorContenedor.textContent = "";
        }
    }
