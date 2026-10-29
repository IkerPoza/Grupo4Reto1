<?php 
    $titulo = 'Login';
    $css = 'InicioSesion-estilo.css';
    $scripts = 'index.script.js';
    $mostrarPerfil = false;
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // leer el error y borrarlo para que solo salga una vez
    $error = $_SESSION['error'] ?? '';
    require __DIR__.'/layout/head.php';
?>

<main class="login-contenedor">
<div class="login-division">
    <h2 class="login-titulo">Inicio Sesion</h2>

    <form action="index.php" class="inicioSesion-form" method="POST" autocomplete="off">
        <div class="input-email">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Escribe tu email"
             autocomplete="off" readonly onfocus="this.removeAttribute('readonly')"><br>
            <small class="mensaje-error" id="error-email"></small>
        </div>
        
        <div class="input-password">
            <label for="password">Contrase&ntilde;a:</label>
            <input type="password" id="password" name="password" placeholder="Escribe tu contraseña"
             autocomplete="off" readonly onfocus="this.removeAttribute('readonly')">
            <small class="mensaje-error" id="error-password"></small>
        </div>
        
        <div class="botones">
            <input type="hidden" name="controller" value="InicioController">
            <input type="hidden" name="accion" value="validarUsuario">
            <input type="submit" id="bInicio" value="Iniciar Sesion">
            </form>
            <form action="index.php" method="POST">
                <input type="hidden" name="controller" value="RegistroController">
                <input type="hidden" name="accion" value="index">
                <input type="submit" id="bRegistro" value="Registrarse">
            </form>
        </div>
    <p class="mensajeError"><?php 
        if(isset($_SESSION["error"])):
            echo $_SESSION  ["error"];
        endif;
    ?></p>
</div>
</main>
<?php require __DIR__.'/layout/footer.php'; ?>
