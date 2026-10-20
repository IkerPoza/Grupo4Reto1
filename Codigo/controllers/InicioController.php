<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/IndexModel.php';

class IndexController extends BaseController {

    public function index() {
        require_once __DIR__ . '/../views/index.view.html';

    }

    public function store() {
        header("Content-Type: application/json; charset=utf-8");

        // leer datos JSON enviados desde index.script.js
        $datos = json_decode(file_get_contents("php://input"), true);

        $email = trim($datos["email"] ?? "");
        $password = $datos["password"] ?? "";

        if (empty($email) || empty($password)) {
            echo json_encode([
                "ok" => false,
                "mensaje" => "Debes rellenar todos los campos."
            ]);
            exit;
        }

        // consultar al modelo
        $usuario = iniciarSesion($email, $password);

        if ($usuario) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['usuario'] = $usuario;

            echo json_encode([
                "ok" => true,
                "mensaje" => "Inicio de sesión correcto."
            ]);
        } else {
            echo json_encode([
                "ok" => false,
                "mensaje" => "Correo o contraseña incorrectos."
            ]);
        }
        exit;
    }
        
    }

    public function show() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php");
            exit;
        }

        $usuario = $_SESSION['usuario'];
        require_once __DIR__ . '/../views/usuario.php';
    }

    public function destroy() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['usuario']);
        session_destroy();

        echo json_encode([
            "ok" => true,
            "mensaje" => "Sesión cerrada correctamente."
        ]);
        exit;
    }
    

    public function destroyAll() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array();
        session_destroy();
        exit;
    }

