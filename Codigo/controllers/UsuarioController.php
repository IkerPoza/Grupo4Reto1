<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/PreguntasModel.php';

class UsuarioController extends BaseController {

    public function index() {
        $this->show();
    }

    public function store() {
        
    }

    public function show() {

        $id = isset($_GET['id']) ? $_GET['id'] : $idUsuario;
        $usuario = UsuarioModel::getById($id);
        if (!$usuario) {
            echo 'Usuario no encontrado.';
            return;
        }

        $idUsuario = $_SESSION['usuario']['id'] ?? 0;

        $esPropio = ($id == $idUsuario);
        $usuarioActual = UsuarioModel::getById($idUsuario);
        $esAdmin = $usuarioActual && $usuarioActual->tipo === 'admin';

        $stats = UsuarioModel::getEstadisticas($id);
        $preguntas = PreguntasModel::getByUsuario($id);
        $guardadas = $esPropio ? PreguntasModel::getGuardadas($id) : [];

        $this->render('usuario.view.php', compact(
            'usuario',
            'stats',
            'preguntas',
            'guardadas',
            'esPropio',
            'esAdmin'
        ));
    }

    public function update() {
        $idUsuario = $_SESSION['usuario']['id'] ?? 0;

        $datos = [
            'nombre'     => ('nombre'),
            'apellidos'  => ('apellidos'),
            'area'       => ('area'),
            'contacto'   => ('contacto'),
            'antiguedad' => ('antiguedad'),
        ];

        $error = null;
        if ($datos['nombre'] === '' || mb_strlen($datos['nombre']) > 100) {
            $error = 'El nombre es obligatorio (máximo 100 caracteres).';
        } elseif ($datos['apellidos'] === '' || mb_strlen($datos['apellidos']) > 100) {
            $error = 'Los apellidos son obligatorios (máximo 100 caracteres).';
        } elseif (!array_key_exists($datos['area'])) {
            $error = 'Selecciona un área de trabajo válida.';
        } elseif (!filter_var($datos['contacto']) || mb_strlen($datos['contacto']) > 100) {
            $error = 'El correo de contacto no es válido.';
        } elseif (!ctype_digit($datos['antiguedad']) || $datos['antiguedad'] > 60) {
            $error = 'La antigüedad debe ser un número de años entre 0 y 60.';
        }

        if ($error) {
            $error;
        } else {
            $datos['antiguedad'] = $datos['antiguedad'];
            UsuarioModel::update($idUsuario, $datos);
            $_SESSION['usuario_nombre'] = $datos['nombre'];
        }
        $this->redirect(url('UsuarioController', 'show'));
    }

    public function destroy() {
        $idUsuario = $_SESSION['usuario']['id'] ?? 0;
        $usuarioActual = UsuarioModel::getById($idUsuario);

        if (!$usuarioActual || $usuarioActual->tipo !== 'admin') {
            exit('No tienes permiso para eliminar perfiles.');
        }

        if (!isset($_POST['id'])) {
            $this->redirect('index.php');
        }

        $id = $_POST['id'];

        if (!$id || !UsuarioModel::getById($id)) {
            exit('El perfil no existe.');
        }

        UsuarioModel::deleteById($id);

        $this->redirect('index.php');
    }

    public function destroyAll() {
    }

}
