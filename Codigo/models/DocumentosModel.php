<?php

require_once __DIR__ . '/Database.php';

class DocumentosModel{

    public static function getAll($orden = 'ASC', $tema = 'todas'){
        $db = Database::getConnection();

        $ordenSql = strtoupper($orden) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT
                d.id AS documento_id,
                d.titulo AS documento_titulo,
                d.tema AS documento_tema,
                d.nombreOriginal AS documento_nombre,
                d.ruta AS documento_ruta,
                d.fecha AS documento_fecha,
                u.id AS autor_id,
                CONCAT(u.nombre, ' ', COALESCE(u.apellidos, ''))
                    AS autor_nombre
            FROM documento d
            INNER JOIN usuario u ON d.idUsuario = u.id";

        if ($tema !== 'todas') {
            $sql .= " WHERE d.tema = :tema";
        }

        $sql .= " ORDER BY d.titulo $ordenSql";

        $stmt = $db->prepare($sql);

        if ($tema !== 'todas') {
            $stmt->bindValue(':tema', $tema);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function buscar($busqueda, $orden = 'ASC', $tema = 'todas') {
        $db = Database::getConnection();

        $ordenSql = strtoupper($orden) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT
                d.id AS documento_id,
                d.titulo AS documento_titulo,
                d.tema AS documento_tema,
                d.nombreOriginal AS documento_nombre,
                d.ruta AS documento_ruta,
                d.fecha AS documento_fecha,
                u.id AS autor_id,
                CONCAT(u.nombre, ' ', COALESCE(u.apellidos, ''))
                    AS autor_nombre
            FROM documento d
            INNER JOIN usuario u ON d.idUsuario = u.id
            WHERE (
                d.titulo LIKE :titulo
                OR d.tema LIKE :temaBusqueda
                OR u.nombre LIKE :autor
                OR u.apellidos LIKE :apellidos
            )";

        if ($tema !== 'todas') {
            $sql .= " AND d.tema = :temaFiltro";
        }

        $sql .= " ORDER BY d.titulo $ordenSql";

        $stmt = $db->prepare($sql);

        $like = '%' . $busqueda . '%';

        $stmt->bindValue(':titulo', $like);
        $stmt->bindValue(':temaBusqueda', $like);
        $stmt->bindValue(':autor', $like);
        $stmt->bindValue(':apellidos', $like);

        if ($tema !== 'todas') {
            $stmt->bindValue(':temaFiltro', $tema);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public static function getById($id){
        $db = Database::getConnection();

        $stmt = $db->prepare(" SELECT * FROM documento WHERE id = :id");

        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public static function insertar($datos) {
        $db = Database::getConnection();

        $stmt = $db->prepare("INSERT INTO documento(titulo, tema, nombreOriginal, ruta, idUsuario) VALUES(:titulo, :tema, :nombreOriginal, :ruta, :idUsuario)");

        $stmt->execute([
            ':titulo' => $datos['titulo'],
            ':tema' => $datos['tema'],
            ':nombreOriginal' => $datos['nombreOriginal'],
            ':ruta' => $datos['ruta'],
            ':idUsuario' => $datos['idUsuario']
        ]);

        return $db->lastInsertId();
    }


    public static function contarPorTema(){
        $db = Database::getConnection();

        $stmt = $db->query("SELECT
                COUNT(*) AS total_todas,

                COALESCE(SUM(
                    tema = 'Ingeniería y Diseño'
                ), 0) AS total_ingenieria,

                COALESCE(SUM(
                    tema = 'Normativa y Calidad'
                ), 0) AS total_normativa,

                COALESCE(SUM(
                    tema = 'Mantenimiento'
                ), 0) AS total_mantenimiento,

                COALESCE(SUM(
                    tema = 'Sostenibilidad e innovación'
                ), 0) AS total_sostenibilidad,

                COALESCE(SUM(
                    tema = 'Formación y Recursos'
                ), 0) AS total_formacion

            FROM documento");

        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public static function deleteById($id){
        $db = Database::getConnection();

        $stmt = $db->prepare("DELETE FROM documento  WHERE id = :id");

        return $stmt->execute([':id' => $id]);
    }
}
