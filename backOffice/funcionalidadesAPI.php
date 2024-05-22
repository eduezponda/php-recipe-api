<?php
    include_once '/var/www/html/Trabajo Final/basededatosAPI/basededatos.php';

    function obtenerUltimaFechaDeActualizacion(){
        $con = conexion();

        $consulta = "SELECT MAX(fecha) AS ultima_fecha FROM final_fechaActualizacion";
        $resultado = $con->query($consulta);

        $con->close();

        $row = $resultado->fetch_assoc();

        return $row['ultima_fecha'];
    }

    function verResumenDatosAPI (){
        $con = conexion();

        $consulta = "SELECT CAST(AVG(calorias) AS INT) AS Calorias_Medias FROM final_requerimiento;";
        
        $resultado = $con->query($consulta);

        $caloriasMedias = [];
        if ($row = $resultado->fetch_assoc()) {
            $caloriasMedias[] = $row;
        }

        $consulta = "SELECT DISTINCT cocina FROM final_cocina;
        ";
        $resultado = $con->query($consulta);

        $tiposCocina = [];
        while ($row = $resultado->fetch_assoc()) {
            $tiposCocina[] = $row;
        }

        $consulta = "SELECT DISTINCT dieta FROM final_dieta;
        ";
        $resultado = $con->query($consulta);

        $tiposDieta = [];
        while ($row = $resultado->fetch_assoc()) {
            $tiposDieta[] = $row;
        }

        $consulta = "SELECT DISTINCT query FROM final_comida WHERE minutos BETWEEN 10 AND 30;
        ";
        $resultado = $con->query($consulta);

        $comidasConDuracionEntre10y30Minutos = [];
        while ($row = $resultado->fetch_assoc()) {
            $comidasConDuracionEntre10y30Minutos[] = $row;
        }

        $consulta = "SELECT CAST(AVG(minutos) AS INT) AS Duracion_Media FROM final_comida;
        ";
        $resultado = $con->query($consulta);

        $duracionMediaComidas = [];

        if ($row = $resultado->fetch_assoc()) {
            $duracionMediaComidas[] = $row;
        }

        $consulta = "SELECT ingrediente, COUNT(*) AS Cantidad
                     FROM final_ingrediente
                     GROUP BY ingrediente
                     ORDER BY Cantidad DESC;
        ";
        $resultado = $con->query($consulta);

        $cantidadVecesIngredientes = [];
        while ($row = $resultado->fetch_assoc()) {
            $cantidadVecesIngredientes[] = $row;
        }

        $consulta = "SELECT c.cocina,
                            CAST(ROUND(AVG(com.minutos)) AS UNSIGNED) AS Duracion_Media,
                            CAST(ROUND(AVG(r.carbohidratos)) AS UNSIGNED) AS Media_Carbohidratos,
                            CAST(ROUND(AVG(r.proteinas)) AS UNSIGNED) AS Media_Proteinas,
                            CAST(ROUND(AVG(r.grasas)) AS UNSIGNED) AS Media_Grasas,
                            CAST(ROUND(AVG(r.calorias)) AS UNSIGNED) AS Media_Calorias
                     FROM final_cocina AS c
                     JOIN final_receta AS rec ON c.id_receta = rec.id
                     JOIN final_requerimiento AS r ON rec.id_requerimiento = r.id
                     JOIN final_comida AS com ON rec.id_comida = com.id
                     GROUP BY c.cocina;
        ";
        $resultado = $con->query($consulta);

        $valorNutricionalyDuracionMediaPorCocina = [];
        while ($row = $resultado->fetch_assoc()) {
            $valorNutricionalyDuracionMediaPorCocina[] = $row;
        }

        $consulta = "SELECT d.dieta,
                            CAST(ROUND(AVG(com.minutos)) AS UNSIGNED) AS Duracion_Media,
                            CAST(ROUND(AVG(r.carbohidratos)) AS UNSIGNED) AS Media_Carbohidratos,
                            CAST(ROUND(AVG(r.proteinas)) AS UNSIGNED) AS Media_Proteinas,
                            CAST(ROUND(AVG(r.grasas)) AS UNSIGNED) AS Media_Grasas,
                            CAST(ROUND(AVG(r.calorias)) AS UNSIGNED) AS Media_Calorias
                     FROM final_dieta AS d
                     JOIN final_receta AS rec ON d.id_receta = rec.id
                     JOIN final_requerimiento AS r ON rec.id_requerimiento = r.id
                     JOIN final_comida AS com ON rec.id_comida = com.id
                     GROUP BY d.dieta;
        ";
        $resultado = $con->query($consulta);

        $valorNutricionalyDuracionMediaPorDieta = [];
        while ($row = $resultado->fetch_assoc()) {
            $valorNutricionalyDuracionMediaPorDieta[] = $row;
        }

        $con->close();

        return [
            'caloriasMedias' => $caloriasMedias,
            'tiposCocina' => $tiposCocina,
            'tiposDieta' => $tiposDieta,
            'comidasConDuracionEntre10y30Minutos' => $comidasConDuracionEntre10y30Minutos,
            'duracionMediaComidas' => $duracionMediaComidas,
            'cantidadVecesIngredientes' => $cantidadVecesIngredientes,
            'valorNutricionalyDuracionMediaPorCocina' => $valorNutricionalyDuracionMediaPorCocina,
            'valorNutricionalyDuracionMediaPorDieta' => $valorNutricionalyDuracionMediaPorDieta
        ];
    }
?>
