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

        // imagen (opcional)
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

            if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
                die('Error al subir la imagen.');
            }

            if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                die('La imagen no puede superar los 2 MB.');
            }

            $temporal = $_FILES['foto']['tmp_name'];
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($temporal);

            $permitidos = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

            if (!isset($permitidos[$mime]) || @getimagesize($temporal) === false) {
                die('Selecciona una imagen JPG, PNG o WEBP válida.');
            }

            $carpeta = __DIR__ . '/../uploads/perfiles/';
            if (!is_dir($carpeta) && !mkdir($carpeta, 0755, true) && !is_dir($carpeta)) {
                die('No se pudo crear la carpeta de imágenes.');
            }

            $nombreFoto = 'usuario_' . (int) $id . '_' . time() . '.' . $permitidos[$mime];

            if (!move_uploaded_file($temporal, $carpeta . $nombreFoto)) {
                die('No se pudo guardar la imagen.');
            }

            $usuarioAnterior = DetalleUsuarioModel::getById($id);
            if ($usuarioAnterior && !empty($usuarioAnterior->foto)) {
                $rutaAnterior = $carpeta . basename($usuarioAnterior->foto);
                if (basename($usuarioAnterior->foto) !== $nombreFoto && is_file($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            }

            $datos['foto'] = 'uploads/perfiles/' . $nombreFoto;
        }

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