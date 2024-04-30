<?php
    include_once 'basededatosAPI/borrarDatosTablasAPI.php';
    include_once 'basededatosAPI/insertarDatos.php';
    include_once 'MYPDF.php';

    function verResumenDatosAPI (){
        $con = conexion();

        $consulta = "SELECT CAST(AVG(calorias) AS INT) AS Calorias_Medias FROM final_requerimiento;
        ";
        $resultado = $con->query($consulta);

        if ($row = $resultado->fetch_assoc()) {
            echo "Calorías Medias de las recetas: " . $row['Calorias_Medias'] . "<br>";
        }

        $consulta = "SELECT DISTINCT cocina FROM final_cocina;
        ";
        $resultado = $con->query($consulta);

        echo "Tipos de cocina: ";
        while ($row = $resultado->fetch_assoc()) {
            echo $row['cocina'] . ", ";
        }
        echo "<br>";

        $consulta = "SELECT DISTINCT dieta FROM final_dieta;
        ";
        $resultado = $con->query($consulta);

        echo "Tipos de dieta: ";
        while ($row = $resultado->fetch_assoc()) {
            echo $row['dieta'] . ", ";
        }
        echo "<br>";

        $consulta = "SELECT DISTINCT query FROM final_comida WHERE minutos BETWEEN 10 AND 30;
        ";
        $resultado = $con->query($consulta);

        echo "Comidas de 10 a 30 minutos: ";
        while ($row = $resultado->fetch_assoc()) {
            echo $row['query'] . ", ";
        }
        echo "<br>";

        $consulta = "SELECT CAST(AVG(minutos) AS INT) AS Duracion_Media FROM final_comida;
        ";
        $resultado = $con->query($consulta);

        if ($row = $resultado->fetch_assoc()) {
            echo "Duración media de las comidas: " . $row['Duracion_Media'] . "<br>";
        }

        $consulta = "SELECT ingrediente, COUNT(*) AS Cantidad
                     FROM final_ingrediente
                     GROUP BY ingrediente
                     ORDER BY Cantidad DESC;
        ";
        $resultado = $con->query($consulta);

        echo "Ingredientes más utilizados:<br>";
        while ($row = $resultado->fetch_assoc()) {
            echo $row['ingrediente'] . ": " . $row['Cantidad'] . " veces<br>";
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

        echo "Valor nutricional medio por tipo de cocina:<br>";
        while ($row = $resultado->fetch_assoc()) {
            echo "Cocina: " . $row['cocina'] . ", Duración Media: " . $row['Duracion_Media'] . " minutos, Carbohidratos: " . $row['Media_Carbohidratos'] . ", Proteínas: " . $row['Media_Proteinas'] . ", Grasas: " . $row['Media_Grasas'] . ", Calorías: " . $row['Media_Calorias'] . "<br>";
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

        echo "Valor nutricional medio por tipo de dieta:<br>";
        while ($row = $resultado->fetch_assoc()) {
            echo "Dieta: " . $row['dieta'] . ", Duración Media: " . $row['Duracion_Media'] . " minutos, Carbohidratos: " . $row['Media_Carbohidratos'] . ", Proteínas: " . $row['Media_Proteinas'] . ", Grasas: " . $row['Media_Grasas'] . ", Calorías: " . $row['Media_Calorias'] . "<br>";
        }

        $con->close();
    }

    function obtenerUltimaFechaDeActualizacion(){
        $con = conexion();

        $consulta = "SELECT MAX(fecha) AS ultima_fecha FROM final_fechaActualizacion";
        $resultado = $con->query($consulta);

        if ($row = $resultado->fetch_assoc()) {
            echo "Ultima fecha de actualización: " . $row['ultima_fecha'] . "<br>";
        }

        $con->close();
    }

    function recogerDatosGraficasAPI (){

        $con = conexion();

        $consulta = "SELECT dieta, COUNT(id_receta) AS cantidad_recetas FROM final_dieta GROUP BY dieta";
        $resultado = $con->query($consulta);
        $datosDietas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $datosDietas[] = $fila;
        }

        $consulta = "SELECT cocina, COUNT(id_receta) AS cantidad_recetas FROM final_cocina GROUP BY cocina";
        $resultado = $con->query($consulta);
        $datosCocinas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $datosCocinas[] = $fila;
        }

        $consulta = "SELECT c.minutos, r.calorias
                    FROM final_comida c
                    JOIN final_receta rec ON c.id = rec.id_comida
                    JOIN final_requerimiento r ON rec.id_requerimiento = r.id
                    ORDER BY c.minutos";
        $resultado = $con->query($consulta);
        $datosMinutos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $datosMinutos[] = $fila;
        }

        $con->close();

        return [
            'dietas' => $datosDietas,
            'cocinas' => $datosCocinas,
            'minutos' => $datosMinutos
        ];
    }
?>