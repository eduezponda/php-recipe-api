<?php
    require_once "../frontOffice/funcionalidadesAPI.php";

    $fechaActualizacion = obtenerUltimaFechaDeActualizacion();

    // Leer el archivo HTML como plantilla
    $html = file_get_contents('index.html');

    $partes = explode('<!-- Principio script -->', $html);

    $partes_ = explode('<!-- Final script -->', $partes[1]);
    
    // Dividir el HTML en dos partes
    $partes__ = explode('<!-- Fecha Actualizacion -->', $partes_[1]);
    
    // Insertar la fecha de actualización en la segunda parte del HTML
    $html_final = $partes[0] . $partes__[0] . $fechaActualizacion . $partes__[1];

    echo $html_final;

?>