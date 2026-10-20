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
            limpiarError("error-nombre");
        }
    });

    /* valida los apelldios cuando el usuario sale de ese campo */
    apellidoInput.addEventListener("blur", () => {
        if (apellidoInput.value.trim() !== "") {
            validarApellido();
        } else {
            limpiarError("error-apellidos");
        }
    });
 
    /*vaalida el correo cuando el usuario sale de ese campo */
    emailInput.addEventListener("blur", () => {
        if (emailInput.value.trim() !== "") {
            validarEmail();
        } else {
            limpiarError("error-email");
        }
    });

    /*validar select cuando el usuario sale de ese campo */
    areaSelect.addEventListener("blur", () => {
        if (areaSelect.value !== "") {
            validarArea();
        } else {
            limpiarError("error-area");
        }
    });

    /*validar select cuando el usuario sale de ese campo */
    antiguedadInput.addEventListener("blur", () => {
        if (antiguedadInput.value !== "") {
            validarAntiguedad();
        } else {
            limpiarError("error-area");
        }
    });

    /*valida la contraseña cuando el usuario sale de ese campo*/
    passwordInput.addEventListener("blur", () => {
        // solo valida si ha escrito algo
        if (passwordInput.value !== "") {
            validarPassword();
        } else {
            limpiarError("error-password");
        }



    /* validar todos los campos al pulsar el boton de registrarse */
    btnRegistro.addEventListener('click', (event) => {
        event.preventDefault();

        //comprobar todos los campos para asegurarnos otra vez de que todo esta bien 
        // Comprueba todos los campos
        const okNombre = validarNombre();
        const okApellidos = validarApellido();
        const okEmail = validarEmail();
        const okArea = validarArea();
        const okAntiguedad = validarAntiguedad();
        const okPassword = validarPassword();

        // y si algo falla no continua
        if (!okNombre ||!okApellidos ||!okEmail ||!okArea || !okAntiguedad ||!okPassword) {
            return;
        }

        // recoge todos los datos del formulario
        const datosEmpleado = {
            nombre: nombreInput.value.trim(),
            apellidos: apellidoInput.value.trim(),
            email: emailInput.value.trim(),
            area: areaSelect.value,
            antiguedad: antiguedadInput.value,
            password: passwordInput.value
        };


        //ENVIAR DATOS AL SERVIDOR 
        fetch("index.php?controller=registro&action=store", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(datosEmpleado)
            })

        .then(response => response.json())
        .then(data => {
            if (data.ok) {
            alert(data.mensaje);
            window.location.href = "index.php"; // rredirigir al login
    } else {
        alert(data.mensaje);
    }
        })
        .catch(error => {
            console.error("Error al registrar al empleado:", error);
        });
        
    });
});
});


//VALIDACIONES

    /* validar nombre */
    function validarNombre() {
        const nombre = document.getElementById("nombre").value.trim();
        const expregNombre = /^[A-Za-zÁÉÍÓÚáéíóú]+$/;

        if (nombre === "") {
            mostrarError("error-nombre", "El campo 'Nombre' no puede estar vacio");
            return false;
        }
        if (!expregNombre.test(nombre)) {
            mostrarError("error-email", "Formato de nombre no valido");
            return false;
        }
        limpiarError("error-nombre");
        return true;
    }

    /* validar apellidos */
    function validarApellido() {
        const apellidos = document.getElementById("apellidos").value.trim();
        const expregApellidos = /^[A-Za-zÁÉÍÓÚáéíóú]+$/;

        if (apellidos === "") {
            mostrarError("error-apellidos", "El campo 'Apellidos' no puede estar vacio");
            return false;
        }
        if (!expregApellidos.test(apellidos)) {
            mostrarError("error-apellidos", "Formato de apellido no valido");
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
            mostrarError("error-email", "El campo Email no puede estar vacío");
            return false;
        }
        if (!expregEmail.test(email)) {
            mostrarError("error-email", "Formato de correo no válido");
            return false;
        }
        limpiarError("error-email");
        return true;
    }
    
    /* validar select */
    function validarArea() {
        const area = document.getElementById("area").value;
        if (area === "") {
            mostrarError("error-area", "Debes seleccionar un área de trabajo.");
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
            mostrarError("error-antiguedad", "El campo Email no puede estar vacío");
            return false;
        }
        if (!expregAntiguedad.test(antiguedad)) {
            mostrarError("error-antiguedad", "Formato de correo no válido");
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

    










