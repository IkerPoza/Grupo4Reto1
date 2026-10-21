<?php

require_once __DIR__ . '/Database.php';

class RegistroModel {
    /* Comprueba si el correo ya existe en la base de datos */
    public static function existeEmail($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id FROM usuario WHERE email = :email");
        $stmt->execute(["email" => $email]);
        return (bool) $stmt->fetch();
    }

    /* Inserta el nuevo usuario con la contraseña cifrada */
    public static function registrarUsuario($datos) {
        $db = Database::getConnection();

        $stmt = $db->prepare(
            "INSERT INTO usuario (nombre, apellidos, email, area, antiguedad, contrasena)
            VALUES (:nombre, :apellidos, :email, :area, :antiguedad, :password)"
        );

        $stmt->execute($datos);
    }
}

