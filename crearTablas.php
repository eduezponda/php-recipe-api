<?php
    include 'basededatos.php';

    $con = conexion();

    $consulta = "CREATE TABLE comida (
        id NUMBER AUTO_INCREMENT PRIMARY KEY,
        query VARCHAR(50),
        minutos NUMBER
    );
    ";
    $resultado = $con->query($consulta);

    $consulta = "CREATE TABLE requerimiento (
        id NUMBER AUTO_INCREMENT PRIMARY KEY,
        carbohidratos NUMBER,
        proteinas NUMBER,
        grasas NUMBER,
        calorias NUMBER
    );
    ";
    $resultado = $con->query($consulta);

    $consulta = "CREATE TABLE composicion (
        id NUMBER AUTO_INCREMENT PRIMARY KEY,
        colesterol NUMBER,
        azucar NUMBER,
    );
    ";
    $resultado = $con->query($consulta);

    $consulta = "CREATE TABLE receta (
        id NUMBER AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(50) NOT NULL,
        imagen VARCHAR(255),
        resumen VARCHAR(10000),
        id_requerimiento NUMBER,
        id_composicion NUMBER,
        id_comida NUMBER,
        FOREIGN KEY (id_requerimiento) REFERENCES requerimiento(id),
        FOREIGN KEY (id_composicion) REFERENCES composicion(id),
        FOREIGN KEY (id_comida) REFERENCES comida(id)
    );
    ";
    $resultado = $con->query($consulta);

    $consulta = "CREATE TABLE cocina (
        id_receta NUMBER,
        cocina VARCHAR(50),
        PRIMARY KEY (id_receta, cocina)
    );
    ";
    $resultado = $con->query($consulta);

    $consulta = "CREATE TABLE dieta (
        id_receta NUMBER,
        dieta VARCHAR(50),
        PRIMARY KEY (id_receta, dieta)
    );
    ";
    $resultado = $con->query($consulta);
?>
