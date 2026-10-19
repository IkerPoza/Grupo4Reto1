/*Botones */
document.getElementById("bInicio").addEventListener('click', validar);



/*VALIDACIONES */

/*Funcion validar general */
function validar(event){

    event.preventDefault();

    try{

        /*Recoger datos */
        const emailUser = document.getElementById("email").value;
        const passwordUser = document.getElementById("password").value;

        /*Campos rellenos */
        if (emailUser === "" && passwordUser === "") {
            throw new Error("Por favor, rellena todos los campos (Email y Contraseña).");
        }
        if (emailUser === "") {
            throw new Error("El campo 'Email' está vacío.");
        }
        if (passwordUser === "") {
            throw new Error("El campo 'Contraseña' está vacío.");
        }


        /*validar email*/
        validarDato( emailUser, /^[^\s@]+@[^\s@]+\.[^\s@]+$/, "El correo electrónico no es válido.\nEjemplo correcto: usuario@dominio.com");
        /*Validar contraseña */
        validarDato(
            passwordUser,
            /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d\s])\S{8,}$/,
            "La contraseña no cumple con los requisitos mínimos de seguridad:\n" +
            "• Debe tener al menos 8 caracteres.\n" +
            "• Debe contener al menos una letra mayúscula (A-Z).\n" +
            "• Debe contener al menos una letra minúscula (a-z).\n" +
            "• Debe contener al menos un número (0-9).\n" +
            "• Debe contener al menos un carácter especial (ej. @, #, $, !, %, *)."
        );

        alert("Validacion correcta");

    }catch(e){
        alert(e.message);
    }
}

function validarDato(campo, expresionRegular, mensajeError) {
    if (!expresionRegular.test(campo)) {
        throw new Error(mensajeError);
    }
}
