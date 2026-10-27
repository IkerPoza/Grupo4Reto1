
<?php
$areas = [
    "ingenieriaYDiseno" => "Ingeniería y diseño",
    "produccionYFabricacion" => "Producción y fabricación",
    "mantenimientoYReparacion" => "Mantenimiento y reparación",
    "calidadYSeguridad" => "Calidad y seguridad",
    "investigacionYDesarrollo" => "Investigación y desarrollo",
    "electronicaYSistemasInformaticos" => "Electrónica y sistemas informáticos",
    "logisticaYAlmacen" => "Logística y almacén",
    "administracionYRecursosHumanos" => "Administración y recursos humanos",
    "comercialYMarketing" => "Comercial y marketing",
    "informaticaYCiberseguridad" => "Informática y ciberseguridad",
    "comprasYProveedores" => "Compras y proveedores",
    "gestionDeProyectos" => "Gestión de proyectos"
];

$nombreArea = $areas[$usuario->area] ?? $usuario->area ?? 'Sin especificar';

$titulo = 'Perfil';
$css = 'usuario-estilo.css';
$scripts = 'usuario.script.js';

require __DIR__ . '/layout/head.php';
require __DIR__ . '/layout/nav.php';
?>

<?php if ($esPropio): ?>
    <form method="post" action="index.php?controller=UsuarioController&accion=logout">
        <button type="submit" class="cerrarSesion" title="Cerrar sesión">
            <img src="views/imagenes/CerrarSesion.svg" alt="Cerrar sesión">
        </button>
    </form>
<?php endif; ?>
</nav>

<main>
    <!-- VISTA DEL PERFIL -->
    <section id="vistaPerfil">

        <section class="perfil tarjetaPerfil" id="perfil">

            <div class="imagenPerfil">
                <img src="<?= htmlspecialchars(!empty($usuario->foto) ? $usuario->foto : 'views/imagenes/ImagenVacia.svg') ?>" alt="Foto de perfil" class="fotoPerfil">
            </div>

            <div class="datosPerfil">
                <div class="identidad">
                    <h2 id="nombreVisible">
                        <?= htmlspecialchars($usuario->nombre . ' ' . $usuario->apellidos) ?>
                    </h2>

                    <p id="areaVisible">
                        <?= htmlspecialchars($nombreArea) ?>
                    </p>
                </div>

                <div class="estadisticas">
                    <article class="estadistica">
                        <strong id="antiguedadVisible"><?= $usuario->antiguedad ?></strong>
                        <p>Antigüedad (años)</p>
                    </article>

                    <article class="estadistica">
                        <strong><?= $stats->preguntas ?></strong>
                        <p>Dudas</p>
                    </article>

                    <article class="estadistica">
                        <strong><?= $stats->respuestas ?></strong>
                        <p>Respuestas</p>
                    </article>

                    <article class="estadistica">
                        <strong><?= $stats->votosRecibidos ?></strong>
                        <p>Votos recibidos</p>
                    </article>
                </div>

                <?php if ($esAdmin || $esPropio): ?>
                    <div class="botonesPerfil">

                        <?php if ($esAdmin): ?>
                            <form method="POST" action="index.php?controller=UsuarioController&accion=destroy"  onsubmit="return confirm('¿Seguro que quieres eliminar este perfil?');">
                                <input type="hidden" name="id" value="<?= $usuario->id ?>">
                                <button type="submit" class="eliminarPerfil">
                                    Eliminar perfil
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if ($esPropio): ?>
                            <button type="button" class="editarPerfil" onclick="mostrarEdicion()">
                                Editar perfil
                            </button>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>

            </div>
        </section>

        <nav class="pestanas">
            <button type="button" class="pestana activa" id="botonPreguntas" aria-pressed="true" onclick="mostrarPreguntas()" >
                Preguntas
            </button>

            <?php if ($esPropio): ?>
                <button type="button" class="pestana" id="botonFavoritas" aria-pressed="false" onclick="mostrarFavoritas()" >
                    Favoritas
                </button>
            <?php endif; ?>
        </nav>

        <!-- LISTA DE PREGUNTAS -->
        <section id="listaPreguntas" class="listaPreguntas">

            <?php if (empty($preguntas)): ?>
                <p class="mensajeVacio">
                    Todavía no ha publicado ninguna pregunta.
                </p>
            <?php endif; ?>

            <?php foreach ($preguntas as $p): ?>
                <article class="tarjetaPregunta" data-id="<?= $p->id ?>">

                    <div class="contenidoPregunta">
                        <h3><?= htmlspecialchars($p->titulo) ?></h3>

                        <p class="tema">
                            <?= htmlspecialchars($p->tema) ?>
                        </p>

                        <p class="descripcion">
                            <?= htmlspecialchars($p->descripcion) ?>
                        </p>
                    </div>

                    <button type="button" class="favorito <?= !empty($p->guardada) ? 'guardado' : '' ?>" data-id="<?= $p->id ?>" aria-pressed="<?= !empty($p->guardada) ? 'true' : 'false' ?>" title="<?= !empty($p->guardada) ? 'Quitar de favoritos' : 'Añadir a favoritos' ?>">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M6 3.5h12a1 1 0 0 1 1 1V21l-7-4.5L5 21V4.5a1 1 0 0 1 1-1z"/>
                        </svg>
                    </button>

                </article>
            <?php endforeach; ?>

        </section>

        <!-- LISTA DE PREGUNTAS FAVORITAS -->
        <section id="listaFavoritas" class="listaPreguntas" hidden>

            <p id="sinFavoritas" class="mensajeVacio" <?= !empty($guardadas) ? 'hidden' : '' ?> >
                Todavía no has añadido preguntas a favoritos.
            </p>

            <?php foreach ($guardadas as $p): ?>
                <article class="tarjetaPregunta" data-id="<?= $p->id ?>">

                    <div class="contenidoPregunta">
                        <h3><?= htmlspecialchars($p->titulo) ?></h3>

                        <p class="tema">
                            <?= htmlspecialchars($p->tema) ?>
                        </p>

                        <p class="descripcion">
                            <?= htmlspecialchars($p->descripcion) ?>
                        </p>
                    </div>

                    <button type="button" class="favorito guardado" data-id="<?= $p->id ?>" aria-pressed="true" title="Quitar de favoritos">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M6 3.5h12a1 1 0 0 1 1 1V21l-7-4.5L5 21V4.5a1 1 0 0 1 1-1z"/>
                        </svg>
                    </button>

                </article>
            <?php endforeach; ?>

        </section>

    </section>
    <!-- FIN DE VISTA DEL PERFIL -->


    <!-- VISTA DE EDICIÓN: FUERA DE vistaPerfil -->
    <?php if ($esPropio): ?>
        <section id="vistaEdicion" hidden>

            <div class="encabezadoEdicion">
                <h2>Editar mi perfil</h2>
                <p>Actualiza tu información personal y profesional.</p>
            </div>

            <section class="formularioPerfil">

                <form id="formPerfil" action="index.php?controller=UsuarioController&accion=update" method="POST" enctype="multipart/form-data">

                    <div class="imagenEdicion">
                        <img src="<?= htmlspecialchars(!empty($usuario->foto) ? $usuario->foto : 'views/imagenes/ImagenVacia.svg') ?>" alt="Foto de perfil" class="fotoPerfil" id="vistaPreviaFoto">

                        <p><b>Foto de perfil</b></p>

                        <label for="foto">Selecciona una imagen</label>
                        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
                    </div>

                    <div class="campo">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario->nombre) ?>" placeholder="Escribe tu nombre" maxlength="100" required>
                    </div>

                    <div class="campo">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($usuario->apellidos) ?>" placeholder="Escribe tus apellidos" maxlength="100" required>
                    </div>

                    <div class="campo">
                        <label for="area">Área de trabajo</label>
                        <select id="area" name="area" required>
                            <?php foreach (AREAS as $clave => $texto): ?>
                                <option value="<?= htmlspecialchars($clave) ?>" <?= $usuario->area === $clave ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($texto) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo">
                        <label for="contacto">Correo electrónico de contacto</label>
                        <input type="email" id="contacto" name="contacto" value="<?= htmlspecialchars($usuario->email) ?>" placeholder="Escribe tu correo electrónico" maxlength="100" required>
                    </div>

                    <div class="campo">
                        <label for="antiguedad">Antigüedad</label>
                        <input type="number" id="antiguedad" name="antiguedad" value="<?= $usuario->antiguedad ?>" min="0" max="60" required>
                    </div>

                    <div class="botones">
                        <button type="submit" class="botonGuardar">
                            Guardar cambios
                        </button>

                        <button type="reset" class="botonBorrar">
                            Restablecer
                        </button>

                        <button type="button" class="botonCancelar" onclick="cancelarEdicion()">
                            Cancelar
                        </button>
                    </div>

                </form>
            </section>

        </section>
    <?php endif; ?>

</main>

<?php require __DIR__ . '/layout/footer.php'; ?>
