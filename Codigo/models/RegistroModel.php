
<?php

require_once __DIR__ . '/Database.php';

/* Comprueba si el correo ya existe en la base de datos */
function existeEmail($email) {
    $db = getConnection();
    $stmt = $db->prepare("SELECT id FROM usuario WHERE email = :email");
    $stmt->execute(["email" => $email]);
    return (bool) $stmt->fetch();
}

/* Inserta el nuevo usuario con la contraseña cifrada */
function registrarUsuario($datos) {
    $db = getConnection();
    
    $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

    $stmt = $db->prepare(
        "INSERT INTO usuario (nombre, apellido, email, area, antiguedad, contraseña)
         VALUES (:nombre, :apellidos, :email, :area, :antiguedad, :contrasena)"
    );

    return $stmt->execute([
        "nombre"     => $datos['nombre'],
        "apellidos"  => $datos['apellidos'],
        "email"      => $datos['email'],
        "area"       => $datos['area'],
        "antiguedad" => $datos['antiguedad'],
        "contrasena" => $passwordHash
    ]);
}

