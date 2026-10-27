<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntasModel.php';

class PreguntasController extends BaseController {
    
    public function index() {
        if(isset($_SESSION["usuario"])){
            $preguntas = $this->show();
            $preguntasPorTema = $this->preguntasPorTema();
            $orden = 'Más recientes';
            $this -> render('preguntas.view.php', ['preguntas' => $preguntas, 'preguntasPorTema' => $preguntasPorTema, 'orden' => $orden]);
        }else{
            $this -> render('index.view.php');
        }
    }
    
    public function show() {
        return $preguntas = PreguntasModel::getAll($_SESSION['usuario']->id, 'Más recientes');
    }

    public function preguntasPorTema(){
        return $preguntasPorTema = PreguntasModel::preguntasPorTema($_SESSION['usuario']->id);
    }
    
    public function store() {
        
    }
    
    public function eliminar() {
        $esAdmin = isset($_SESSION['usuario']) && $_SESSION['usuario']->tipo === 'admin';

        if (!$esAdmin) {
            header('Location: index.php?controller=PreguntasController&accion=index');
            exit;
        }

        $id = $_GET['id'] ?? null;

        if ($id) {
            PreguntasModel::deleteById($id);
        }

        header('Location: index.php?controller=PreguntasController&accion=index');
        exit;
        
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
        $archivosRespuestas = PreguntasModel::getArchivosRespuestas($idPregunta);
        $idUsuario = $_SESSION['usuario']->id;
        $esAdmin = ($_SESSION['usuario']->tipo ?? '') === 'admin';
        $respuestaActiva = filter_input(INPUT_GET, 'responderA', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'responderA', FILTER_VALIDATE_INT);
        $errorRespuesta = $_SESSION['errorRespuesta'] ?? '';
        unset($_SESSION['errorRespuesta']);
        $this->render('preguntas.detalle.view.php', compact('pregunta', 'respuestas','archivosRespuestas', 'idUsuario', 'esAdmin', 'respuestaActiva', 'errorRespuesta'));
    }

        
    public function votar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json; charset=utf-8');

        $idUsuario = (int)($_SESSION['usuario']->id ?? 0);

        $datos = json_decode(file_get_contents('php://input'), true) ?? [];
        $idRespuesta = $datos['idRespuesta'] ?? null;
        $voto = $datos['voto'] ?? null;

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare('SELECT id FROM respuesta WHERE id = ?');
            $stmt->execute([$idRespuesta]);

            $miVoto = PreguntasModel::votarRespuesta($idUsuario, $idRespuesta, $voto);
            $totalVotos = (int)PreguntasModel::totalVotosRespuesta($idRespuesta);

            echo json_encode([
                'ok' => true,
                'miVoto' => (int)$miVoto,
                'totalVotos' => $totalVotos,
                'mensaje' => ((int)$miVoto !== 0 && (int)$miVoto !== (int)$voto)
                    ? 'Ya has votado en sentido contrario. No puedes cambiar el sentido de tu voto.'
                    : ''
            ]);
        } catch (Throwable $e) {
            error_log($e->getMessage());
        }
    }



    public function responder() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $idUsuario = ($_SESSION['usuario']->id ?? 0);
        $idPregunta = filter_input(INPUT_POST, 'idPregunta', FILTER_VALIDATE_INT);
        $idRespuesta = filter_input(INPUT_POST, 'idRespuesta', FILTER_VALIDATE_INT) ?: null;
        $texto = trim($_POST['respuesta'] ?? '');

        $archivosSubidos = [];
        $archivosGuardados = [];

        try {
            if (!$idUsuario || !$idPregunta || $texto === '') {
                throw new RuntimeException(
                    'Escribe una respuesta antes de enviarla.'
                );
            }

            $pregunta = PreguntasModel::getDetalleById($idPregunta);

            if (!$pregunta) {
                throw new RuntimeException('La pregunta no existe.');
            }

            if ($idRespuesta) {
                $valida = false;

                foreach (PreguntasModel::getRespuestasByPregunta($idPregunta) as $r) {
                    if ($r->id === $idRespuesta) {
                        $valida = true;
                        break;
                    }
                }

                if (!$valida) {
                    throw new RuntimeException('La respuesta seleccionada no es válida.');
                }
            }

            $archivos = $_FILES['archivos'] ?? null;
            $archivosValidos = [];

            $permitidos = [
                'application/pdf',
                'image/jpeg',
                'image/png',
                'image/webp',
                'text/plain',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation'
            ];

            if ($archivos && is_array($archivos['name'])) {
                $total = count($archivos['name']);

                if ($total > 5) {
                    throw new RuntimeException('Puedes adjuntar como máximo 5 archivos.');
                }

                $finfo = new finfo(FILEINFO_MIME_TYPE);

                for ($i = 0; $i < $total; $i++) {
                    if ($archivos['error'][$i] === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }

                    if ($archivos['error'][$i] !== UPLOAD_ERR_OK) {
                        throw new RuntimeException('No se ha podido subir un archivo.');
                    }

                    if ($archivos['size'][$i] > 10 * 1024 * 1024) {
                        throw new RuntimeException(
                            'Cada archivo puede ocupar como máximo 10 MB.'
                        );
                    }

                    $tipo = $finfo->file($archivos['tmp_name'][$i]);

                    if (!in_array($tipo, $permitidos, true)) {
                        throw new RuntimeException(
                            'Hay un archivo con un formato no permitido.'
                        );
                    }

                    $archivosValidos[] = [
                        'tmp' => $archivos['tmp_name'][$i],
                        'nombre' => basename($archivos['name'][$i]),
                        'tipo' => $tipo
                    ];
                }
            }

            $carpeta = __DIR__ . '/../uploads/respuestas/';

            if (!is_dir($carpeta) && !mkdir($carpeta, 0755, true)) {
                throw new RuntimeException('No se pudo crear la carpeta de archivos.');
            }

            foreach ($archivosValidos as $archivo) {
                $extension = strtolower(
                    pathinfo($archivo['nombre'], PATHINFO_EXTENSION)
                );

                $nombreGuardado = bin2hex(random_bytes(16))
                    . ($extension !== '' ? '.' . $extension : '');

                $destino = $carpeta . $nombreGuardado;

                if (!move_uploaded_file($archivo['tmp'], $destino)) {
                    throw new RuntimeException('No se pudo guardar un archivo.');
                }

                $rutaRelativa = 'uploads/respuestas/' . $nombreGuardado;

                $archivosSubidos[] = __DIR__ . '/../' . $rutaRelativa;

                $archivosGuardados[] = [
                    'nombre' => $archivo['nombre'],
                    'ruta' => $rutaRelativa,
                    'tipo' => $archivo['tipo']
                ];
            }

            $nuevoId = PreguntasModel::crearRespuesta(
                $idUsuario,
                $idPregunta,
                $idRespuesta,
                $texto
            );

            foreach ($archivosGuardados as $archivo) {
                PreguntasModel::guardarArchivoRespuesta(
                    $nuevoId,
                    $archivo['nombre'],
                    $archivo['ruta'],
                    $archivo['tipo']
                );
            }

            header(
                'Location: index.php?controller=PreguntasController&accion=detalle&id='
                . $idPregunta . '#respuesta-' . $nuevoId
            );
            exit;

        } catch (Throwable $e) {
            foreach ($archivosSubidos as $ruta) {
                if (is_file($ruta)) {
                    unlink($ruta);
                }
            }

            error_log($e->getMessage());

            $_SESSION['errorRespuesta'] = $e->getMessage();

            header('Location: index.php?controller=PreguntasController&accion=detalle&id=' . $idPregunta);
            exit;
        }
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


    
    public function favorita() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json; charset=utf-8');

        try {
            $usuario_id = ($_SESSION['usuario']->id ?? 0);

            $data = json_decode(file_get_contents('php://input'), true);

            $pregunta_id = filter_var($data['idPregunta'] ?? null);

            $favorita = $data['favorita'] ?? null;

            if ($favorita) {
                PreguntasModel::anadirFavoritos($pregunta_id, $usuario_id);
            } else {
                PreguntasModel::eliminarFavoritos($pregunta_id, $usuario_id);
            }

            echo json_encode(['ok' => true, 'favorita' => $favorita]);

        } catch (Throwable $e) {
            error_log($e->getMessage());
        }
    }

    public function anadirFavoritos() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json; charset=utf-8');

        $usuario_id = (int) ($_SESSION['usuario']->id ?? 0);
        $data = json_decode(file_get_contents('php://input'), true);
        $pregunta_id = (int) ($data['pregunta_id'] ?? 0);

        try {
            PreguntasModel::anadirFavoritos($pregunta_id, $usuario_id);
        } catch (Throwable $e) {
            echo "Error al añadir a favoritos: " . $e->getMessage();
        }
    }

    public function eliminarFavoritos() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json; charset=utf-8');

        $usuario_id = (int) ($_SESSION['usuario']->id ?? 0);
        $data = json_decode(file_get_contents('php://input'), true);
        $pregunta_id = (int) ($data['pregunta_id'] ?? 0);

        try {
            PreguntasModel::eliminarFavoritos($pregunta_id, $usuario_id);
        } catch (Throwable $e) {
            echo "Error al eliminar de favoritos: " . $e->getMessage();
        }
    }


    public function buscar(){
        if($_POST["busqueda"] == ''):
            $this -> index();
        else:
            $busqueda = '%' . $_POST["busqueda"] . '%';
            $preguntas = PreguntasModel::mostrarPorNombre($_SESSION['usuario']->id, $busqueda);
            $preguntasPorTema = $this->preguntasPorTema();
            $orden = 'Más recientes';
        endif;

        $this->render('preguntas.view.php', [
            'preguntas' => $preguntas,
            'preguntasPorTema' => $preguntasPorTema,
            'order' => $orden
        ]);
    }

    public function buscarPorTema(){
        $tema = trim((string) ($_POST['tema'] ?? ''));
        $orden = $_POST['ordenar'] ?? null;
        if($tema === 'todas'){
            $preguntas = PreguntasModel::getAll($_SESSION['usuario']->id, $orden);
        }elseif($tema === 'favoritas'){
            $preguntas = PreguntasModel::mostrarFavoritas($_SESSION['usuario']->id, $orden);
        }else{
            $preguntas = PreguntasModel::buscarPorTema($_SESSION['usuario']->id, $tema, $orden);
        }
        
        $preguntasPorTema = $this->preguntasPorTema();

        $this->render('preguntas.view.php', [
            'preguntas' => $preguntas,
            'preguntasPorTema' => $preguntasPorTema,
            'temaActual' => $tema !== '' ? $tema : 'todas',
            'orden' => $orden
        ]);
    }
}