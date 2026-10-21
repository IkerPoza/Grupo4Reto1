<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntasModel.php';

class PreguntasController extends BaseController {
    
    public function index() {
        if(isset($_SESSION["usuario"])){
            $preguntas = $this->show();
            $preguntasPorTema = $this->preguntasPorTema();
            $this -> render('preguntas.view.php', ['preguntas' => $preguntas, 'preguntasPorTema' => $preguntasPorTema]);
        }else{
            $this -> render('index.view.php');
        }
    }
    
    public function show() {
        return $preguntas = PreguntasModel::getAll($_SESSION['usuario']->id);
    }

    public function preguntasPorTema(){
        return $preguntasPorTema = PreguntasModel::preguntasPorTema($_SESSION['usuario']->id);
    }
    
    public function store() {
        
    }
    
    public function destroy() {
        
    }
    
    public function destroyAll() {
        
    }

    public function anadirFavoritos(){
        $data = json_decode(file_get_contents('php://input'), true);
        $pregunta_id = (int) ($data['pregunta_id'] ?? 0);
        $usuario_id = $_SESSION['usuario']->id;

        PreguntasModel::anadirFavoritos($pregunta_id, $usuario_id);
    }

    public function eliminarFavoritos(){
        $data = json_decode(file_get_contents('php://input'), true);
        $pregunta_id = (int) ($data['pregunta_id'] ?? 0);
        $usuario_id = $_SESSION['usuario']->id;

        PreguntasModel::eliminarFavoritos($pregunta_id, $usuario_id);
    }

    public function buscar(){
        if($_POST["busqueda"] == ''):
            $this -> index();
        else:
            $busqueda = '%' . $_POST["busqueda"] . '%';
            $preguntas = PreguntasModel::mostrarPorNombre($_SESSION['usuario']->id, $busqueda);
            $preguntasPorTema = $this->preguntasPorTema();
            $this -> render('preguntas.view.php', ['preguntas' => $preguntas, 'preguntasPorTema' => $preguntasPorTema]);
        endif;
    }
}