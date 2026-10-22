<?php
require_once __DIR__ . '/Database.php';

class PreguntasModel {
    
    public static function getAll($usuario_id) {
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
            COUNT(DISTINCT r.id) AS num_respuestas,
            CASE WHEN pg.id IS NOT NULL THEN 1 ELSE 0 END AS es_favorita
        FROM pregunta p
        INNER JOIN usuario u ON p.idUsuario = u.id
        LEFT JOIN respuesta r ON p.id = r.idPregunta
        LEFT JOIN preguntaGuardada pg
            ON pg.idPregunta = p.id AND pg.idUsuario = :usuario_id
        GROUP BY p.id, p.titulo, p.tema, p.descripcion, p.fecha,
                u.id, u.nombre, u.apellidos, pg.id
        ORDER BY p.fecha DESC");
        $datos = [
            "usuario_id" => $usuario_id
        ];

        $stmt->execute($datos);
        return $stmt->fetchAll();
    }

    public static function mostrarPorNombre($usuario_id, $busqueda) {
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
            COUNT(DISTINCT r.id) AS num_respuestas,
            CASE WHEN pg.id IS NOT NULL THEN 1 ELSE 0 END AS es_favorita
        FROM pregunta p
        INNER JOIN usuario u ON p.idUsuario = u.id
        LEFT JOIN respuesta r ON p.id = r.idPregunta
        LEFT JOIN preguntaGuardada pg
            ON pg.idPregunta = p.id AND pg.idUsuario = :usuario_id
        WHERE p.titulo LIKE :busqueda
        OR p.descripcion LIKE :busqueda
        GROUP BY p.id, p.titulo, p.tema, p.descripcion, p.fecha,
                u.id, u.nombre, u.apellidos, pg.id
        ORDER BY p.fecha DESC");
        $datos = [
            "usuario_id" => $usuario_id,
            "busqueda" => $busqueda
        ];

        $stmt->execute($datos);
        return $stmt->fetchAll();
    }

    public static function preguntasPorTema($usuario_id){
        $db = Database::getConnection();
        $stmt = $db->prepare("
        SELECT
            COUNT(*) AS total_todas,
            (SELECT COUNT(*) FROM preguntaGuardada WHERE idUsuario = :usuario_id) AS total_favoritos,
            IFNULL(SUM(tema = 'Ingeniería y Diseño'), 0) AS total_ingenieria,
            IFNULL(SUM(tema = 'Normativa y Calidad'), 0) AS total_normativa,
            IFNULL(SUM(tema = 'Mantenimiento'), 0) AS total_mantenimiento,
            IFNULL(SUM(tema = 'Sostenibilidad e innovación'), 0) AS total_sostenibilidad,
            IFNULL(SUM(tema = 'Formación y Recursos'), 0) AS total_formacion
        FROM pregunta");
        $datos = [
            "usuario_id" => $usuario_id
        ];

        $stmt->execute($datos);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public static function getById($id) {
        
    }
    
    public static function create($datos) {
        
    }
    
    public static function deleteById($id) {
        
    }
    
    public static function deleteAll() {
        
    }

    public static function getByUsuario($id) {
        $db = Database::getConnection();

        $stmt = $db -> prepare("SELECT p.*, (SELECT COUNT(*) FROM respuesta r WHERE r.idPregunta=p.id) AS totalRespuestas, EXISTS(SELECT 1 FROM preguntaGuardada g WHERE g.idPregunta=p.id AND g.idUsuario=?) AS guardada FROM pregunta p WHERE p.idUsuario=? ORDER BY p.fecha DESC");
        
        $stmt->execute([$id, $id]);
        
        return $stmt->fetchAll();
    }

    public static function getGuardadas($id) {
        $db = Database::getConnection();
        
        $stmt = $db -> prepare("SELECT p.*, (SELECT COUNT(*) FROM respuesta r WHERE r.idPregunta=p.id) AS totalRespuestas, 1 AS guardada FROM preguntaGuardada g JOIN pregunta p ON p.id=g.idPregunta WHERE g.idUsuario=? ORDER BY p.fecha DESC"); 
        
        $stmt->execute([$id]);
        
        return $stmt->fetchAll();
    }

    public static function anadirFavoritos($pregunta_id, $usuario_id){
        $db = Database::getConnection();

        $stmt = $db->prepare(
            "INSERT INTO preguntaguardada (idUsuario, idPregunta)
            VALUES (:usuario_id, :pregunta_id)"
        );

        $datos = [
            "usuario_id" => $usuario_id,
            "pregunta_id" => $pregunta_id
        ];

        error_log(print_r($datos, true));
        $stmt->execute($datos);
    }

    public static function eliminarFavoritos($pregunta_id, $usuario_id){
        $db = Database::getConnection();

        $stmt = $db->prepare(
            "DELETE FROM preguntaguardada WHERE idUsuario = :usuario_id AND idPregunta = :pregunta_id"
        );

        $datos = [
            "usuario_id" => $usuario_id,
            "pregunta_id" => $pregunta_id
        ];
        $stmt->execute($datos);
    }
}