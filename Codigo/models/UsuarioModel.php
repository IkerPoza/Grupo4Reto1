<?php
require_once __DIR__ . '/Database.php';

class UsuarioModel {

    // todos los usuarios
    public static function getAll() {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM usuario");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // usuario por id
    public static function getById($id) {
        $db =  Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM usuario WHERE id = :id");

        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    // actualizar datos del perfil
    public static function update($id, $datos) {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("UPDATE usuario SET nombre = :nombre, apellidos = :apellidos, area = :area, contacto = :contacto, antiguedad = :antiguedad WHERE id = :id");
        
        $data =[
            'nombre'     => $datos['nombre'],
            'apellidos'  => $datos['apellidos'],
            'area'       => $datos['area'],
            'contacto'   => $datos['contacto'],
            'antiguedad' => $datos['antiguedad'],
            'id'         => $id
        ];
        
        return $stmt->execute($data);
    }


    // estadísticas del perfil
    public static function getEstadisticas($id) {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("SELECT(SELECT COUNT(*) FROM pregunta  WHERE idUsuario = :u1) AS preguntas,
                            (SELECT COUNT(*) FROM respuesta WHERE idUsuario = :u2) AS respuestas,
                            (SELECT COUNT(*) FROM respuestaFavorita f JOIN respuesta r ON r.id = f.idRespuesta WHERE r.idUsuario = :u3) AS votosRecibidos");
        
        $stmt->execute(['u1' => $id, 'u2' => $id, 'u3' => $id]);

        return $stmt->fetch();
    }


   // eliminar usuario por id
    public static function deleteById($id) {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("DELETE FROM usuario WHERE id = :id");
        
        return $stmt->execute(['id' => $id]);
    }

}