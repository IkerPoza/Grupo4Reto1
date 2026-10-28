/* espera a que el html este cargado */
document.addEventListener("DOMContentLoaded", () => {

    // guadar las constantes de los elementos que necesitamos 
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const btnInicio = document.getElementById("bInicio");


    // valida el correo cuando el usuario sale de ese campo
    emailInput.addEventListener("blur", () => {
        if (emailInput.value.trim() !== "") {
            validarEmail();
        } else {
            mostrarError("error-email", "* El campo no puede estar vacío");
        }
    });


    // valida la contraseña cuando el usuario sale de ese campo
    passwordInput.addEventListener("blur", () => {
        if (passwordInput.value !== "") {
            validarPassword();
        } else {
            mostrarError("error-password", "* El campo no puede estar vacío");
        }
    });

});


/* comprobar correo sea valido y no vacio */
function validarEmail() {
    const email = document.getElementById("email").value.trim();
    const expregEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {
        mostrarError("error-email", "* El campo Email no puede estar vacío.");
        return false;
    }

    if (!expregEmail.test(email)) {
        mostrarError("error-email", "* Formato de correo no válido.");
        return false;
    }
    limpiarError("error-email");
    return true;
}


/* comprueba que la contraseña cumpla todos los requisitos */
function validarPassword() {
    const password = document.getElementById("password").value;
    //8 caracteres, mayuscula, minuscula, numero y sumbolo
    const expregPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d\s])\S{8,}$/;
    if (password === "") {
        mostrarError("error-password", "* El campo Contraseña no puede estar vacío.");
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
