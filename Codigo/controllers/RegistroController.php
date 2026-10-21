<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/RegistroModel.php';

class RegistroController extends BaseController {

    public function index() {
        $this -> render('registro.view.php');
    }

    public function store() {
        try{
            $nombre = $_POST["nombre"];
            $apellidos = $_POST["apellidos"];
            $email = $_POST["email"];
            $area = $_POST["area"];
            $antiguedad = $_POST["antiguedad"];
            $password = $_POST["password"];

            $existeEmail = RegistroModel::existeEmail($email);
            if ($existeEmail == true){
                    throw new Exception();
            }

            if(isset($_COOKIE['error'])):
                setcookie("error", "", time() - 3600);
            endif;
            
        }catch(Exception $e){
            setcookie('error', "El email ya esta en uso.", 0);
        }
    }

    public function show() {
        
    }

    public function destroy() {
        
    }
    

    public function destroyAll() {
        
    }

}

