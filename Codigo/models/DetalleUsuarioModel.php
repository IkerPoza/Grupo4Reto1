<?php
require_once __DIR__ . '/Database.php';

class DetalleUsuarioModel {
    public static function getById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function update($id, $datos) {
        $db = Database::getConnection();

        $sql = "UPDATE usuario SET 
                    nombre = :nombre, 
                    apellidos = :apellidos, 
                    area = :area, 
                    email = :email, 
                    tipo = :tipo";

        $params = [
            ':nombre'    => $datos['nombre'],
            ':apellidos' => $datos['apellidos'],
            ':area'      => $datos['area'],
            ':email'     => $datos['email'],
            ':tipo'      => $datos['tipo'],
            ':id'        => $id
        ];

        if (!empty($datos['foto'])) {
            $sql .= ", foto = :foto";
            $params[':foto'] = $datos['foto'];
        }

        $sql .= " WHERE id = :id";

        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }
}