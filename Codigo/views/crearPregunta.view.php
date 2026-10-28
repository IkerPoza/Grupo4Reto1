<?php

$titulo = 'NuevaPregunta';
$css = 'crearPregunta-estilo.css';
$scripts = 'crearPregunta.script.js';

require __DIR__ . '/layout/head.php';
require __DIR__ . '/layout/nav.php';

?>

</nav>

<main class="crearPregunta-contenedor">

    <div class="crearPregunta-division">

        <h2 class="crearPregunta-titulo">Nueva pregunta</h2>

        <form action="index.php" class="crearPregunta-form" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="controller" value="CrearPreguntaController">
            <input type="hidden" name="accion" value="store">

            <div class="campo">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" placeholder="Título de la pregunta" required>
                <small class="mensaje-error" id="error-titulo"></small>
            </div>

            <div class="campo">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="5" placeholder="Descripción de la pregunta" required></textarea>
                <small class="mensaje-error" id="error-descripcion"></small>
            </div>

            <div class="campo">
                <label for="tema">Tema</label>
                <select id="tema" name="tema" required>
                    <option value="" selected>--Selecciona el tema de tu pregunta--</option>
                    <option value="Ingeniería y Diseño">Ingeniería y Diseño</option>
                    <option value="Normativa y Calidad">Normativa y Calidad</option>
                    <option value="Mantenimiento">Mantenimiento</option>
                    <option value="Sostenibilidad e innovación">Sostenibilidad e Innovación</option>
                    <option value="Formación y Recursos">Formación y Recursos</option>
                </select>

                <small class="mensaje-error" id="error-tema"></small>
            </div>


            <div class="campo campo-imagen">
                <label for="subir-imagen"> Selecciona una imagen para la pregunta</label>
                <input type="file" id="subir-imagen" name="imagen" accept="image/jpeg,image/png,image/webp,image/gif">
            </div>

            <div class="contenedor-botones">
                <button type="submit" id="bAnadir" class="btn-annadir">Añadir</button>
                <button type="button" id="bEliminar" class="btn-eliminar">Eliminar</button>
            </div>
        </form>

        <p class="mensajeError">
            <?php
            if (isset($_COOKIE["error"])):
                echo htmlspecialchars($_COOKIE["error"]);
            endif;
            ?>
        </p>

    </div>

</main>

<?php require __DIR__ . '/layout/footer.php'; ?>

<script src="scripts/crearPregunta.script.js"></script>
