<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntasModel.php';

class PreguntasController extends BaseController {
    
    public function index() {
        $preguntas = $this->show();
        $this -> render('preguntas.view.php', ['preguntas' => $preguntas]);
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