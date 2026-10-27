DROP TABLE IF EXISTS archivoRespuesta
DROP TABLE IF EXISTS preguntaGuardada;
DROP TABLE IF EXISTS votoRespuesta;
DROP TABLE IF EXISTS respuestaFavorita;
DROP TABLE IF EXISTS respuesta;
DROP TABLE IF EXISTS pregunta;
DROP TABLE IF EXISTS usuario;


CREATE TABLE usuario (
    id INT NOT NULL AUTO_INCREMENT,
    tipo ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100),
    email VARCHAR(255) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    area VARCHAR(100),
    antiguedad INT DEFAULT 0,
    numPreguntas INT DEFAULT 0,
    numRespuestas INT DEFAULT 0,
    foto VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE pregunta (
    id INT NOT NULL AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    tema VARCHAR(100),
    descripcion TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    imagen VARCHAR(255) DEFAULT NULL;
    PRIMARY KEY (id),
    FOREIGN KEY (idUsuario) REFERENCES usuario(id) ON DELETE CASCADE
);

CREATE TABLE respuesta (
    id INT NOT NULL AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    idPregunta INT NOT NULL,
    idRespuesta int,
    texto TEXT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (idUsuario) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (idPregunta) REFERENCES pregunta(id) ON DELETE CASCADE,
    FOREIGN KEY (idRespuesta) REFERENCES respuesta(id) ON DELETE CASCADE
);

CREATE TABLE respuestaFavorita (
    id INT NOT NULL AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    idRespuesta INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idUsuario) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (idRespuesta) REFERENCES respuesta(id) ON DELETE CASCADE
);


CREATE TABLE votoRespuesta (
    id INT NOT NULL AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    idRespuesta INT NOT NULL,
    voto TINYINT NOT NULL,
    PRIMARY KEY (id), 
    CONSTRAINT chk_voto CHECK (voto IN (-1, 1)),
    CONSTRAINT uq_voto_usuario_respuesta UNIQUE (idUsuario, idRespuesta),
    FOREIGN KEY (idUsuario) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (idRespuesta) REFERENCES respuesta(id) ON DELETE CASCADE
);


CREATE TABLE preguntaGuardada (
    id INT NOT NULL AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    idPregunta INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idUsuario) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (idPregunta) REFERENCES pregunta(id) ON DELETE CASCADE
);


CREATE TABLE archivoRespuesta (
    id INT NOT NULL AUTO_INCREMENT,
    idRespuesta INT NOT NULL,
    nombreOriginal VARCHAR(255) NOT NULL,
    ruta VARCHAR(255) NOT NULL,
    tipoMime VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idRespuesta) REFERENCES respuesta(id) ON DELETE CASCADE
);
