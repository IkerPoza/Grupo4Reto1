<?php 

require_once __DIR__ . '/Database.php';


// comprobar si la opeticion esta viniendo del formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json; charset=utf-8");

    $datos = json_decode(file_get_contents("php://input"), true);

    $email = trim($datos["email"] ?? "");
    $password = $datos["password"] ?? "";

    if ($email === "" || $password === "") {
        echo json_encode([
                        "ok" => false,
                        "mensaje" => "Debes rellenar todos los campos."
                        ]);
        exit;
    }

    // buscaar usuario + comorprobar la contraseña
    $usuario = iniciarSesion($email, $password);

    // comprobar si el inicio de seison es correcto
    if ($usuario) {
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


/* se busca usuario ppro correo y se cimprueba la contraseña  */
function iniciarSesion($email, $password) {
    $db = getConnection();
    $stmt = $db->prepare("SELECT * FROM usuario WHERE email = :email");
    $stmt->execute(["email" => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // comprobar que usuario existe y coincide la contraseñq
    if ($usuario && password_verify($password, $usuario["password"])) {
        return $usuario;
    }
    // devuelve false si datos no correctos
    return false;
}





