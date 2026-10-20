<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/RegistroModel.php';

class RegistroController extends BaseController {

    public function index() {
        require_once __DIR__ . '/../views/registro.view.html';

    }

    public function store() {
        header("Content-Type: application/json; charset=utf-8");

        $datos = json_decode(file_get_contents("php://input"), true);

        if (!is_array($datos)) {
            http_response_code(400);
            echo json_encode(["ok" => false, "mensaje" => "Los datos recibidos no son válidos."]);
            exit;
        }

        $nombre     = trim($datos["nombre"] ?? "");
        $apellidos  = trim($datos["apellidos"] ?? "");
        $email      = trim($datos["email"] ?? "");
        $area       = $datos["area"] ?? "";
        $antiguedad = $datos["antiguedad"] ?? "";
        $password   = $datos["password"] ?? "";

        // Validar que ningún campo esté vacío
        if ($nombre === "" || $apellidos === "" || $email === "" || $area === "" || $antiguedad === "" || $password === "") {
            http_response_code(400);
            echo json_encode(["ok" => false, "mensaje" => "Debes rellenar todos los campos."]);
            exit;
        }

        // Validar formato del email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["ok" => false, "mensaje" => "El correo no tiene un formato válido."]);
            exit;
        }

        try {
            // Verificar si el email ya existe
            if (existeEmail($email)) {
                http_response_code(409);
                echo json_encode(["ok" => false, "mensaje" => "Este correo ya está registrado."]);
                exit;
            }

            // Guardar usuario
            $exito = registrarUsuario([
                'nombre'     => $nombre,
                'apellidos'  => $apellidos,
                'email'      => $email,
                'area'       => $area,
                'antiguedad' => $antiguedad,
                'password'   => $password
            ]);

            if ($exito) {
                echo json_encode(["ok" => true, "mensaje" => "Empleado registrado correctamente."]);
            } else {
                http_response_code(500);
                echo json_encode(["ok" => false, "mensaje" => "No se pudo completar el registro."]);
            }

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["ok" => false, "mensaje" => "Error de servidor al registrar al empleado."]);
        }

        exit;
        
    }

    public function show() {
        
    }

    public function destroy() {
        
    }
    

    public function destroyAll() {
        
    }

}

