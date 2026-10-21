<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/InicioModel.php';

class InicioController extends BaseController {

    public function index() {
    }

    public function store() {
    }

    public function show() {
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
            $usuario = InicioModel::iniciarSesion($email, $password);
            if ($usuario == false){
                throw new Exception();
            }

            if(isset($_COOKIE['error'])):
                setcookie("error", "", time() - 3600);
            endif;
            $_SESSION['usuario'] = $usuario;
            $this -> redirect("index.php");
        }catch(Exception $e){
            setcookie('error', "Usuario o Contraseña no valido.", 0);
            $this -> redirect("index.php");
        }
    }
}
