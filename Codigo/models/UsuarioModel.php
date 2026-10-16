<?php
require_once __DIR__ . '/Database.php';

class UsuarioModel {

    // todos los usuarios
    public static function getAll() {
        $db = getConnection();

        $stmt = $db->prepare("SELECT * FROM usuario");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // usuario por id
    public static function getById($id) {
        $db = getConnection();

        $stmt = $db->prepare("SELECT * FROM usuario WHERE id = :id");

        $data = ["id" => $id];

        $stmt->execute($data);

        return $stmt->fetch();
    }

    // actualizar datos del perfil
    public static function update($id, $datos) {
        $db = getConnection();

        $stmt = $db->prepare("UPDATE usuario SET nombre = :nombre, area = :area, contacto = :contacto, apellido = :apellido WHERE id = :id");

        $data = [
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'area' => $datos['area'],
            'contacto' => $datos['contacto'],
            'id' => $id
        ];

        $stmt->execute($data);
    }

    // eliminar usuario por id
    public static function deleteById($id) {
        $db = getConnection();

        $stmt = $db->prepare("DELETE FROM usuario WHERE id = :id");

        $data = ["id" => $id];

        return $stmt->execute($data);
    }

    // eliminar todos los usuarios
    public static function deleteAll() {
        $db = getConnection();

        $stmt = $db->prepare("DELETE FROM usuario");

        return $stmt->execute();
    }
}