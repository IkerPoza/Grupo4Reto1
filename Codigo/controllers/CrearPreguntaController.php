<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/CrearPreguntaModel.php';

class CrearPreguntaController extends BaseController {

    public function index() {
        if(isset($_COOKIE['error'])):
            setcookie("error", "", time() - 3600);
        endif; 
        $this->render('crearPregunta.view.php');
    }

    public function store() {
        try {
            setcookie("error", "", time() - 3600);

            $usuario = $_SESSION['usuario'];

            $datos = [
                "titulo" => $_POST["titulo"],
                "descripcion" => $_POST["descripcion"],
                "tema" => $_POST["tema"],
                "idUsuario" => $usuario->id
            ];

            CrearPreguntaModel::insertarPregunta($datos);

            $this->redirect('index.php');

            
        } catch (Throwable $e) {
            setcookie('error', "Error al insertar", 0);
            $this -> render('crearPregunta.view.php');
        }
}

    public function show() {
        
    }

    public function destroy() {
        
    }
    

    public function destroyAll() {
        
    }

}