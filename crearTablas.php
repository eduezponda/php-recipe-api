<?php
    include 'basededatos.php';

    $con = conexion();

    $consulta = "DROP TABLE IF EXISTS receta, requerimiento, composicion, comida, cocina, dieta;";
    $resultado = $con->query($consulta);

    $consulta = "CREATE TABLE comida (
        id INT AUTO_INCREMENT PRIMARY KEY,
        query VARCHAR(50),
        minutos INT
    );
    ";
    $resultado = $con->query($consulta);

    $consulta = "CREATE TABLE requerimiento (
        id INT AUTO_INCREMENT PRIMARY KEY,
        carbohidratos INT,
        proteinas INT,
        grasas INT,
        calorias INT
    );
    ";
    $resultado = $con->query($consulta);

    $consulta = "CREATE TABLE composicion (
        id INT AUTO_INCREMENT PRIMARY KEY,
        colesterol INT,
        azucar INT
    );
    ";
    $resultado = $con->query($consulta);

    $consulta = "CREATE TABLE receta (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(100) NOT NULL,
        imagen VARCHAR(255),
        resumen VARCHAR(10000),
        id_requerimiento INT,
        id_composicion INT,
        id_comida INT,
        FOREIGN KEY (id_requerimiento) REFERENCES requerimiento(id),
        FOREIGN KEY (id_composicion) REFERENCES composicion(id),
        FOREIGN KEY (id_comida) REFERENCES comida(id)
    );
    ";
    $resultado = $con->query($consulta);

    $consulta = "CREATE TABLE cocina (
        id_receta INT,
        cocina VARCHAR(50),
        PRIMARY KEY (id_receta, cocina)
    );
    ";
    $resultado = $con->query($consulta);

    $consulta = "CREATE TABLE dieta (
        id_receta INT,
        dieta VARCHAR(50),
        PRIMARY KEY (id_receta, dieta)
    );
    ";
    $resultado = $con->query($consulta);

    echo 'Tablas creadas correctamente';
?>
