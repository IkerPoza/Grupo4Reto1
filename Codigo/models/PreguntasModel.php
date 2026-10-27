<?php
require_once __DIR__ . '/Database.php';

class PreguntasModel {
    
    public static function getAll($usuario_id, $orden) {
        $orderBy = match ($orden) {
            'Más antiguos'       => 'p.fecha ASC',
            'Más respuestas' => 'num_respuestas DESC',
            'Menos respuestas'  => 'num_respuestas ASC',
            default           => 'p.fecha DESC',
        };
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
        ORDER BY $orderBy");
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

    public static function buscarPorTema($usuario_id, $tema, $orden) {
        $orderBy = match ($orden) {
            'Más antiguos'       => 'p.fecha ASC',
            'Más respuestas' => 'num_respuestas DESC',
            'Menos respuestas'  => 'num_respuestas ASC',
            default           => 'p.fecha DESC',
        };
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
        WHERE p.tema = :tema
        GROUP BY p.id, p.titulo, p.tema, p.descripcion, p.fecha,
                u.id, u.nombre, u.apellidos, pg.id
        ORDER BY $orderBy");
        $datos = [
            "usuario_id" => $usuario_id,
            "tema" => $tema
        ];

        $stmt->execute($datos);
        return $stmt->fetchAll();
    }

    public static function mostrarFavoritas($usuario_id, $orden) {
        $orderBy = match ($orden) {
            'Más antiguos'       => 'p.fecha ASC',
            'Más respuestas' => 'num_respuestas DESC',
            'Menos respuestas'  => 'num_respuestas ASC',
            default           => 'p.fecha DESC',
        };
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
            1 AS es_favorita
        FROM pregunta p
        INNER JOIN usuario u ON p.idUsuario = u.id
        INNER JOIN preguntaGuardada pg
                ON pg.idPregunta = p.id AND pg.idUsuario = :usuario_id
        LEFT JOIN respuesta r ON p.id = r.idPregunta
        GROUP BY p.id, p.titulo, p.tema, p.descripcion, p.fecha,
                u.id, u.nombre, u.apellidos
        ORDER BY $orderBy");
        $datos = [
            "usuario_id" => $usuario_id
        ];

        $stmt->execute($datos);
        return $stmt->fetchAll();
    }
    
    public static function getById($id) {
        
    }
    
    public static function create($datos) {
        
    }
    
    public static function deleteById($id) {
       $db = Database::getConnection();

        $stmt = $db->prepare("UPDATE respuesta SET idRespuesta = NULL WHERE idPregunta = :id");
        $stmt->execute([':id' => $id]);

        $stmt = $db->prepare("DELETE FROM respuesta WHERE idPregunta = :id");
        $stmt->execute([':id' => $id]);

        $stmt = $db->prepare("DELETE FROM preguntaGuardada WHERE idPregunta = :id");
        $stmt->execute([':id' => $id]);

        $stmt = $db->prepare("DELETE FROM pregunta WHERE id = :id");
        return $stmt->execute([':id' => $id]);
        
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

    public static function getDetalleById($id) {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT p.*, u.nombre AS autor_nombre, u.apellidos AS autor_apellidos, u.id AS autor_id FROM pregunta p JOIN usuario u ON p.idUsuario = u.id WHERE p.id = ?");
        
        $stmt->execute([$id]);
        
        return $stmt->fetch();
    }

    
    public static function getRespuestasByPregunta($idPregunta) {
        $db = Database::getConnection();

        $idUsuario = $_SESSION['usuario']->id ?? 0;

        $stmt = $db->prepare("SELECT r.id, r.idUsuario, r.idPregunta, r.idRespuesta, r.texto, r.fecha, u.nombre AS autor_nombre, u.apellidos AS autor_apellidos, COALESCE(v.totalVotos, 0) AS totalVotos, COALESCE(v.miVoto, 0) AS miVoto
            FROM respuesta r
            JOIN usuario u ON r.idUsuario = u.id
            LEFT JOIN ( SELECT idRespuesta, SUM(voto) AS totalVotos, MAX(
                        CASE WHEN idUsuario = ? THEN voto ELSE 0 END
                    ) AS miVoto
                        FROM votoRespuesta
                        GROUP BY idRespuesta
                    ) v ON v.idRespuesta = r.id
                    WHERE r.idPregunta = ?
                    ORDER BY r.fecha ASC, r.id ASC");

        $stmt->execute([$idUsuario, $idPregunta]);

        return $stmt->fetchAll();
    }

        
    
    public static function votarRespuesta($idUsuario, $idRespuesta, $voto) {
        $db = Database::getConnection();

        $idUsuario = $idUsuario;
        $idRespuesta = $idRespuesta;
        $voto = $voto;

        if (!$idUsuario || !$idRespuesta || !in_array($voto, [-1, 1], true)) {
            throw new InvalidArgumentException('Datos de voto no válidos.');
        }

        $stmt = $db->prepare("SELECT voto FROM votoRespuesta WHERE idUsuario = ? AND idRespuesta = ?");
        $stmt->execute([$idUsuario, $idRespuesta]);
        $actual = $stmt->fetchColumn();

        if ($actual !== false) {
            if ($actual === $voto) {
                $stmt = $db->prepare("DELETE FROM votoRespuesta WHERE idUsuario = ? AND idRespuesta = ?" );
                $stmt->execute([$idUsuario, $idRespuesta]);
                return 0;
            }

            $stmt = $db->prepare("UPDATE votoRespuesta SET voto = ? WHERE idUsuario = ? AND idRespuesta = ?" );
            $stmt->execute([$voto, $idUsuario, $idRespuesta]);
            return $voto;
        }

        $stmt = $db->prepare("INSERT INTO votoRespuesta (idUsuario, idRespuesta, voto) VALUES (?, ?, ?)" );
        $stmt->execute([$idUsuario, $idRespuesta, $voto]);
        return $voto;
    }

    
    public static function totalVotosRespuesta($idRespuesta) {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT COALESCE(SUM(voto), 0) FROM votoRespuesta WHERE idRespuesta = ?");

        $stmt->execute([$idRespuesta]);

        return $stmt->fetchColumn();
    }



    public static function crearRespuesta($idUsuario, $idPregunta, $idRespuesta, $texto) {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("INSERT INTO respuesta (idUsuario, idPregunta, idRespuesta, texto) VALUES (?, ?, ?, ?)");
        
        $stmt->execute([$idUsuario, $idPregunta, $idRespuesta ?: null, $texto]);
        
        return $db->lastInsertId();
    }

    public static function eliminarRespuesta($idRespuesta) {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("DELETE FROM respuesta WHERE id = ?");
        
        return $stmt->execute([$idRespuesta]);
    }
    
    public static function anadirFavoritos($pregunta_id, $usuario_id){
        $db = Database::getConnection();

        $stmt = $db->prepare("INSERT INTO preguntaguardada (idUsuario, idPregunta) VALUES (:usuario_id, :pregunta_id)");

        $datos = [
            "usuario_id" => $usuario_id,
            "pregunta_id" => $pregunta_id
        ];

        error_log(print_r($datos, true));
        $stmt->execute($datos);
    }

    public static function eliminarFavoritos($pregunta_id, $usuario_id){
        $db = Database::getConnection();

        $stmt = $db->prepare("DELETE FROM preguntaguardada WHERE idUsuario = :usuario_id AND idPregunta = :pregunta_id");

        $datos = [
            "usuario_id" => $usuario_id,
            "pregunta_id" => $pregunta_id
        ];
        $stmt->execute($datos);
    }
}