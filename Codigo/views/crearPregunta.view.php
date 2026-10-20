<?php
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
            <label for="Tema">Tema</label>
            <select id="tema" name="tema">
                            <option value="" selected> --Selecciona el tema de tu pregunta-- </option>
                            <option value="ingenieriaYDiseno"> Ingeniería y diseño </option>
                            <option value="produccionYFabricacion"> Producción y fabricación </option>
                            <option value="mantenimientoYReparacion"> Mantenimiento y reparación </option>
                            <option value="calidadYSeguridad"> Calidad y seguridad </option>
                            <option value="investigacionYDesarrollo">Investigación y desarrollo </option>
                            <option value="electronicaYSistemasInformaticos"> Electrónica y sistemas informáticos </option>
                            <option value="logisticaYAlmacen"> Logística y almacén </option>
                            <option value="administracionYRecursosHumanos"> Administración y recursos humanos</option>
                            <option value="comercialYMarketing"> Comercial y marketing </option>
                            <option value="informaticaYCiberseguridad"> Informática y ciberseguridad </option>
                            <option value="comprasYProveedores"> Compras y proveedores </option>
                            <option value="gestionDeProyectos"> Gestión de proyectos </option>
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