<?php

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'reto');
define('DB_USER', 'root');
define('DB_PASS', '');

if (!defined('AREAS')) {
    define('AREAS', [
        'desarrollo' => 'Desarrollo',
        'diseno' => 'Diseño',
        'formacion' => 'Formación y Recursos',
        'administracion' => 'Administración',
        'soporte' => 'Soporte'
    ]);
}