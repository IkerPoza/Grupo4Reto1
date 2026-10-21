<?php
$areas = [
    'desarrollo' => 'Desarrollo',
    'diseno' => 'Diseño',
    'formacion' => 'Formación y Recursos',
    'administracion' => 'Administración',
    'soporte' => 'Soporte'
];
$nombreArea = $areas[$usuario->area] ?? $usuario->area ?? 'Sin especificar';

$titulo = 'Perfil';
$css = ['../estilos/crearPregunta-estilo.css'];
$scripts = [''];
require __DIR__ . '/layout/head.php';
require __DIR__ . '/layout/nav.php';
?>

<main class="crearPregunta-contenedor">
    <div class="crearPregunta-division">
        <h2 class="crearPregunta-titulo">Nueva pregunta</h2>

        <form class="crearPregunta-form">
            <div>
            <label for="titulo">Titulo</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo de la pregunta" required>
            </div>
            <div>
            <label for="Descripcion">Descripcion</label>
            <input type="text" name="descripcion" id="descripcion" placeholder="Descripcion de la pregunta" required>
            </div>
            <div>
            <label for="Gmail">Gmail</label>
            <input type="email" name="gmail" id="gmail" placeholder="Introduce tu gmail" required>
            </div>
            <div>
            <label for="area">Area</label>
            <select id="area" name="area" required>
                <?php foreach (AREAS as $clave => $texto): ?>
                    <option value="<?= $clave ?>" <?= $usuario->area === $clave ? 'selected' : '' ?>><?= $texto ?></option>
                <?php endforeach; ?>
            </select>
            </div>
                
            
            
            
            <a href="inicio.view.html" class="btn-annadir">Añadir</a>
            <button type="submit" class="btn-eliminar">Eliminar</button>
            
        </form>
    </div>
    <div class="crearPregunta-imagen">
        <label for="subir-imagen">
            <img src="imagenes/ImagenVacia.png" alt="Añadir imagen">
            <input type="file" id="subir-imagen" name="imagen" style="display: none;">
        </label>
    </div>
</main>

<?php require __DIR__.'/layout/footer.php'; ?>
<script src="scripts/registro.script.js"></script>

</html>