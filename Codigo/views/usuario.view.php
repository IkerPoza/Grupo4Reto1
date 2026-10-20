<?php
$titulo = 'Perfil';
$css = ['../estilos/usuario-estilo.css'];
$scripts = ['../scripts/usuario.script.js'];
require __DIR__ . '/layout/head.php';
require __DIR__ . '/layout/nav.php';
?>

    <nav class="navegacion">
        <a href="index.php">
            <svg width="30" height="19" viewBox="0 0 23 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.39156 0.54592C9.39176 0.432169 9.32391 0.321233 9.19752 0.228636C9.07113 0.136039 8.89252 0.0664156 8.68669 0.0295081C8.48085 -0.00739933 8.25809 -0.00974339 8.04958 0.022804C7.84107 0.0553514 7.65725 0.121161 7.52383 0.211024L0.219844 5.11992C0.077359 5.21566 0 5.33351 0 5.45481C0 5.57612 0.077359 5.69396 0.219844 5.78971L7.52383 10.6986C7.65725 10.7885 7.84107 10.8543 8.04958 10.8868C8.25809 10.9194 8.48085 10.917 8.68669 10.8801C8.89252 10.8432 9.07113 10.7736 9.19752 10.681C9.32391 10.5884 9.39176 10.4775 9.39156 10.3637V8.18743C15.0042 8.24852 17.7004 8.80541 19.0547 9.41084C20.3423 9.98627 20.5781 10.6631 20.8233 11.3722L20.887 11.5549C20.9338 11.6857 21.0704 11.803 21.2711 11.8847C21.4718 11.9665 21.7228 12.0071 21.9767 11.999C22.2307 11.9908 22.4702 11.9344 22.6502 11.8404C22.8302 11.7463 22.9382 11.6211 22.954 11.4884C23.1324 9.98954 22.8643 7.81763 20.839 6.00079C18.8732 4.23741 15.3339 2.88146 9.39156 2.73965V0.54592Z" fill="#5B6F8F"/>
            </svg>
        </a>
    </nav>


    <main>

        <?php if ($esPropio): ?>
            <form method="post" action="<?= url('AuthController', 'logout') ?>">
                <button type="submit" class="cerrarSesion" title="Cerrar sesión">
                    <img src="views/imagenes/CerrarSesion.png" alt="Cerrar sesión">
                </button>
            </form>
        <?php endif; ?>

        <section id="vistaPerfil">
            <section class="perfil tarjetaPerfil" id="perfil">

                <div class="imagenPerfil">
                    <img src="imagenes/ImagenVacia.png" alt="Foto de perfil" class="fotoPerfil">
                </div>

                <div class="datosPerfil">
                    <div class="identidad">
                        <h2 id="nombreVisible"><?= $usuario->nombre . ' ' . $usuario->apellidos ?></h2>
                        <p id="areaVisible"><?= nombreArea($usuario->area) ?></p>
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
                            <strong><?= (int)$stats->respuestas ?></strong>
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
                                <form method="POST" action="index.php?controller=UsuarioController&accion=destroy" onsubmit="return confirm('¿Seguro que quieres eliminar este perfil?');">

                                    <input type="hidden" name="id" value="<?= $usuario->id ?>">

                                    <button type="submit" class="eliminarPerfil"> Eliminar perfil </button>
                                </form>
                            <?php endif; ?>

                            <?php if ($esPropio): ?>
                                <button type="button" class="editarPerfil" onclick="mostrarEdicion()"> Editar perfil </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>

            </section>

            <nav class="pestanas">
                <button type="button" class="pestana activa" id="botonPreguntas" aria-pressed="true" onclick="mostrarPreguntas()"> Preguntas</button>

                <?php if ($esPropio): ?>
                    <button type="button" class="pestana" id="botonFavoritas" aria-pressed="false" onclick="mostrarFavoritas()"> Favoritas </button>
                <?php endif; ?>            
            </nav>

            <section id="listaPreguntas" class="listaPreguntas">
                <?php if (!$preguntas): ?>
                    <p class="mensajeVacio">Todavía no ha publicado ninguna pregunta.</p>
                <?php endif; ?>
                <?php foreach ($preguntas as $p) { 
                    $tarjeta($p, (bool)$p->guardada); 
                } ?>
            </section>


            <section id="listaFavoritas" class="listaPreguntas" hidden>
                <p id="sinFavoritas" class="mensajeVacio" <?= $guardadas ? 'hidden' : '' ?>> Todavía no has añadido preguntas a favoritos.</p>
                <?php foreach ($guardadas as $p) { 
                    $tarjeta($p, true); 
                } ?>
            </section>
        </section>

        <?php if ($esPropio): ?>
        <section id="vistaEdicion" hidden>
            <div class="encabezadoEdicion">
                <h2>Editar mi perfil</h2>
                <p>Actualiza tu información personal y profesional.</p>
            </div>

            <section class="formularioPerfil">
                <div class="imagenEdicion">
                    <img src="views/imagenes/ImagenVacia.png" alt="Foto de perfil" class="fotoPerfil">
                    <p><b>Foto de perfil</b></p>
                    <p>Podrás añadir una imagen más adelante.</p>
                </div>
                <form id="formPerfil" action="<?= url('UsuarioController', 'update') ?>" method="POST">
                    <div class="campo">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="<?= $usuario->nombre ?>" placeholder="Escribe tu nombre" maxlength="100" required>
                    </div>
                    <div class="campo">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" id="apellidos" name="apellidos" value="<?= $usuario->apellidos ?>" placeholder="Escribe tus apellidos" maxlength="100" required>
                    </div>
                    <div class="campo">
                        <label for="area">Área de trabajo</label>
                        <select id="area" name="area" required>
                            <?php foreach (AREAS as $clave => $texto): ?>
                                <option value="<?= $clave ?>" <?= $usuario->area === $clave ? 'selected' : '' ?>><?= $texto ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label for="contacto">Correo electrónico de contacto</label>
                        <input type="email" id="contacto" name="contacto" value="<?= $usuario->contacto ?>" placeholder="Escribe tu correo electrónico" maxlength="100" required>
                    </div>
                    <div class="campo">
                        <label for="antiguedad">Antigüedad (años)</label>
                        <input type="number" id="antiguedad" name="antiguedad" value="<?= $usuario->antiguedad ?>" min="0" max="60" required>
                    </div>
                    <div class="botones">
                        <button type="submit" class="botonGuardar">Guardar cambios</button>
                        <button type="reset" class="botonBorrar">Restablecer</button>
                        <button type="button" class="botonCancelar" onclick="cancelarEdicion()"> Cancelar </button>
                    </div>
                </form>
            </section>

        </section>
        <?php endif; ?>
    </main>
    
<?php require __DIR__ . '/layout/footer.php'; ?>