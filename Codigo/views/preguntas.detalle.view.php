<?php
$titulo = 'Detalle de pregunta';
$css = 'preguntas.detalle-estilo.css';
$scripts = 'preguntas.view.script.js';
require __DIR__ . '/layout/head.php';

$h = static fn($valor) => htmlspecialchars(($valor ?? ''));
$nombreCompleto = static function ($obj) {
    return trim(($obj->autor_nombre ?? '') . ' ' . ($obj->autor_apellidos ?? '')) ?: 'Usuario';
};

$porPadre = [];
foreach ($respuestas as $respuesta) {
    $padre = ($respuesta->idRespuesta ?? 0);
    $porPadre[$padre][] = $respuesta;
}

$archivosPorRespuesta = [];
foreach ($archivosRespuestas as $archivo) {
    $archivosPorRespuesta[$archivo->idRespuesta][] = $archivo;
}

$dibujarRespuestas = function ($idPadre = 0, $nivel = 0) use (&$dibujarRespuestas, $porPadre, $archivosPorRespuesta, $h, $nombreCompleto, $pregunta, $idUsuario, $esAdmin, $respuestaActiva) {
    if (empty($porPadre[$idPadre])) return;
    foreach ($porPadre[$idPadre] as $respuesta) {
        $esPropia = $respuesta->idUsuario === $idUsuario;
        $puedeEliminar = $esPropia || $esAdmin;
        $clase = $nivel > 0 ? 'respuesta respuestaAnidada' : 'respuesta';
    ?>


        <article class="<?= $clase ?>" id="respuesta-<?= $respuesta->id ?>">
            <div class="textoRespuesta">
                <h3><a class="enlacePerfil" href="index.php?controller=UsuarioController&accion=show&id=<?= $respuesta->idUsuario ?>"><?= $h($nombreCompleto($respuesta)) ?></a></h3>
                
                <p><?= nl2br($h($respuesta->texto)) ?></p>

                <?php if (!empty($archivosPorRespuesta[$respuesta->id])): ?>
                    <div class="archivosAdjuntos">
                        <h4>Archivos adjuntos</h4>

                        <?php foreach ($archivosPorRespuesta[$respuesta->id] as $archivo): ?>
                            <a class="archivoAdjunto" href="<?= htmlspecialchars($archivo->ruta, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" download="<?= htmlspecialchars($archivo->nombreOriginal, ENT_QUOTES, 'UTF-8') ?>">
                                <span class="iconoArchivo">📎</span>
                                <span class="nombreArchivo">
                                    <?= $h($archivo->nombreOriginal) ?>
                                </span>
                                <span class="descargarArchivo">Descargar</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
            <div class="accionesRespuesta">
                <button class="accionVoto votoArriba <?= ($respuesta->miVoto ?? 0) === 1 ? 'votoSeleccionado' : '' ?>" type="button" data-id-respuesta="<?= $respuesta->id ?>" data-voto="1" title="Votar a favor">
                    <img src="views/imagenes/<?= ($respuesta->miVoto ?? 0) === 1 ? 'FlechaArribaRellena.png' : 'FlechaArriba.svg' ?>" alt="Voto positivo">
                </button>
                <span class="contadorVotos" aria-live="polite"><?= ($respuesta->totalVotos ?? 0) ?></span>
                <button class="accionVoto votoAbajo <?= ($respuesta->miVoto ?? 0) === -1 ? 'votoSeleccionado' : '' ?>" type="button" data-id-respuesta="<?= $respuesta->id ?>" data-voto="-1" title="Votar en contra">
                    <img src="views/imagenes/<?= ($respuesta->miVoto ?? 0) === -1 ? 'FlechaAbajoRellena.png' : 'FlechaAbajo.svg' ?>" alt="Voto negativo">
                </button>
                <form action="index.php" method="POST" class="formAccion">
                    <input type="hidden" name="controller" value="PreguntasController">
                    <input type="hidden" name="accion" value="detalle">
                    <input type="hidden" name="idPregunta" value="<?= $pregunta->id ?>">
                    <input type="hidden" name="responderA" value="<?= $respuesta->id ?>">
                    <button type="submit" aria-label="Responder" title="Responder"><img src="views/imagenes/Responder.svg" alt="Responder"></button>
                </form>
                <?php if ($puedeEliminar): ?>
                    <form action="index.php" method="POST" class="formAccion" onsubmit="return confirm('¿Quieres eliminar esta respuesta?');">
                        <input type="hidden" name="controller" value="PreguntasController">
                        <input type="hidden" name="accion" value="eliminarRespuesta">
                        <input type="hidden" name="idPregunta" value="<?= $pregunta->id ?>">
                        <input type="hidden" name="idRespuesta" value="<?= $respuesta->id ?>">
                        <button class="eliminarRespuesta" type="submit" aria-label="Eliminar respuesta" title="Eliminar respuesta"><img src="views/imagenes/Papelera.svg" alt="Eliminar"></button>
                    </form>
                <?php endif; ?>
            </div>
        </article>
        <?php if ($respuestaActiva === $respuesta->id): ?>
            <form class="formularioRespuesta" action="index.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="controller" value="PreguntasController">
                <input type="hidden" name="accion" value="responder">
                <input type="hidden" name="idPregunta" value="<?= $pregunta->id ?>">

                <textarea name="respuesta" placeholder="Escribe tu respuesta aquí..." required></textarea>

                <div class="accionesFormularioRespuesta">
                    <label class="botonAdjuntar" title="Adjuntar archivos">
                        <img src="views/imagenes/Subir.svg" alt="adjuntar">
                        <span>Adjuntar archivos</span>
                        <input type="file" name="archivos[]" multiple accept=".pdf,.png,.jpg,.jpeg,.webp,.txt,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                    </label>

                    <button type="submit" title="Enviar respuesta">
                        <img src="views/imagenes/Enviar.svg" alt="Enviar">
                    </button>
                </div>
            </form>

        <?php endif; ?>
        <?php
        $dibujarRespuestas($respuesta->id, $nivel + 1);
    }
};
?>

<?php require __DIR__ . '/layout/nav.php'; ?>
</nav>

<main class="contenido detallePregunta">
    <section class="publicacion">
        <div class="informacionPublicacion">
            <h2><?= $h($pregunta->titulo) ?></h2>
            <p class="autorPublicacion"><a class="enlacePerfil" href="index.php?controller=UsuarioController&accion=show&id=<?= $pregunta->autor_id ?>"><?= $h(trim($pregunta->autor_nombre . ' ' . ($pregunta->autor_apellidos ?? ''))) ?></a></p>
            <p class="temaPublicacion"><?= $h($pregunta->tema) ?></p>
        </div>
        <?php if (!empty($pregunta->imagen)): ?>
            <div class="imagenPublicacion">
                <img src="<?= htmlspecialchars($pregunta->imagen) ?>"
                     alt="Imagen adjunta a la pregunta"
                     class="imagenPreguntaDetalle">
            </div>
        <?php endif; ?>
        <p class="descripcionPublicacion"><?= nl2br($h($pregunta->descripcion)) ?></p>
    </section>

    <?php if (!empty($errorRespuesta)): ?><p class="errorRespuesta"><?= $h($errorRespuesta) ?></p><?php endif; ?>

    <form class="formularioRespuesta formularioAnidado" action="index.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="controller" value="PreguntasController">
        <input type="hidden" name="accion" value="responder">
        <input type="hidden" name="idPregunta" value="<?= $pregunta->id ?>">
        <input type="hidden" name="idRespuesta" value="<?= $respuesta->id ?>">
        <textarea name="respuesta" placeholder="Escribe tu respuesta aquí..." required></textarea>
            <div class="accionesFormularioRespuesta">
            <label class="botonAdjuntar" title="Adjuntar archivos">
                <img src="views/imagenes/Subir.svg" alt="">
                <span>Adjuntar archivos</span>
                <input type="file" name="archivos[]" multiple accept=".pdf,.png,.jpg,.jpeg,.webp,.txt,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
            </label>

            <button type="submit" title="Enviar respuesta">
                <img src="views/imagenes/Enviar.svg" alt="Enviar">
            </button>
        </div>
    </form>

    <section class="listaRespuestas" aria-label="Respuestas">
        <?php if (empty($respuestas)): ?>
            <p class="sinRespuestas">Todavía no hay respuestas. ¡Sé la primera persona en responder!</p>
        <?php else: ?>
            <?php $dibujarRespuestas(0, 0); ?>
        <?php endif; ?>
    </section>
</main>
<?php require __DIR__ . '/layout/footer.php'; ?>