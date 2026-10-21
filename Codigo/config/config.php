<?php

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'reto');
define('DB_USER', 'root');
define('DB_PASS', '');

if (!defined('AREAS')) {
    define('AREAS', [
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
    ]);
}