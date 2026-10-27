<?php
require_once __DIR__ . '/Database.php';

class ListarUsuariosModel {
    
    public static function getAll($orden = 'ASC') {
        $ordenSql = strtoupper($orden) === 'DESC' ? 'DESC' : 'ASC';
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuario ORDER BY nombre {$ordenSql}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function buscar($busqueda, $orden = 'ASC') {
        $ordenSql = strtoupper($orden) === 'DESC' ? 'DESC' : 'ASC';
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuario
                            WHERE nombre LIKE :b1 OR apellidos LIKE :b2 OR email LIKE :b3
                            ORDER BY nombre {$ordenSql}");
        $like = '%' . $busqueda . '%';
        $stmt->execute([':b1' => $like, ':b2' => $like, ':b3' => $like]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function deleteById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM usuario WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    
}