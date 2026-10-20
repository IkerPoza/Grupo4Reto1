/* espera a que el html este cargado */
document.addEventListener("DOMContentLoaded", () => {

    // guadar las constantes de los elementos que necesitamos 
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const btnInicio = document.getElementById("bInicio");


    // vaalida el correo cuando el usuario sale de ese campo
    emailInput.addEventListener("blur", () => {
        // solo valida si ha escrito algo
        if (emailInput.value.trim() !== "") {
            validarEmail();
        } else {
            limpiarError("error-email");
        }
    });


    // valida la contraseña cuando el usuario sale de ese campo
    passwordInput.addEventListener("blur", () => {
        // solo valida si ha escrito algo
        if (passwordInput.value !== "") {
            validarPassword();
        } else {
            limpiarError("error-password");
        }
    });


    // valida los dos campos cuando se pulsa el botpn
    btnInicio.addEventListener("click", (event) => {
        // evita que el formulario se envie automaticamente
        event.preventDefault();

        const okEmail = validarEmail();
        const okPassword = validarPassword();

        if (okEmail && okPassword) {
            const email = emailInput.value.trim();
            const password = passwordInput.value;
           
            // enviar  correo y  contraseña
            fetch("indexModel.php", {
                method: "POST",
                headers: {"Content-Type": "application/json" },
                body: JSON.stringify({email: email,
                                      password: password
                                    })
            })

            .then(response => response.json())
            .then(data => {console.log(data);})
            .catch(error => {
                console.error("Error al enviar los datos:", error);
            });
        }
        
    });

});


/* comprobar correo sea valido y no vacio */
function validarEmail() {
    const email = document.getElementById("email").value.trim();
    const expregEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {
        mostrarError("error-email", "El campo Email no puede estar vacío.");
        return false;
    }

    if (!expregEmail.test(email)) {
        mostrarError("error-email", "Formato de correo no válido.");
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
        mostrarError("error-password", "El campo Contraseña no puede estar vacío.");
        return false;
    }
    if (!expregPass.test(password)) {
        mostrarError("error-password", "Mínimo 8 caracteres, mayúscula, minúscula, número y símbolo.");
        return false;
    }
    limpiarError("error-password");
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
