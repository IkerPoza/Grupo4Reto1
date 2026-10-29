<?php
$titulo = $titulo ?? '';
$css = $css ?? '';
$scripts = $scripts ?? '';
$usuarioHeader = $_SESSION['usuario'] ?? '';
$mostrarPerfil = $mostrarPerfil ?? true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo !== '' ? $titulo . ' · ' : '' ?>AERGIBIDE SL</title>
    <link rel="stylesheet" href="views/estilos/<?= $css ?>">
    <link rel="stylesheet" href="views/estilos/estilos.css">
    <script src="views/scripts/<?= $scripts ?>" defer></script>
    <link rel="icon" type="image/svg+xml" href="views/imagenes/Logo.svg">
</head>
<body>
    <header class="cabecera">
        <div class="logo">
            <form action="index.php" method="POST">
                <input type="hidden" name="controller" value="PreguntasController">
                <input type="hidden" name="accion" value="index">

                <button type="submit" class="botonLogo">
                    <img src="views/imagenes/Logo.svg" alt="Logo">
                    <h1>AERGIBIDE SL</h1>
                </button>
            </form>
        </div>

        <?php if ($mostrarPerfil): ?>
        <div class="perfil">
            <form action="index.php" method="POST">
                <input type="hidden" name="controller" value="UsuarioController">
                <input type="hidden" name="accion" value="show">

                <button type="submit" class="botonPerfil">
                    <div class="imagenPerfilHeader">
                        <img src="<?= htmlspecialchars(!empty($usuarioHeader->foto) ? $usuarioHeader->foto : 'views/imagenes/Perfil.svg') ?>" alt="Foto de perfil" class="fotoPerfilHeader">
                    </div>
                </button>

            </form>
        </div>
        <?php endif; ?>

    </header>

