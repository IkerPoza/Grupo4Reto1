<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AERGIBIDE SL</title>

    <link rel="stylesheet" href="views/estilos/estilos.css">
    <link rel="stylesheet" href="views/estilos/inicioSesion-estilo.css">
</head>

<body>

<?php require __DIR__.'/layout/head.php';?>

<body>
<main class="login-contenedor">
<div class="login-division">
    <h2 class="login-titulo">Inicio Sesion</h2>

    <form class="inicioSesion-form" method="POST">
        <div class="input-email">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Escribe tu email" required><br>
        </div>
        
        <div class="input-password">
            <label for="password">Contrase&ntilde;a:</label>
            <input type="password" id="password" name="password" placeholder="Escribe tu contraseña" required>
        </div>
        
        <div class="botones">
            <input type="hidden" name="controller" value="UsuarioController">
            <input type="hidden" name="accion" value="validarUsuario">
            <input type="submit" id="bInicio" value="Iniciar Sesion">
            <a href="Registro.view.html" id="bRegistro" class="btn-registro">Registrarse</a>
        </div>
    </form>
</div>
</main>
<?php require __DIR__.'/layout/footer.php'; ?>



</html>