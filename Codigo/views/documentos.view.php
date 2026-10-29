<?php
$titulo = 'Documentos';
$css = 'documentos-estilo.css';
$scripts = 'documentos.script.js';

$esAdmin = isset($_SESSION['usuario']) && $_SESSION['usuario']->tipo === 'admin';

require __DIR__ . '/layout/head.php';
require __DIR__ . '/layout/nav.php';
?>
</nav>

<main>

    <div class="cabeceraDocumentos">
        <div class="tituloDocumentos">
            <h3>Mis documentos</h3>
            <p>Consulta y comparte documentación técnica con el equipo</p>
        </div>

        <div class="contenedorSubirDocumento">
            <button type="button" id="abrirFormularioDocumento" class="publicarDocumento" aria-expanded="false" aria-controls="formularioSubidaDocumento">
                <span aria-hidden="true">+</span>
                Subir documento
            </button>

            <form id="formularioSubidaDocumento" class="formularioNuevoDocumento" action="index.php?controller=DocumentosController&amp;accion=store" method="POST" enctype="multipart/form-data" hidden>
                <h4>Subir documento</h4>

                <div class="campoDocumento">
                    <label for="tituloDocumento">Nombre del documento</label>
                    <input type="text" id="tituloDocumento" name="titulo" maxlength="255" required>
                </div>

                <div class="campoDocumento">
                    <label for="temaDocumento">Categoría</label>
                    <select id="temaDocumento" name="tema" required>
                        <option value="">Selecciona una categoría</option>
                        <option value="Ingeniería y Diseño">Ingeniería y Diseño</option>
                        <option value="Normativa y Calidad">Normativa y Calidad</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Sostenibilidad e innovación">Sostenibilidad e innovación</option>
                        <option value="Formación y Recursos">Formación y Recursos</option>
                    </select>
                </div>

                <div class="campoDocumento">
                    <label for="archivoDocumento">Archivo</label>
                    <input type="file" id="archivoDocumento" name="archivo" accept=".pdf,.txt,.jpg,.jpeg,.png,.webp,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                    <small>PDF, Word, Excel, PowerPoint, TXT o imagen. Máximo 10 MB.</small>
                </div>

                <div class="accionesDocumento">
                    <button type="submit" class="btnConfirmarDocumento">Guardar documento</button>
                    <button type="button" id="cancelarSubidaDocumento" class="btnLimpiarDocumento">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($errorDocumentos)): ?>
    <p class="errorDocumentos" role="alert"><?= htmlspecialchars($errorDocumentos) ?></p>
    <?php endif; ?>

    <div class="contenidoDocumentos">

        <aside>
            <div class="filtrarTema">
                <h5>Filtrar por Tema</h5>
                <?php $temaActual = $temaActual ?? 'todas'; ?>

                <section data-id="todas" class="tema" <?= $temaActual === 'todas' ? 'id="seleccionado"' : '' ?>>
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.25 6.66667H5.41667C6.10833 6.66667 6.66667 6.10833 6.66667 5.41667V1.25C6.66667 0.558333 6.10833 0 5.41667 0H1.25C0.558333 0 0 0.558333 0 1.25V5.41667C0 6.10833 0.558333 6.66667 1.25 6.66667ZM1.66667 1.66667H5V5H1.66667V1.66667ZM13.75 0H9.58333C8.89167 0 8.33333 0.558333 8.33333 1.25V5.41667C8.33333 6.10833 8.89167 6.66667 9.58333 6.66667H13.75C14.4417 6.66667 15 6.10833 15 5.41667V1.25C15 0.558333 14.4417 0 13.75 0ZM13.3333 5H10V1.66667H13.3333V5ZM1.25 15H5.41667C6.10833 15 6.66667 14.4417 6.66667 13.75V9.58333C6.66667 8.89167 6.10833 8.33333 5.41667 8.33333H1.25C0.558333 8.33333 0 8.89167 0 9.58333V13.75C0 14.4417 0.558333 15 1.25 15ZM1.66667 10H5V13.3333H1.66667V10ZM13.75 8.33333H9.58333C8.89167 8.33333 8.33333 8.89167 8.33333 9.58333V13.75C8.33333 14.4417 8.89167 15 9.58333 15H13.75C14.4417 15 15 14.4417 15 13.75V9.58333C15 8.89167 14.4417 8.33333 13.75 8.33333ZM13.3333 13.3333H10V10H13.3333V13.3333Z" fill="#757575"/>
                    </svg>
                    <p>Todas las categorías</p>
                    <p class="contador"><?= $preguntasPorTema -> total_todas ?></p>
                </section>

                <section data-id="Ingeniería y Diseño" class="tema" <?= $temaActual === 'Ingeniería y Diseño' ? 'id="seleccionado"' : '' ?>>
                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.3716 1.59532L2.11707 11.8504C1.88309 12.0843 1.72359 12.3823 1.65869 12.7068L1.00003 16L4.2925 15.3414C4.61696 15.2765 4.91496 15.117 5.14893 14.883L15.4051 4.62793C15.7927 4.22271 16.0062 3.68185 15.9999 3.12116C15.9936 2.56048 15.768 2.02454 15.3715 1.6281C14.9749 1.23167 14.4389 1.00626 13.8782 1.00013C13.3175 0.994001 12.7767 1.20765 12.3716 1.59532Z" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.6369 3.84949L13.1509 6.36339" stroke="#757575" stroke-width="2"/>
                    </svg>
                    <p>Ingeniería y Diseño</p>
                    <p class="contador"><?= $preguntasPorTema -> total_ingenieria ?></p>
                </section>
                <section data-id="Normativa y Calidad" class="tema" <?= $temaActual === 'Normativa y Calidad' ? 'id="seleccionado"' : '' ?>>
                    <svg width="17" height="19" viewBox="0 0 17 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 16.3V2.7C1 2.24913 1.19754 1.81673 1.54917 1.49792C1.90081 1.17911 2.37772 1 2.875 1H9.9625C10.4524 1.00006 10.9228 1.17396 11.2731 1.4845L15.4356 5.1752C15.6143 5.33351 15.7562 5.52258 15.8531 5.73131C15.95 5.94004 16 6.16423 16 6.3907V16.3C16 16.7509 15.8025 17.1833 15.4508 17.5021C15.0992 17.8209 14.6223 18 14.125 18H2.875C2.37772 18 1.90081 17.8209 1.54917 17.5021C1.19754 17.1833 1 16.7509 1 16.3Z" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.375 1V4.4C10.375 4.85087 10.5725 5.28327 10.9242 5.60208C11.2758 5.92089 11.7527 6.1 12.25 6.1H16" stroke="#757575" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                    <p>Normativa y Calidad</p>
                    <p class="contador"><?= $preguntasPorTema -> total_normativa ?></p>
                </section>
                <section data-id="Mantenimiento" class="tema" <?= $temaActual === 'Mantenimiento' ? 'id="seleccionado"' : '' ?>>
                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.41672 7.4168L3.25061 3.25116" stroke="#757575" stroke-width="1.5"/>
                        <path d="M2.41741 4.50088L4.50047 2.41806L2.0008 1.16836L1.16758 2.00149L2.41741 4.50088ZM14.8949 5.72974C15.295 5.33011 15.5702 4.8228 15.687 4.26958C15.8039 3.71636 15.7574 3.1411 15.5532 2.61384L14.3683 3.79855H12.7019V2.13229L13.8867 0.947584C13.3594 0.742919 12.7839 0.696031 12.2303 0.812632C11.6768 0.929233 11.1691 1.20429 10.7692 1.60429C10.3692 2.00429 10.0942 2.51197 9.97772 3.06547C9.86123 3.61897 9.90825 4.1944 10.1131 4.72166L4.72211 10.1128C4.19479 9.90805 3.6193 9.86104 3.06573 9.97752C2.51217 10.094 2.00443 10.3689 1.60439 10.7689C1.20434 11.1688 0.929253 11.6764 0.812639 12.2299C0.696024 12.7833 0.742918 13.3588 0.947607 13.8861L2.13162 12.7014H3.79806V14.3676L2.61322 15.5523C3.14045 15.757 3.71581 15.804 4.26925 15.6875C4.8227 15.571 5.33033 15.2962 5.73033 14.8964C6.13033 14.4966 6.40545 13.9892 6.52216 13.4358C6.63888 12.8825 6.59217 12.3072 6.38772 11.7799L11.7803 6.38792C12.3074 6.59177 12.8824 6.63808 13.4354 6.52124C13.9883 6.40439 14.4954 6.12941 14.8949 5.72974Z" stroke="#757575" stroke-width="1.5" stroke-linejoin="round"/>
                        <path d="M8.08081 10.3328L12.7502 15.0016C12.8547 15.1063 12.9788 15.1893 13.1154 15.246C13.252 15.3026 13.3984 15.3318 13.5463 15.3318C13.6942 15.3318 13.8407 15.3026 13.9773 15.246C14.1139 15.1893 14.238 15.1063 14.3425 15.0016L15.0024 14.3418C15.1071 14.2373 15.1901 14.1132 15.2468 13.9766C15.3034 13.84 15.3326 13.6936 15.3326 13.5457C15.3326 13.3978 15.3034 13.2514 15.2468 13.1148C15.1901 12.9782 15.1071 12.8541 15.0024 12.7497L10.333 8.08081" stroke="#757575" stroke-width="1.5" stroke-linejoin="round"/>
                    </svg>
                    <p>Mantenimiento</p>
                    <p class="contador"><?= $preguntasPorTema -> total_mantenimiento ?></p>
                </section>
                <section data-id="Sostenibilidad e innovación" class="tema" <?= $temaActual === 'Sostenibilidad e innovación' ? 'id="seleccionado"' : '' ?>>
                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.97343 14.0264C10.5976 18.6019 16.6979 12.5016 15.9355 1.06429C4.49826 0.301874 -1.60212 6.40226 2.97343 14.0264ZM2.97343 14.0264L1 15.9998M2.97343 14.0285L7.10038 9.89944" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p>Sostenibilidad e innovación</p>
                    <p class="contador"><?= $preguntasPorTema -> total_sostenibilidad ?></p>
                </section>
                <section data-id="Formación y Recursos" class="tema" <?= $temaActual === 'Formación y Recursos' ? 'id="seleccionado"' : '' ?>>
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.6 4.31548L8.17143 0.0979131C8.07266 0.0335133 7.96386 0 7.85357 0C7.74328 0 7.63448 0.0335133 7.53571 0.0979131L3.25 2.90963L1.10714 4.31548L0.392857 4.7841C0.275061 4.86199 0.176002 4.98115 0.106659 5.12838C0.0373169 5.27562 0.00039979 5.44517 0 5.61825V11.2417H1.42857V6.19933L2.85714 7.13657V10.7262C2.85714 12.6569 5.08571 15 7.85714 15C10.6286 15 12.8571 12.6663 12.8571 10.7262V7.13657L14.6071 5.99314C14.85 5.83381 15 5.50578 15 5.159C15 4.81222 14.85 4.48419 14.6071 4.32486L14.6 4.31548ZM3.88571 4.58728L7.85 1.98176L12.6786 5.14963L7.85 8.31749L3.02143 5.14963L3.88571 4.57791V4.58728ZM11.4214 10.7262C11.4214 11.4385 9.91429 13.1255 7.85 13.1255C5.78571 13.1255 4.27857 11.4479 4.27857 10.7262V8.07381L7.52857 10.2107C7.62857 10.2763 7.73571 10.3138 7.85 10.3138C7.96429 10.3138 8.07143 10.2763 8.17143 10.2107L11.4214 8.07381V10.7262Z" fill="#757575"/>
                    </svg>
                    <p>Formación y Recursos</p>
                    <p class="contador"><?= $preguntasPorTema -> total_formacion ?></p>
                </section>
            </div>
        </aside>


        <div class="der">
            <h4 class="tituloRecientes">Documentos recientes</h4>

            <div class="tablaDocumentos">
                <div class="filaDocumento cabeceraTabla">
                    <span></span>
                    <span>Nombre de documento</span>
                    <span>Categoría</span>
                    <span>Autor</span>
                    <span>Fecha</span>
                    <span></span>
                </div>

                <p class="mensajeVacio" <?= !empty($documentos) ? 'hidden' : '' ?>>
                    No hay documentos en este tema.
                </p>

                <?php foreach ($documentos as $documento): ?>
                <div class="filaDocumento">
                    <img class="iconoDocumento" src="views/imagenes/documento.svg" alt="Documento" height="20">

                    <span class="tituloDoc"><?= htmlspecialchars($documento->documento_titulo) ?></span>

                    <span class="celdaCategoria">
                        <span class="etiquetaCategoria"><?= htmlspecialchars($documento->documento_tema) ?></span>
                    </span>

                    <span class="autorDoc"><?= htmlspecialchars($documento->autor_nombre) ?></span>
                    <span class="fechaDoc"><?= !empty($documento->documento_fecha) ? date('d/m/Y', strtotime($documento->documento_fecha)) : '' ?></span>

                    <a class="btnDescargar" href="<?= htmlspecialchars($documento->documento_ruta) ?>" download="<?= htmlspecialchars($documento->documento_nombre) ?>" title="Descargar">
                        <img src="views/imagenes/download.svg" alt="Descargar" height="20">
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</main>
<?php require __DIR__ . '/layout/footer.php'; ?>