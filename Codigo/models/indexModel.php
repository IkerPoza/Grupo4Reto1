<?php 

require_once __DIR__ . '/Database.php';

<?php 
require_once __DIR__ . '/Database.php';

/* Busca el usuario por correo y comprueba la contraseña */
function iniciarSesion($email, $password) {
    $db = getConnection();
    $stmt = $db->prepare("SELECT * FROM usuario WHERE email = :email");
    $stmt->execute(["email" => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Comprobar que el usuario existe y coincide la contraseña
    if ($usuario && password_verify($password, $usuario["password"])) {
        return $usuario;
    }
    
    return false;
}




