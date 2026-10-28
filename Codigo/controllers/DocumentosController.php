<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/DocumentosModel.php';

class DocumentosController extends BaseController{
    private function comprobarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['usuario']->id)) {
            $this->redirect('index.php');
        }
    }


    public function index(){
        $this->comprobarSesion();

        $orden = $_GET['orden'] ?? 'asc';
        $busqueda = trim($_GET['busqueda'] ?? '');
        $temaActual = $_GET['tema'] ?? 'todas';

        $temasPermitidos = [
            'todas',
            'Ingeniería y Diseño',
            'Normativa y Calidad',
            'Mantenimiento',
            'Sostenibilidad e innovación',
            'Formación y Recursos'
        ];

        if (!in_array($temaActual, $temasPermitidos, true)) {
            $temaActual = 'todas';
        }

        if ($busqueda !== '') {
            $documentos = DocumentosModel::buscar(
                $busqueda,
                $orden,
                $temaActual
            );
        } else {
            $documentos = DocumentosModel::getAll(
                $orden,
                $temaActual
            );
        }

        $preguntasPorTema = DocumentosModel::contarPorTema();

        $usuarioActual = $_SESSION['usuario'];

        // Mensaje de error de una subida fallida (se muestra una sola vez)
        $errorDocumentos = $_SESSION['errorDocumentos'] ?? null;
        unset($_SESSION['errorDocumentos']);

        $this->render('documentos.view.php', [
            'documentos' => $documentos,
            'preguntasPorTema' => $preguntasPorTema,
            'temaActual' => $temaActual,
            'orden' => $orden,
            'busqueda' => $busqueda,
            'usuarioActual' => $usuarioActual,
            'errorDocumentos' => $errorDocumentos
        ]);
    }


    public function store(){
        $this->comprobarSesion();

        $rutaGuardada = null;

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new RuntimeException('Método no permitido.');
            }

            // Si el archivo supera post_max_size, PHP vacía $_POST y $_FILES
            if (empty($_POST) && empty($_FILES) &&
                (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
                throw new RuntimeException(
                    'El archivo supera el tamaño máximo permitido por el servidor.'
                );
            }

            $titulo = trim($_POST['titulo'] ?? '');
            $tema = trim($_POST['tema'] ?? '');

            $temasPermitidos = [
                'Ingeniería y Diseño',
                'Normativa y Calidad',
                'Mantenimiento',
                'Sostenibilidad e innovación',
                'Formación y Recursos'
            ];

            if ($titulo === '' ||
                !in_array($tema, $temasPermitidos, true)) {
                throw new RuntimeException(
                    'Completa el título y selecciona una categoría válida.'
                );
            }

            if (!isset($_FILES['archivo'])) {
                throw new RuntimeException(
                    'Selecciona un archivo válido.'
                );
            }

            $errorSubida = $_FILES['archivo']['error'];

            if ($errorSubida === UPLOAD_ERR_INI_SIZE ||
                $errorSubida === UPLOAD_ERR_FORM_SIZE) {
                throw new RuntimeException(
                    'El archivo supera el tamaño máximo permitido por el servidor.'
                );
            }

            if ($errorSubida !== UPLOAD_ERR_OK) {
                throw new RuntimeException(
                    'Selecciona un archivo válido.'
                );
            }

            $archivo = $_FILES['archivo'];

            if ($archivo['size'] > 10 * 1024 * 1024) {
                throw new RuntimeException(
                    'El archivo no puede superar los 10 MB.'
                );
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $tipo = $finfo->file($archivo['tmp_name']);

            $tiposPermitidos = [
                'application/pdf' => 'pdf',
                'text/plain' => 'txt',
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'application/msword' => 'doc',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    => 'docx',
                'application/vnd.ms-excel' => 'xls',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    => 'xlsx',
                'application/vnd.ms-powerpoint' => 'ppt',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                    => 'pptx'
            ];

            $extensionFinal = $tiposPermitidos[$tipo] ?? null;

            // Según la versión de libmagic, los archivos de Office a veces se
            // detectan con un tipo genérico (zip, octet-stream...). En ese
            // caso se acepta si la extensión es de Office. La extensión
            // guardada sale siempre de esta lista, nunca del nombre subido.
            if ($extensionFinal === null) {
                $tiposGenericos = [
                    'application/zip',
                    'application/octet-stream',
                    'application/vnd.ms-office',
                    'application/CDFV2',
                    'application/x-ole-storage'
                ];
                $extensionesOffice = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
                $extensionNombre = strtolower(
                    pathinfo($archivo['name'], PATHINFO_EXTENSION)
                );

                if (in_array($tipo, $tiposGenericos, true) &&
                    in_array($extensionNombre, $extensionesOffice, true)) {
                    $extensionFinal = $extensionNombre;
                }
            }

            if ($extensionFinal === null) {
                throw new RuntimeException(
                    'El formato del archivo no está permitido.'
                );
            }

            $carpeta = __DIR__ . '/../uploads/documentos/';

            if (!is_dir($carpeta) &&
                !mkdir($carpeta, 0755, true)) {
                throw new RuntimeException(
                    'No se pudo crear la carpeta de documentos.'
                );
            }

            $nombreOriginal = basename($archivo['name']);

            $nombreGuardado = bin2hex(random_bytes(16))
                . '.' . $extensionFinal;

            $destino = $carpeta . $nombreGuardado;

            if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
                throw new RuntimeException(
                    'No se pudo guardar el archivo.'
                );
            }

            $rutaGuardada = 'uploads/documentos/' . $nombreGuardado;

            DocumentosModel::insertar([
                'titulo' => $titulo,
                'tema' => $tema,
                'nombreOriginal' => $nombreOriginal,
                'ruta' => $rutaGuardada,
                'idUsuario' => $_SESSION['usuario']->id
            ]);

            $this->redirect('index.php?controller=DocumentosController&accion=index');

        } catch (Throwable $e) {
            if ($rutaGuardada !== null) {
                $archivoGuardado = __DIR__ . '/../' . $rutaGuardada;

                if (is_file($archivoGuardado)) {
                    unlink($archivoGuardado);
                }
            }

            error_log($e->getMessage());

            $_SESSION['errorDocumentos'] = $e instanceof RuntimeException
                ? $e->getMessage()
                : 'No se ha podido subir el documento. Inténtalo de nuevo.';

            $this->redirect('index.php?controller=DocumentosController&accion=index');
        }
    }

 
    public function eliminar(){
        $this->comprobarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=DocumentosController&accion=index');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->redirect('index.php?controller=DocumentosController&accion=index');
        }

        $documento = DocumentosModel::getById($id);

        if (!$documento) {
            $this->redirect('index.php?controller=DocumentosController&accion=index');
        }

        $idUsuario =  $_SESSION['usuario']->id;
        $esAdmin = ($_SESSION['usuario']->tipo ?? '') === 'admin';

        if (!$esAdmin &&  $documento->idUsuario !== $idUsuario) {
            http_response_code(403);
            exit('No tienes permiso para eliminar este documento.');
        }

        if (DocumentosModel::deleteById($id)) {
            $ruta = __DIR__ . '/../' . $documento->ruta;

            if (is_file($ruta)) {
                unlink($ruta);
            }
        }

        $this->redirect('index.php?controller=DocumentosController&accion=index');
    }
}
