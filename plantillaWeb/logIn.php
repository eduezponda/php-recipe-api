<?php
    require_once "../frontOffice/funcionalidadesAPI.php";

    $fechaActualizacion = obtenerUltimaFechaDeActualizacion();

    // Leer el archivo HTML como plantilla
    $html = file_get_contents('logIn.html');
    
    // Dividir el HTML en dos partes
    $partes = explode('<!-- Fecha Actualizacion -->', $html);
    
    // Insertar la fecha de actualización en la segunda parte del HTML
    $html_final = $partes[0] . $fechaActualizacion . $partes[1];

    echo $html_final;

?>