/* espera a que el html este cargado  */
document.addEventListener("DOMContentLoaded", () => {

    /* guardar constantes que necesito */
    const nombreInput = document.getElementById("nombre");
    const apellidoInput = document.getElementById("apellidos");
    const emailInput = document.getElementById("email");
    const areaSelect = document.getElementById("area");
    const antiguedadInput = document.getElementById("antiguedad");
    const passwordInput = document.getElementById("password");
    //boton Registrarse 
    const btnRegistro = document.getElementById("bRegistro");

    /* vaalida el nombre cuando el usuario sale de ese campo */
    nombreInput.addEventListener("blur", () => {
        if (nombreInput.value.trim() !== "") {
            validarNombre();
        } else {
            mostrarError("error-nombre", "* El campo no puede estar vacío");
        }
    });

    /* valida los apelldios cuando el usuario sale de ese campo */
    apellidoInput.addEventListener("blur", () => {
        if (apellidoInput.value.trim() !== "") {
            validarApellido();
        } else {
            mostrarError("error-apellidos", "* El campo no puede estar vacío");
        }
    });
 
    /*vaalida el correo cuando el usuario sale de ese campo */
    emailInput.addEventListener("blur", () => {
        if (emailInput.value.trim() !== "") {
            validarEmail();
        } else {
            mostrarError("error-email", "* El campo no puede estar vacío");
        }
    });

    /*validar select cuando el usuario sale de ese campo */
    areaSelect.addEventListener("blur", () => {
        if (areaSelect.value !== "") {
            validarArea();
        } else {
            mostrarError("error-area", "* El campo no puede estar vacío");
        }
    });

    /*validar select cuando el usuario sale de ese campo */
    antiguedadInput.addEventListener("blur", () => {
        if (antiguedadInput.value !== "") {
            validarAntiguedad();
        } else {
            mostrarError("error-antiguedad", "* El campo no puede estar vacío");
        }
    });

    /*valida la contraseña cuando el usuario sale de ese campo*/
    passwordInput.addEventListener("blur", () => {
        // solo valida si ha escrito algo
        if (passwordInput.value !== "") {
            validarPassword();
        } else {
            mostrarError("error-password", "* El campo no puede estar vacío");
        }
    });
});


//VALIDACIONES

    /* validar nombre */
    function validarNombre() {
        const nombre = document.getElementById("nombre").value.trim();
        const expregNombre = /^[A-ZÁÉÍÓÚ][a-záéíóúñ]+$/;

        if (!expregNombre.test(nombre)) {
            mostrarError("error-nombre", "* Formato de nombre no válido");
            return false;
        }
        limpiarError("error-nombre");
        return true;
    }

    /* validar apellidos */
    function validarApellido() {
        const apellidos = document.getElementById("apellidos").value.trim();
        const expregApellidos = /^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+$/;

        if (apellidos === "") {
            mostrarError("error-apellidos", "* El campo Apellidos no puede estar vacío");
            return false;
        }
        if (!expregApellidos.test(apellidos)) {
            mostrarError("error-apellidos", "* Formato de apellido no válido");
            return false;
        }
        limpiarError("error-apellidos");
        return true;
    }

    /* validar email */
    function validarEmail() {
        const email = document.getElementById("email").value.trim();
        const expregEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email === "") {
            mostrarError("error-email", "* El campo Email no puede estar vacío");
            return false;
        }
        if (!expregEmail.test(email)) {
            mostrarError("error-email", "* Formato de correo no válido");
            return false;
        }
        limpiarError("error-email");
        return true;
    }
    
    /* validar select */
    function validarArea() {
        const area = document.getElementById("area").value;
        if (area === "") {
            mostrarError("error-area", "* Debes seleccionar un área de trabajo");
            return false;
        }
        limpiarError("error-area");
        return true;
    }

    /* validar antiguedad */
    function validarAntiguedad() {
        const antiguedad = document.getElementById("antiguedad").value.trim();
        const expregAntiguedad = /^[0-9]+$/;

        if (antiguedad === "") {
            mostrarError("error-antiguedad", "* El campo Email no puede estar vacío");
            return false;
        }
        if (!expregAntiguedad.test(antiguedad)) {
            mostrarError("error-antiguedad", "* Formato de correo no válido");
            return false;
        }
        limpiarError("error-antiguedad");
        return true;

    }

    /* validar contraseña */
    function validarPassword() {
        const password = document.getElementById("password").value;
        // 8 caracteres, mayuscula, minuscula, numero y sumbolo
        const expregPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d\s])\S{8,}$/;
        if (password === "") {
            mostrarError("error-password", "* El campo Contraseña no puede estar vacío");
            return false;
        }
        if (!expregPass.test(password)) {
            mostrarError("error-password", "* Mínimo 8 caracteres, mayúscula, minúscula, número y símbolo");
            return false;
        }
        limpiarError("error-password");
        return true;
    }

    /* mestra mensaje en el elemento de error  */
    function mostrarError(idElemento, mensaje) {
        const errorContenedor = document.getElementById(idElemento);
        if (errorContenedor) {
            errorContenedor.textContent = mensaje;
        }
    }

    /* elimina el mensaje de error del elemento indicado */
    function limpiarError(idElemento) {

        const errorContenedor = document.getElementById(idElemento);
        if (errorContenedor) {
            errorContenedor.textContent = "";
        }
    }

    










