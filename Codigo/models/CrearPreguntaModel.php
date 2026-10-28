<?php

require_once __DIR__ . '/Database.php';

class CrearPreguntaModel {

    public static function insertarPregunta($datos) {
        $db = Database::getConnection();

        $stmt = $db->prepare("INSERT INTO pregunta(titulo, descripcion, tema, idUsuario, imagen) VALUES (:titulo, :descripcion, :tema, :idUsuario, :imagen)" );

        $stmt->execute($datos);
    }
}
