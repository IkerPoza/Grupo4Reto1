<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntasModel.php';

class PreguntasController extends BaseController {
    
    public function index() {
        session_start();
        if(isset($_SESSION["usuario_id"])){
            $preguntas = $this->show();
            $this -> render('preguntas.view.php', ['preguntas' => $preguntas]);
        }else{
            $this -> render('index.view.php');
        }
    }
    
    public function show() {
        return $preguntas = PreguntasModel::getAll();
    }
    
    public function store() {
        
    }
    
    public function destroy() {
        
    }
    
    public function destroyAll() {
        
    }
}