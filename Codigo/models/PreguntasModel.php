<?php
require_once __DIR__ . '/Database.php';

class PreguntasModel {
    
    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->prepare("
        SELECT p.id AS pregunta_id,
            p.titulo AS pregunta_titulo,
            p.tema AS pregunta_tema,
            p.descripcion AS pregunta_descripcion,
            p.fecha AS pregunta_fecha,
            u.id AS autor_id,
            u.nombre AS autor_nombre,
            u.apellidos AS autor_apellidos,
            COUNT(r.id) AS num_respuestas
        FROM pregunta p
        INNER JOIN usuario u ON p.idUsuario = u.id
        LEFT JOIN respuesta r ON p.id = r.idPregunta
        GROUP BY p.id
        ORDER BY p.fecha DESC");
        $stmt->execute();
        return $stmt->fetchAll();
        
    }
    
    public static function getById($id) {
        
    }
    
    public static function create($datos) {
        
    }
    
    public static function deleteById($id) {
        
    }
    
    public static function deleteAll() {
        
    }
}