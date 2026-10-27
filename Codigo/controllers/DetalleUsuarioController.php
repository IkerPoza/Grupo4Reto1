<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/DetalleUsuarioModel.php';

class DetalleUsuarioController extends BaseController {

    public function detalle() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: index.php?controller=ListarUsuariosController&accion=index');
            exit;
        }

        $usuario = DetalleUsuarioModel::getById($id);

        if (!$usuario) {
            header('Location: index.php?controller=ListarUsuariosController&accion=index');
            exit;
        }

        if (!defined('AREAS')) {
            define('AREAS', [
                'ingenieriaYDiseño' => 'Ingeniería y Diseño',
                'fabricacion'       => 'Fabricación',
                'calidad'           => 'Calidad',
                'mantenimiento'     => 'Mantenimiento'
            ]);
        }

        require_once __DIR__ . '/../views/detalleUsuarios.view.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=ListarUsuariosController&accion=index');
            exit;
        }

        $id = $_POST['id'] ?? $_GET['id'] ?? null;

        if (!$id) {
            header('Location: index.php?controller=ListarUsuariosController&accion=index');
            exit;
        }

        $tipo = $_POST['tipo'] ?? 'user';
        if (!in_array($tipo, ['admin', 'user'], true)) {
            $tipo = 'user';
        }

        $datos = [
            'nombre'    => trim($_POST['nombre'] ?? ''),
            'apellidos' => trim($_POST['apellidos'] ?? ''),
            'area'      => $_POST['area'] ?? '',
            'email'     => trim($_POST['contacto'] ?? ''),
            'tipo'      => $tipo,
            'foto'      => null
        ];

        /*if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $permitidos = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime  = $finfo->file($_FILES['foto']['tmp_name']);

            if (isset($permitidos[$mime])) {
                $nombreFoto  = uniqid('user_', true) . '.' . $permitidos[$mime];
                $rutaDestino = __DIR__ . '/../views/imagenes/' . $nombreFoto;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
                    $datos['foto'] = 'views/imagenes/' . $nombreFoto;
                }
            }
        }*/

        try {
            DetalleUsuarioModel::update($id, $datos);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                die('Ese correo ya está en uso por otro usuario.');
            }
            throw $e;
        }
        header('Location: index.php?controller=ListarUsuariosController&accion=index');
        exit;
    }
}