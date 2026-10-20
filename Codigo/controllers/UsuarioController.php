<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

class UsuarioController extends BaseController {

    public function index() {
        $this->show();
    }

    public function store() {
        
    }

    public function show() {

        $id = isset($_GET['id']) ? $_GET['id'] : $this->usuarioId();
        $usuario = UsuarioModel::getById($id);
        if (!$usuario) {
            echo 'Usuario no encontrado.';
            return;
        }

        $esPropio = ($id === $this->usuarioId());
        $stats = UsuarioModel::getEstadisticas($id);
        $preguntas = PreguntasModel::getByUsuario($id);
        $guardadas = $esPropio ? PreguntasModel::getGuardadas($id) : [];

        $this->render('usuario.view.php', compact('usuario', 'stats', 'preguntas', 'guardadas', 'esPropio'));
    }

    public function update() {

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
            UsuarioModel::update($this->usuarioId(), $datos);
            $_SESSION['usuario_nombre'] = $datos['nombre'];
        }
        $this->redirect(url('UsuarioController', 'show'));
    }

    public function destroy() {
    }

    public function destroyAll() {
    }

    public function validarUsuario(){
        try{
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $email = $_POST["email"];
            $password = $_POST["password"];
            $usuario = UsuarioModel::validarUsuario($email, $password); 

            $_SESSION['usuario_id'] = $usuario -> id;
            $_SESSION['nombre_usuario'] = $usuario -> nombre;
            $_SESSION['rol'] = $usuario -> tipo;
            echo $_SESSION['usuario_id'];
            $this -> redirect("index.php");
        }catch(Exception $e){
            echo "Usuario o Contraseña no valido.";
        }
        
    }

}
