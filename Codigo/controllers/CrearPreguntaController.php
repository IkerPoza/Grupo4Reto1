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
        $rutaImagen = null;

        try {
            setcookie("error", "", time() - 3600);
            
            $usuario = $_SESSION['usuario'];

            $datos = [
                "titulo" => trim($_POST["titulo"] ?? ""),
                "descripcion" => trim($_POST["descripcion"] ?? ""),
                "tema" => $_POST["tema"] ?? "",
                "idUsuario" => $usuario->id,
                "imagen" => null
            ];

            if ($datos["titulo"] === "" || $datos["descripcion"] === "" || $datos["tema"] === "") {
                throw new RuntimeException("Completa todos los campos.");
            }

            if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
                    throw new RuntimeException("No se ha podido subir la imagen.");
                }

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $tipo = $finfo->file($_FILES["imagen"]["tmp_name"]);

                $tiposPermitidos = [
                    "image/jpeg" => "jpg",
                    "image/png"  => "png",
                    "image/webp" => "webp",
                    "image/gif"  => "gif"
                ];

                if (!isset($tiposPermitidos[$tipo])) {
                    throw new RuntimeException(
                        "El archivo debe ser una imagen JPG, PNG, WEBP o GIF."
                    );
                }

                $carpeta = __DIR__ . "/../uploads/preguntas/";

                if (!is_dir($carpeta) && !mkdir($carpeta, 0755, true)) {
                    throw new RuntimeException(
                        "No se ha podido crear la carpeta de imágenes."
                    );
                }

                $nombreArchivo = bin2hex(random_bytes(16)) . "." . $tiposPermitidos[$tipo];

                $destino = $carpeta . $nombreArchivo;

                if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $destino)) {
                    throw new RuntimeException(
                        "No se ha podido guardar la imagen."
                    );
                }

                $rutaImagen = "uploads/preguntas/" . $nombreArchivo;
                $datos["imagen"] = $rutaImagen;
            }

            CrearPreguntaModel::insertarPregunta($datos);

            $this->redirect('index.php');

        } catch (Throwable $e) {
            if ($rutaImagen !== null) {
                $archivo = __DIR__ . "/../" . $rutaImagen;

                if (is_file($archivo)) {
                    unlink($archivo);
                }
            }

            error_log($e->getMessage());

            setcookie("error", "Error al crear la pregunta.", 0);
            $this->render('crearPregunta.view.php');
        }
    }


    public function show() {
        
    }

    public function destroy() {
        
    }
    

    public function destroyAll() {
        
    }

}