
<?php

require_once __DIR__ . '/Database.php';

// Comprueba si la petición viene del formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Indica que la respuesta será JSON
    header("Content-Type: application/json; charset=utf-8");

    // Recoge los datos enviados por JavaScript
    $datos = json_decode(file_get_contents("php://input"), true);

    // Comprueba que se hayan recibido datos válidos
    if (!is_array($datos)) {
        http_response_code(400);
        echo json_encode([
            "ok" => false,
            "mensaje" => "Los datos recibidos no son válidos."
        ]);
        exit;
    }

    // Guarda los datos recibidos
    $nombre = trim($datos["nombre"] ?? "");
    $apellidos = trim($datos["apellidos"] ?? "");
    $email = trim($datos["email"] ?? "");
    $area = $datos["area"] ?? "";
    $antiguedad = $datos["antiguedad"] ?? "";
    $password = $datos["password"] ?? "";

    // Comprueba que ningún campo esté vacío
    if (
        $nombre === "" ||
        $apellidos === "" ||
        $email === "" ||
        $area === "" ||
        $antiguedad === "" ||
        $password === ""
    ) {
        http_response_code(400);

        echo json_encode([
            "ok" => false,
            "mensaje" => "Debes rellenar todos los campos."
        ]);

        exit;
    }

    // Comprueba que el correo tenga un formato válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);

        echo json_encode([
            "ok" => false,
            "mensaje" => "El correo no es válido."
        ]);

        exit;
    }

    // Intenta registrar al empleado
    try {

        $db = getConnection();

        // Comprueba si el correo ya está registrado
        $stmt = $db->prepare(
            "SELECT id FROM usuario WHERE email = :email"
        );

        $stmt->execute(["email" => $email]);

        if ($stmt->fetch()) {
            http_response_code(409);

            echo json_encode([
                "ok" => false,
                "mensaje" => "Este correo ya está registrado."
            ]);

            exit;
        }

        // Cifra la contraseña antes de guardarla
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Inserta el nuevo empleado en la base de datos
        $stmt = $db->prepare(
            "INSERT INTO usuario
            (nombre, apellido, email, area, antiguedad, contraseña)
            VALUES
            (:nombre, :apellidos, :email, :area, :antiguedad, :contraseña)"
        );

        // Envía los datos a la consulta preparada
        $stmt->execute([
            "nombre" => $nombre,
            "apellidos" => $apellidos,
            "email" => $email,
            "area" => $area,
            "antiguedad" => $antiguedad,
            "contraseña" => $passwordHash
        ]);

        // Devuelve una respuesta de éxito
        echo json_encode([
            "ok" => true,
            "mensaje" => "Empleado registrado correctamente."
        ]);

    } catch (PDOException $e) {

        // Informa de que ha ocurrido un error en el registro
        http_response_code(500);

        echo json_encode([
            "ok" => false,
            "mensaje" => "No se ha podido registrar al empleado."
        ]);
    }

    exit;
}

?>
