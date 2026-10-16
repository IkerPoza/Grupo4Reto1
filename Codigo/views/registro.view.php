<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AERGIBIDE SL</title>

    <link rel="stylesheet" href="views/estilos/estilos.css">
    <link rel="stylesheet" href="views/estilos/registro-estilo.css">
</head>

<body>

<?php require __DIR__.'/layout/head.php';?>


<main class="registro-contenedor">
    <div class="registro-division">
        <h2 class="registro-titulo">Registrarse</h2>

        <form class="registro-form">
            <p>
            <label for="Nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Introduce tu nombre" required>
            </p>
            <p>
            <label for="Apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" placeholder="Introduce tus apellidos" required>
            </p>
            <p>
            <label for="Gmail">Gmail</label>
            <input type="email" name="gmail" id="gmail" placeholder="Introduce tu gmail" required>
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
            </p>
            <p>
            <label for="antiguedad">Tiempo de antigüedad</label>
            <input type="number" name="antiguedad" id="antiguedad" placeholder="Tiempo de antigüedad" required>
            </p>
            <p>
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" placeholder="Introduce tu contraseña" required>
            </p>
            
            <a href="index.view.html" class="btn-registro">Registrarse</a>
        </form>
    </div>
</main>

<?php require __DIR__.'/layout/footer.php'; ?>

<script src="scripts/registro.script.js"></script>

</html>