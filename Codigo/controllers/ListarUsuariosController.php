<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/ListarUsuariosModel.php';

class ListarUsuariosController extends BaseController {

    public function index() {
        try{
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $idUsuario = $_SESSION['usuario']->id ?? 0;
            $usuarioActual = UsuarioModel::getById($idUsuario);

            if (!$usuarioActual || $usuarioActual->tipo !== 'admin') {
                exit('No tienes permiso para eliminar perfiles.');
            }

            $orden    = $_GET['orden'] ?? 'asc';
            $busqueda = trim($_GET['busqueda'] ?? '');

            if ($busqueda !== '') {
                $usuarios = ListarUsuariosModel::buscar($busqueda, $orden);
            } else {
                $usuarios = ListarUsuariosModel::getAll($orden);
            }

            require_once __DIR__ . '/../views/listarUsuarios.view.php';

        }catch(Exception $e){
            echo "Error al eliminar el usuario.";
        }
    }

    public function eliminar(){
        try{
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $idUsuario = $_SESSION['usuario']->id ?? 0;
            $usuarioActual = UsuarioModel::getById($idUsuario);

            if (!$usuarioActual || $usuarioActual->tipo !== 'admin') {
                exit('No tienes permiso para eliminar perfiles.');
            }
            if (isset($_GET['id'])) {
                ListarUsuariosModel::deleteById($_GET['id']);
            }
            header('Location: index.php?controller=ListarUsuariosController&accion=index');
            exit;
        }catch(Exception $e){
            echo "Error al eliminar el usuario.";
        }
    }

    public function detalle(){
        try{
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $idUsuario = $_SESSION['usuario']->id ?? 0;
            $usuarioActual = UsuarioModel::getById($idUsuario);

            if (!$usuarioActual || $usuarioActual->tipo !== 'admin') {
                exit('No tienes permiso para eliminar perfiles.');
            }
            $id = $_GET['id'] ?? null;
            
            if ($id) {
                $usuario = ListarUsuariosModel::getById($id);
            }

            require_once __DIR__ . '/../views/detalleUsuarios.view.php';
        }catch(Exception $e){
            echo "Error al eliminar el usuario.";
        }
    }

    
}
