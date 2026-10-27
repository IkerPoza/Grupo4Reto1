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

        <form action="index.php" class="crearPregunta-form" method="POST">
             <input type="hidden" name="controller" value="CrearPreguntaController">
             <input type="hidden" name="accion" value="store">
            <div>
                <label for="titulo">Titulo</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo de la pregunta" required>
                <small class="mensaje-error" id="error-titulo"></small>
            </div>
            <div>
                <label for="Descripcion">Descripcion</label>
                <input type="text" name="descripcion" id="descripcion" placeholder="Descripcion de la pregunta" required>
                <small class="mensaje-error" id="error-descripcion"></small>
            </div>
            <div>
            <label for="Tema">Tema</label>
            <select id="tema" name="tema" required>
                            <option value="" selected> --Selecciona el tema de tu pregunta-- </option>
                            <option value="Ingeniería y Diseño"> Ingeniería y Diseño </option>
                            <option value="Normativa y Calidad"> Normativa y Calidad </option>
                            <option value="Mantenimiento"> Mantenimiento </option>
                            <option value="Sostenibilidad e Innovacion"> Sostenibilidad e Innovacion </option>
                            <option value="Formacion y Recursos"> Formacion y Recursos </option>
            </select>
            <small class="mensaje-error" id="error-tema"></small>
            </div>
            
            <button type="submit" id="bAnadir" class="btn-annadir">Añadir</button>
            <button type="button" id="bEliminar" class="btn-eliminar">Eliminar</button>
            
            
        </form> 
        <p class="mensajeError"><?php 
                            if(isset($_COOKIE["error"])):
                                echo $_COOKIE["error"]; 
                            endif;
                            ?>
        </p>
    </div>
    <div class="crearPregunta-imagen">
        <label for="subir-imagen">
            <img src="views/imagenes/ImagenVacia.svg" alt="Foto de pregunta">
            <input type="file" id="subir-imagen" name="imagen" style="display: none;">
        </label>
    </div>
   
</main>

<?php require __DIR__.'/layout/footer.php'; ?>
<script src="scripts/crearPregunta.script.js"></script>
