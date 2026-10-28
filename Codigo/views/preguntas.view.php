<?php
$titulo = 'Preguntas';
$css = 'preguntas-estilo.css';
$scripts = 'preguntas.view.script.js';

$esAdmin = isset($_SESSION['usuario']) && $_SESSION['usuario']->tipo === 'admin';

require __DIR__ . '/layout/head.php';
?>

<main>
    <!--boton lista usuarios !-->
    <?php if ($esAdmin): ?>
    <a href="index.php?controller=ListarUsuariosController&accion=index" class="btnListarUsuarios" title="ListarUsuarios">
        <img src="views/imagenes/ListarUsuarios.png" alt="ListarUsuarios" width="auto" height="30">
    </a>
    <?php endif; ?>

    <div class="buscador">
        <form action="index.php" method="POST">
            <input class="busqueda" name="busqueda" type="text" placeholder="Buscar preguntas por titulo, descripcion..." value="<?= htmlspecialchars($busqueda ?? '') ?>">
            <label class="buscar">
                <input name="accion" value="buscar" type="submit" hidden>
                <svg width="15" height="15" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.2" d="M9.63417 5.195C9.63417 6.37311 9.16616 7.50297 8.33311 8.33602C7.50006 9.16908 6.3702 9.63708 5.19208 9.63708C4.01397 9.63708 2.88411 9.16908 2.05106 8.33602C1.218 7.50297 0.75 6.37311 0.75 5.195C0.75 4.01766 1.2177 2.88854 2.0502 2.05603C2.88271 1.22353 4.01183 0.755829 5.18917 0.755829C6.36651 0.755829 7.49563 1.22353 8.32813 2.05603C9.16064 2.88854 9.62833 4.01766 9.62833 5.195" fill="black"/>
                    <path d="M8.3975 8.3275L11.7225 11.6525M9.63416 5.195C9.63417 5.77818 9.51911 6.35563 9.29559 6.89426C9.07206 7.4329 8.74446 7.92215 8.33155 8.33397C7.91864 8.7458 7.42854 9.07211 6.88931 9.29422C6.35009 9.51633 5.77234 9.62987 5.18917 9.62834C4.31093 9.62833 3.45243 9.36783 2.72229 8.87979C1.99215 8.39175 1.42317 7.69811 1.08735 6.88661C0.751537 6.07512 0.663968 5.18226 0.835727 4.32098C1.00749 3.45971 1.43086 2.66874 2.05227 2.04814C2.67368 1.42755 3.46521 1.00522 4.32671 0.834589C5.18821 0.663962 6.08096 0.752705 6.89201 1.08959C7.70306 1.42647 8.39596 1.99636 8.88303 2.72715C9.37011 3.45793 9.62949 4.31677 9.62833 5.195" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </label>
        </form>
    </div>

    <div class="contenidoPreguntas">
        <aside>
            <form action="index.php" method="POST">
                <input type="hidden" name="controller" value="CrearPreguntaController">
                <label class="publicarPregunta">
                    <input name="publicar" type="submit" hidden>
                    <svg width="15" height="15" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.5 16V8.5M8.5 8.5V1M8.5 8.5H16M8.5 8.5H1" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Publicar pregunta
                </label>
            </form>
            <div class="filtrarTema">
                <h5>Filtrar por Tema</h5>
                <?php $temaActual = $temaActual ?? 'todas'; ?>
                <section data-id="favoritas" class="tema" <?= $temaActual === 'favoritas' ? 'id="seleccionado"' : '' ?>>
                    <svg width="13" height="18" viewBox="0 0 13 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 3.8C1 2.82 1 2.33 1.19075 1.9555C1.35853 1.62623 1.62623 1.35853 1.9555 1.19075C2.33 1 2.82 1 3.8 1H8.7C9.68 1 10.17 1 10.5445 1.19075C10.8738 1.35853 11.1415 1.62623 11.3092 1.9555C11.5 2.33 11.5 2.82 11.5 3.8V15.4419C11.5 15.8671 11.5 16.0798 11.4116 16.1961C11.3734 16.2467 11.3247 16.2884 11.2688 16.3184C11.2129 16.3483 11.1512 16.3657 11.0879 16.3694C10.9417 16.3781 10.765 16.26 10.4115 16.0246L6.25 13.25L2.0885 16.0238C1.735 16.26 1.55825 16.3781 1.41125 16.3694C1.34811 16.3656 1.28654 16.3481 1.2308 16.3182C1.17506 16.2883 1.12647 16.2466 1.08837 16.1961C1 16.0798 1 15.8671 1 15.4419V3.8Z" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p>Mis Favoritos</p>
                    <p><?= $preguntasPorTema -> total_favoritos ?></p>
                </section>
                <section data-id="todas" class="tema" <?= $temaActual === 'todas' ? 'id="seleccionado"' : '' ?>>
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.25 6.66667H5.41667C6.10833 6.66667 6.66667 6.10833 6.66667 5.41667V1.25C6.66667 0.558333 6.10833 0 5.41667 0H1.25C0.558333 0 0 0.558333 0 1.25V5.41667C0 6.10833 0.558333 6.66667 1.25 6.66667ZM1.66667 1.66667H5V5H1.66667V1.66667ZM13.75 0H9.58333C8.89167 0 8.33333 0.558333 8.33333 1.25V5.41667C8.33333 6.10833 8.89167 6.66667 9.58333 6.66667H13.75C14.4417 6.66667 15 6.10833 15 5.41667V1.25C15 0.558333 14.4417 0 13.75 0ZM13.3333 5H10V1.66667H13.3333V5ZM1.25 15H5.41667C6.10833 15 6.66667 14.4417 6.66667 13.75V9.58333C6.66667 8.89167 6.10833 8.33333 5.41667 8.33333H1.25C0.558333 8.33333 0 8.89167 0 9.58333V13.75C0 14.4417 0.558333 15 1.25 15ZM1.66667 10H5V13.3333H1.66667V10ZM13.75 8.33333H9.58333C8.89167 8.33333 8.33333 8.89167 8.33333 9.58333V13.75C8.33333 14.4417 8.89167 15 9.58333 15H13.75C14.4417 15 15 14.4417 15 13.75V9.58333C15 8.89167 14.4417 8.33333 13.75 8.33333ZM13.3333 13.3333H10V10H13.3333V13.3333Z" fill="#757575"/>
                    </svg>
                    <p>Todas las categorías</p>
                    <p><?= $preguntasPorTema -> total_todas ?></p>
                </section>
                <section data-id="Ingeniería y Diseño" class="tema" <?= $temaActual === 'Ingeniería y Diseño' ? 'id="seleccionado"' : '' ?>>
                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.3716 1.59532L2.11707 11.8504C1.88309 12.0843 1.72359 12.3823 1.65869 12.7068L1.00003 16L4.2925 15.3414C4.61696 15.2765 4.91496 15.117 5.14893 14.883L15.4051 4.62793C15.7927 4.22271 16.0062 3.68185 15.9999 3.12116C15.9936 2.56048 15.768 2.02454 15.3715 1.6281C14.9749 1.23167 14.4389 1.00626 13.8782 1.00013C13.3175 0.994001 12.7767 1.20765 12.3716 1.59532Z" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.6369 3.84949L13.1509 6.36339" stroke="#757575" stroke-width="2"/>
                    </svg>
                    <p>Ingeniería y Diseño</p>
                    <p><?= $preguntasPorTema -> total_ingenieria ?></p>
                </section>
                <section data-id="Normativa y Calidad" class="tema" <?= $temaActual === 'Normativa y Calidad' ? 'id="seleccionado"' : '' ?>>
                    <svg width="17" height="19" viewBox="0 0 17 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 16.3V2.7C1 2.24913 1.19754 1.81673 1.54917 1.49792C1.90081 1.17911 2.37772 1 2.875 1H9.9625C10.4524 1.00006 10.9228 1.17396 11.2731 1.4845L15.4356 5.1752C15.6143 5.33351 15.7562 5.52258 15.8531 5.73131C15.95 5.94004 16 6.16423 16 6.3907V16.3C16 16.7509 15.8025 17.1833 15.4508 17.5021C15.0992 17.8209 14.6223 18 14.125 18H2.875C2.37772 18 1.90081 17.8209 1.54917 17.5021C1.19754 17.1833 1 16.7509 1 16.3Z" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.375 1V4.4C10.375 4.85087 10.5725 5.28327 10.9242 5.60208C11.2758 5.92089 11.7527 6.1 12.25 6.1H16" stroke="#757575" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                    <p>Normativa y Calidad</p>
                    <p><?= $preguntasPorTema -> total_normativa ?></p>
                </section>
                <section data-id="Mantenimiento" class="tema" <?= $temaActual === 'Mantenimiento' ? 'id="seleccionado"' : '' ?>>
                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.41672 7.4168L3.25061 3.25116" stroke="#757575" stroke-width="1.5"/>
                        <path d="M2.41741 4.50088L4.50047 2.41806L2.0008 1.16836L1.16758 2.00149L2.41741 4.50088ZM14.8949 5.72974C15.295 5.33011 15.5702 4.8228 15.687 4.26958C15.8039 3.71636 15.7574 3.1411 15.5532 2.61384L14.3683 3.79855H12.7019V2.13229L13.8867 0.947584C13.3594 0.742919 12.7839 0.696031 12.2303 0.812632C11.6768 0.929233 11.1691 1.20429 10.7692 1.60429C10.3692 2.00429 10.0942 2.51197 9.97772 3.06547C9.86123 3.61897 9.90825 4.1944 10.1131 4.72166L4.72211 10.1128C4.19479 9.90805 3.6193 9.86104 3.06573 9.97752C2.51217 10.094 2.00443 10.3689 1.60439 10.7689C1.20434 11.1688 0.929253 11.6764 0.812639 12.2299C0.696024 12.7833 0.742918 13.3588 0.947607 13.8861L2.13162 12.7014H3.79806V14.3676L2.61322 15.5523C3.14045 15.757 3.71581 15.804 4.26925 15.6875C4.8227 15.571 5.33033 15.2962 5.73033 14.8964C6.13033 14.4966 6.40545 13.9892 6.52216 13.4358C6.63888 12.8825 6.59217 12.3072 6.38772 11.7799L11.7803 6.38792C12.3074 6.59177 12.8824 6.63808 13.4354 6.52124C13.9883 6.40439 14.4954 6.12941 14.8949 5.72974Z" stroke="#757575" stroke-width="1.5" stroke-linejoin="round"/>
                        <path d="M8.08081 10.3328L12.7502 15.0016C12.8547 15.1063 12.9788 15.1893 13.1154 15.246C13.252 15.3026 13.3984 15.3318 13.5463 15.3318C13.6942 15.3318 13.8407 15.3026 13.9773 15.246C14.1139 15.1893 14.238 15.1063 14.3425 15.0016L15.0024 14.3418C15.1071 14.2373 15.1901 14.1132 15.2468 13.9766C15.3034 13.84 15.3326 13.6936 15.3326 13.5457C15.3326 13.3978 15.3034 13.2514 15.2468 13.1148C15.1901 12.9782 15.1071 12.8541 15.0024 12.7497L10.333 8.08081" stroke="#757575" stroke-width="1.5" stroke-linejoin="round"/>
                    </svg>
                    <p>Mantenimiento</p>
                    <p><?= $preguntasPorTema -> total_mantenimiento ?></p>
                </section>
                <section data-id="Sostenibilidad e innovación" class="tema" <?= $temaActual === 'Sostenibilidad e innovación' ? 'id="seleccionado"' : '' ?>>
                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.97343 14.0264C10.5976 18.6019 16.6979 12.5016 15.9355 1.06429C4.49826 0.301874 -1.60212 6.40226 2.97343 14.0264ZM2.97343 14.0264L1 15.9998M2.97343 14.0285L7.10038 9.89944" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p>Sostenibilidad e innovación</p>
                    <p><?= $preguntasPorTema -> total_sostenibilidad ?></p>
                </section>
                <section data-id="Formación y Recursos" class="tema" <?= $temaActual === 'Formación y Recursos' ? 'id="seleccionado"' : '' ?>>
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.6 4.31548L8.17143 0.0979131C8.07266 0.0335133 7.96386 0 7.85357 0C7.74328 0 7.63448 0.0335133 7.53571 0.0979131L3.25 2.90963L1.10714 4.31548L0.392857 4.7841C0.275061 4.86199 0.176002 4.98115 0.106659 5.12838C0.0373169 5.27562 0.00039979 5.44517 0 5.61825V11.2417H1.42857V6.19933L2.85714 7.13657V10.7262C2.85714 12.6569 5.08571 15 7.85714 15C10.6286 15 12.8571 12.6663 12.8571 10.7262V7.13657L14.6071 5.99314C14.85 5.83381 15 5.50578 15 5.159C15 4.81222 14.85 4.48419 14.6071 4.32486L14.6 4.31548ZM3.88571 4.58728L7.85 1.98176L12.6786 5.14963L7.85 8.31749L3.02143 5.14963L3.88571 4.57791V4.58728ZM11.4214 10.7262C11.4214 11.4385 9.91429 13.1255 7.85 13.1255C5.78571 13.1255 4.27857 11.4479 4.27857 10.7262V8.07381L7.52857 10.2107C7.62857 10.2763 7.73571 10.3138 7.85 10.3138C7.96429 10.3138 8.07143 10.2763 8.17143 10.2107L11.4214 8.07381V10.7262Z" fill="#757575"/>
                    </svg>
                    <p>Formación y Recursos</p>
                    <p><?= $preguntasPorTema -> total_formacion ?></p>
                </section>
            </div>
        </aside>
        <div class="der">
            <div class="opcionesPreguntas">
                <p>Mostrando nº preguntas</p>
                <div>
                    <label for="ordenar">Ordenar por:</label>
                    <?php $orden = $orden ?? 'Más recientes'; ?>
                    <select name="ordenar" id="ordenar">
                        <option value="Más recientes" <?= $orden === 'Más recientes' ? 'selected' : '' ?>>Más recientes</option>
                        <option value="Más antiguos" <?= $orden === 'Más antiguos' ? 'selected' : '' ?>>Más antiguos</option>
                        <option value="Más respuestas" <?= $orden === 'Más respuestas' ? 'selected' : '' ?>>Más respuestas</option>
                        <option value="Menos respuestas" <?= $orden === 'Menos respuestas' ? 'selected' : '' ?>>Menos respuestas</option>
                    </select>
                </div>
            </div>
            <div class="listaPreguntas">
                <p class="mensajeVacio" <?= !empty($preguntas) ? 'hidden' : '' ?>>
                    No hay preguntas en este tema.
                </p>
                <?php foreach ($preguntas as $pregunta): 
                    if($pregunta -> es_favorita == 1):
                        $llenar = '#FFDD33';
                    else:
                        $llenar = 'none';
                    endif;
                ?>
                <section class="tarjetaPregunta" data-url="index.php?controller=PreguntasController&accion=detalle&id=<?= $pregunta->pregunta_id ?>">
                    <div class="informacionSuperior">
                        <div class="autor">
                        <svg width="25" height="25" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.5 0C18.0499 0 13.6998 1.3196 9.99968 3.79193C6.29957 6.26426 3.41569 9.77828 1.71272 13.8896C0.00974887 18.001 -0.435826 22.525 0.432341 26.8895C1.30051 31.2541 3.44343 35.2632 6.59011 38.4099C9.73679 41.5566 13.7459 43.6995 18.1105 44.5677C22.475 45.4358 26.999 44.9903 31.1104 43.2873C35.2217 41.5843 38.7357 38.7004 41.2081 35.0003C43.6804 31.3002 45 26.9501 45 22.5C44.9932 16.5347 42.6205 10.8157 38.4024 6.59761C34.1843 2.37952 28.4653 0.0068058 22.5 0ZM35.3459 36.8454C35.3147 34.7368 34.4559 32.725 32.9547 31.2439C31.4535 29.7629 29.4302 28.9313 27.3214 28.9286H17.6786C15.57 28.9317 13.5472 29.7635 12.0464 31.2445C10.5455 32.7255 9.68693 34.737 9.65572 36.8454C6.74127 34.243 4.686 30.8168 3.76206 27.0203C2.83813 23.2239 3.0891 19.2364 4.48175 15.5858C5.8744 11.9352 8.34304 8.79375 11.5608 6.57734C14.7785 4.36093 18.5936 3.17415 22.5008 3.17415C26.408 3.17415 30.2231 4.36093 33.4408 6.57734C36.6586 8.79375 39.1272 11.9352 40.5199 15.5858C41.9125 19.2364 42.1635 23.2239 41.2396 27.0203C40.3156 30.8168 38.2603 34.243 35.3459 36.8454Z" fill="#C8D0DA"/>
                            <path d="M22.5 9.64288C20.9107 9.64288 19.3571 10.1142 18.0356 10.9971C16.7141 11.8801 15.6842 13.1351 15.076 14.6035C14.4678 16.0718 14.3086 17.6875 14.6187 19.2463C14.9288 20.8051 15.6941 22.2369 16.8179 23.3607C17.9417 24.4845 19.3735 25.2498 20.9323 25.5599C22.4911 25.87 24.1068 25.7108 25.5751 25.1026C27.0435 24.4944 28.2985 23.4645 29.1815 22.143C30.0644 20.8215 30.5357 19.2679 30.5357 17.6786C30.5357 15.5474 29.6891 13.5035 28.1821 11.9965C26.6751 10.4895 24.6312 9.64288 22.5 9.64288Z" fill="#C8D0DA"/>
                        </svg>
                        <p>
                            <a href="index.php?controller=UsuarioController&accion=show&id=<?= $pregunta->autor_id ?>">
                                <?= htmlspecialchars($pregunta->autor_nombre) ?>
                            </a>
                        </p>   
                    </div>
                    <p><?= $pregunta->pregunta_fecha?></p>
                    </div>
                    
                    
                    <h1><?= $pregunta->pregunta_titulo ?></h1>
                    <h2><?= $pregunta->pregunta_descripcion ?></h2>
                    <hr>
                    <div class="abajoPregunta">
                        <p><?= $pregunta->pregunta_tema ?> · <?= $pregunta->num_respuestas ?> respuestas</p>

                        <div class="accionesPregunta">
                            <?php if ($esAdmin): ?>
                                <a href="index.php?controller=PreguntasController&accion=eliminar&id=<?= $pregunta->pregunta_id ?>"
                                class="btnEliminarPregunta"
                                title="Eliminar pregunta"
                                onclick="event.stopPropagation(); return confirm('¿Seguro que quieres eliminar esta pregunta?');">
                                    <img src="views/imagenes/Papelera.svg" alt="Eliminar pregunta" height="18">
                                </a>
                            <?php endif; ?>

                            <svg data-id="<?= $pregunta->pregunta_id ?>" class="botonFavorito" width="15" height="20" viewBox="0 0 13 18" fill="<?= $llenar ?>" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 3.8C1 2.82 1 2.33 1.19075 1.9555C1.35853 1.62623 1.62623 1.35853 1.9555 1.19075C2.33 1 2.82 1 3.8 1H8.7C9.68 1 10.17 1 10.5445 1.19075C10.8738 1.35853 11.1415 1.62623 11.3092 1.9555C11.5 2.33 11.5 2.82 11.5 3.8V15.4419C11.5 15.8671 11.5 16.0797 11.4116 16.1961C11.3734 16.2467 11.3247 16.2884 11.2688 16.3184C11.2129 16.3483 11.1512 16.3657 11.0879 16.3694C10.9417 16.3781 10.765 16.26 10.4115 16.0246L6.25 13.25L2.0885 16.0237C1.735 16.26 1.55825 16.3781 1.41125 16.3694C1.34811 16.3656 1.28654 16.3481 1.2308 16.3182C1.17506 16.2883 1.12647 16.2466 1.08837 16.1961C1 16.0797 1 15.8671 1 15.4419V3.8Z" stroke="#FFDD33" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    
        
                </section>

                <?php endforeach; ?>
                
            </div>
        </div>
        
        
    </div>
</main>
<?php require __DIR__.'/layout/footer.php'; ?>