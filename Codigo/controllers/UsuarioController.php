<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/PreguntasModel.php';
require_once __DIR__ . '/../config/config.php';

function nombreArea($area) {
    return AREAS[$area] ?? $area;
}

class UsuarioController extends BaseController {

    public function index() {
        $this->show();
    }

    public function store() {
        
    }

    //datos del usuario
    public function show() {
        try{
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $idUsuario = $_SESSION['usuario']->id ?? 0;

            $id = isset($_GET['id']) ? $_GET['id'] : $idUsuario;

            $usuario = UsuarioModel::getById($id);

            if (!$usuario) {
                echo 'Usuario no encontrado.';
                return;
            }


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
        } catch (Throwable $e) {
            echo "Error al cargar el perfil: " . $e->getMessage();
        }
    }

    
    //actualizar usuario
    public function update() {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $idUsuario = $_SESSION['usuario']->id ?? 0;

            if (!$idUsuario) {
                exit('Debes iniciar sesión.');
            }

            $datos = [
                'nombre'     => trim($_POST['nombre'] ?? ''),
                'apellidos'  => trim($_POST['apellidos'] ?? ''),
                'area'       => $_POST['area'] ?? '',
                'contacto'   => trim($_POST['contacto'] ?? ''),
                'antiguedad' => $_POST['antiguedad'] ?? '',
                'foto'       => null
            ];

            if ($datos['nombre'] === '' || mb_strlen($datos['nombre']) > 100) {
                exit('El nombre es obligatorio (máximo 100 caracteres).');

            } elseif ($datos['apellidos'] === '' || mb_strlen($datos['apellidos']) > 100) {
                exit('Los apellidos son obligatorios (máximo 100 caracteres).');

            } elseif (!array_key_exists($datos['area'], AREAS)) {
                exit('Selecciona un área de trabajo válida.');

            } elseif (!filter_var($datos['contacto'], FILTER_VALIDATE_EMAIL) || mb_strlen($datos['contacto']) > 100) {
                exit('El correo de contacto no es válido.');

            } elseif (!ctype_digit((string) $datos['antiguedad']) || $datos['antiguedad'] > 60) {
                exit('La antigüedad debe estar entre 0 y 60 años.');
            }

            // imagen
            if (isset($_FILES['foto']) &&
                $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

                if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
                    exit('Error al subir la imagen.');
                }

                if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                    exit('La imagen no puede superar los 2 MB.');
                }

                $temporal = $_FILES['foto']['tmp_name'];

                $tipo = (new finfo(FILEINFO_MIME_TYPE))->file($temporal);

                $extensiones = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/webp' => 'webp'
                ];

                if (!isset($extensiones[$tipo]) || @getimagesize($temporal) === false) {
                    exit('Selecciona una imagen JPG, PNG o WEBP válida.');
                }

                $carpeta = __DIR__ . '/../uploads/perfiles/';

                if (!is_dir($carpeta) && !mkdir($carpeta, 0755, true) && !is_dir($carpeta)) {
                    exit('No se pudo crear la carpeta de imágenes.');
                }

                
                $nombreArchivo = 'usuario_' . $idUsuario . '.' . $extensiones[$tipo];

                $usuarioAnterior = UsuarioModel::getById($idUsuario);

                if (!move_uploaded_file($temporal, $carpeta . $nombreArchivo)) {
                    exit('No se pudo guardar la imagen.');
                }

                if ($usuarioAnterior && !empty($usuarioAnterior->foto)) {
                    $fotoAnterior = basename($usuarioAnterior->foto);
                    $rutaAnterior = $carpeta . $fotoAnterior;

                    if ($fotoAnterior !== $nombreArchivo && is_file($rutaAnterior)) {
                        unlink($rutaAnterior);
                    }
                }

                $datos['foto'] = 'uploads/perfiles/' . $nombreArchivo;

            }

            if (!UsuarioModel::update($idUsuario, $datos)) {
                exit('No se pudieron guardar los cambios.');
            }

            $_SESSION['usuario_nombre'] = $datos['nombre'];

            $this->redirect('index.php?controller=UsuarioController&accion=show');

        } catch (Throwable $e) {
            error_log($e->getMessage());
            echo 'Error al intentar actualizar el usuario.';
        }
    }

    //eliminar usuario si eres admin
    public function destroy() {
        try{
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $idUsuario = $_SESSION['usuario']->id ?? 0;
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

            unset($_SESSION['usuario']);

            $this->redirect('index.php');
        }catch(Exception $e){
            echo "Error al eliminar el usuario.";
        }
    }

    //cerrar sesion
    public function logout(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(session_name(),'', time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();

        header('Location: index.php');
        exit;
    }

}
