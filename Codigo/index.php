<?php
session_start();
require_once 'Router.php';
require_once __DIR__ . '/models/InicioModel.php';

// si el sison esta iniciada comprobar que sigue exisitiendo en la BBDD
if (isset($_SESSION['usuario'])) {
    $usuarioBD = InicioModel::obtenerPorId($_SESSION['usuario']->id);

    if (!$usuarioBD) {
        unset($_SESSION['usuario']);
    }
}

Router::dispatch();

