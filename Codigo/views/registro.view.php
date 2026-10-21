<?php 
    $titulo = 'Registro';
    $css = 'Registro-estilo.css';
    $scripts = 'registro.script.js';
    require __DIR__.'/layout/head.php';
?>

<main class="registro-contenedor">
    <div class="registro-division">
        <h2 class="registro-titulo">Registrarse</h2>

        <form action="index.php" class="registro-form" method="POST">
            <p>
            <label for="Nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Introduce tu nombre" required>
            <small class="mensaje-error" id="error-nombre"></small>
            </p>
            <p>
            <label for="Apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" placeholder="Introduce tus apellidos" required>
            <small class="mensaje-error" id="error-apellidos"></small>
            </p>
            <p>
            <label for="Email">Email</label>
            <input type="email" name="email" id="email" placeholder="Introduce tu email" required>
            <small class="mensaje-error" id="error-email"></small>
            </p>
            <p>
            <label for="Area">Area de trabajo</label>
            <select id="area" name="area">
                            <option value="" selected> --Selecciona tu área de trabajo-- </option>
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
            <small class="mensaje-error" id="error-area"></small>
            </p>
            <p>
            <label for="antiguedad">Tiempo de antigüedad</label>
            <input type="number" name="antiguedad" id="antiguedad" placeholder="Tiempo de antigüedad" required>
            <small class="mensaje-error" id="error-antiguedad"></small>
            </p>
            <p>
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" placeholder="Introduce tu contraseña" required>
            <small class="mensaje-error" id="error-password"></small>
            </p>
            <input type="hidden" name="controller" value="RegistroController">
            <input type="hidden" name="accion" value="store">
            <input id="bRegistro" class="btn-registro" type="submit" value="Registrarse">
        </form>
        <p class="mensajeError"><?php 
            if(isset($_COOKIE["error"])):
                echo $_COOKIE["error"]; 
            endif;
        ?></p>
    </div>
</main>

<?php require __DIR__.'/layout/footer.php'; ?>
