<?php 

require_once __DIR__ . '/Database.php';

class InicioModel {
    /* Busca el usuario por correo y comprueba la contraseña */
    public static function iniciarSesion($email, $password) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->execute(["email" => $email]);
        $usuario = $stmt->fetch();

        // Comprobar que el usuario existe y coincide la contraseña
        if ($usuario && $usuario->contrasena === $password) {
            return $usuario;
        }
        
        return false;
    }
}




