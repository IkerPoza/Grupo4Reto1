<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/ListarUsuariosModel.php';

class ListarUsuariosController extends BaseController {

    public function index() {
        $orden = $_GET['orden'] ?? 'asc';
        $busqueda = $_POST['busqueda'] ?? '';

        if (!empty($busqueda)) {
            $usuarios = ListarUsuariosModel::buscar($busqueda, $orden);
        } else {
            $usuarios = ListarUsuariosModel::getAll($orden);
        }

        require_once __DIR__ . '/../views/listarUsuarios.view.php';
    }

    public function eliminar(){
        if (isset($_GET['id'])) {
            ListarUsuariosModel::deleteById($_GET['id']);
        }
        header('Location: index.php?controller=ListarUsuariosController&accion=index');
        exit;
    }

    
}
