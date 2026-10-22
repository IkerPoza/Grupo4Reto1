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

    public function detalle() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['usuario']->id)) {
            $this->render('index.view.php');
            return;
        }
        $idPregunta = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'idPregunta', FILTER_VALIDATE_INT);
        if (!$idPregunta) { 
            header('Location: index.php'); 
            return; 
        }

        $pregunta = PreguntasModel::getDetalleById($idPregunta);
        if (!$pregunta) { 
            http_response_code(404); echo 'Pregunta no encontrada.'; 
            return; 
        
        }
        $respuestas = PreguntasModel::getRespuestasByPregunta($idPregunta);
        $idUsuario = $_SESSION['usuario']->id;
        $esAdmin = ($_SESSION['usuario']->tipo ?? '') === 'admin';
        $respuestaActiva = filter_input(INPUT_GET, 'responderA', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'responderA', FILTER_VALIDATE_INT);
        $errorRespuesta = $_SESSION['errorRespuesta'] ?? '';
        unset($_SESSION['errorRespuesta']);
        $this->render('preguntas.detalle.view.php', compact('pregunta', 'respuestas', 'idUsuario', 'esAdmin', 'respuestaActiva', 'errorRespuesta'));
    }

    public function responder() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $idUsuario = ($_SESSION['usuario']->id ?? 0);
        $idPregunta = filter_input(INPUT_POST, 'idPregunta', FILTER_VALIDATE_INT);
        $idRespuesta = filter_input(INPUT_POST, 'idRespuesta', FILTER_VALIDATE_INT) ?: null;
        $texto = trim($_POST['respuesta'] ?? '');
        if (!$idUsuario || !$idPregunta || $texto === '') {
            $_SESSION['errorRespuesta'] = 'Escribe una respuesta antes de enviarla.';
            header('Location: index.php?controller=PreguntasController&accion=detalle&id=' . $idPregunta);
            exit;
        }
        $pregunta = PreguntasModel::getDetalleById($idPregunta);
        if (!$pregunta) {
            header('Location: index.php'); 
            exit;
        }
        if ($idRespuesta) {
            $valida = false;
            foreach (PreguntasModel::getRespuestasByPregunta($idPregunta) as $r) {
                if ($r->id === $idRespuesta) {
                    $valida = true; 
                    break; 
                }
            }
            if (!$valida) $idRespuesta = null;
        }
        PreguntasModel::crearRespuesta($idUsuario, $idPregunta, $idRespuesta, $texto);
        header('Location: index.php?controller=PreguntasController&accion=detalle&id=' . $idPregunta);
        exit;
    }

    public function eliminarRespuesta() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $idUsuario = ($_SESSION['usuario']->id ?? 0);
        $idRespuesta = filter_input(INPUT_POST, 'idRespuesta', FILTER_VALIDATE_INT);
        $idPregunta = filter_input(INPUT_POST, 'idPregunta', FILTER_VALIDATE_INT);
        if (!$idUsuario || !$idRespuesta || !$idPregunta) { 
            header('Location: index.php'); 
            exit; 
        }
        $respuesta = null;
        foreach (PreguntasModel::getRespuestasByPregunta($idPregunta) as $r) {
            if ($r->id === $idRespuesta) { 
                $respuesta = $r; 
                break; 
            }
        }
        $esAdmin = ($_SESSION['usuario']->tipo ?? '') === 'admin';
        if ($respuesta && ($esAdmin || $respuesta->idUsuario === $idUsuario)) {
            PreguntasModel::eliminarRespuesta($idRespuesta);
        }
        header('Location: index.php?controller=PreguntasController&accion=detalle&id=' . $idPregunta);
        exit;
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