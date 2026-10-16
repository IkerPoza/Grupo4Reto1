document.addEventListener("DOMContentLoaded", () => {
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const btnInicio = document.getElementById("bInicio");

    /* 1. Validaciones en tiempo real al salir de cada campo (blur) */
    emailInput.addEventListener("blur", () => {
        // soolo valida si el usuario ha escrito algo
        if (emailInput.value.trim() !== "") {
            validarEmail();
        } else {
            limpiarError("error-email");
        }
    });

    passwordInput.addEventListener("blur", () => {
        if (passwordInput.value !== "") {
            validarPassword();
        } else {
            limpiarError("error-password");
        }
    });

    /* 2. Validación completa al hacer click en boton */
    btnInicio.addEventListener("click", (event) => {
        event.preventDefault();

        const okEmail = validarEmail();
        const okPassword = validarPassword();

        // si ambos son válidos se hace  con el fetch
        if (okEmail && okPassword) {
            console.log("Formulario correcto. Preparado para Fetch a PHP.");
            
            /* aqui el fetch:
            fetch('login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    email: emailInput.value,
                    password: passwordInput.value
                })
            })
            .then(res => res.json())
            .then(data => console.log(data));
            */
        }
    });
});

/* VALIDACIONES */

function validarEmail() {
    const emailInput = document.getElementById("email");
    const email = emailInput.value.trim();
    const regExEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {
        mostrarError("error-email", "El campo Email no puede estar vacío.");
        return false;
    }

    if (!regExEmail.test(email)) {
        mostrarError("error-email", "Formato de correo no válido (ejemplo: usuario@dominio.com).");
        return false;
    }

    limpiarError("error-email");
    return true;
}

function validarPassword() {
    const passwordInput = document.getElementById("password");
    const password = passwordInput.value;
    const regExPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d\s])\S{8,}$/;

    if (password === "") {
        mostrarError("error-password", "El campo Contraseña no puede estar vacío.");
        return false;
    }

    if (!regExPass.test(password)) {
        mostrarError("error-password", "Mínimo 8 caracteres, mayúscula, minúscula, número y símbolo.");
        return false;
    }

    limpiarError("error-password");
    return true;
}

/* FUNCIONES PARA MOSTRAR / LIMPIAR MENSAJES */

function mostrarError(idElemento, mensaje) {
    const errorContenedor = document.getElementById(idElemento);
    if (errorContenedor) {
        errorContenedor.textContent = mensaje;
    }
}

function limpiarError(idElemento) {
    const errorContenedor = document.getElementById(idElemento);
    if (errorContenedor) {
        errorContenedor.textContent = "";
    }
}