<?php
    include_once 'basededatosAPI/borrarDatosTablasAPI.php';
    include_once 'basededatosAPI/insertarDatos.php';
    include_once 'MYPDF.php';

    function verResumenDatosAPI (){
        $con = conexion();

        $consulta = "SELECT AVG(calorias) AS Calorias_Medias FROM final_requerimiento;
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

        $consulta = "SELECT AVG(minutos) AS Duracion_Media FROM final_comida;
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
                            AVG(com.minutos) AS Duracion_Media
                            AVG(r.carbohidratos) AS Media_Carbohidratos,
                            AVG(r.proteinas) AS Media_Proteinas,
                            AVG(r.grasas) AS Media_Grasas,
                            AVG(r.calorias) AS Media_Calorias
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
                        AVG(com.minutos) AS Duracion_Media
                        AVG(r.carbohidratos) AS Media_Carbohidratos,
                        AVG(r.proteinas) AS Media_Proteinas,
                        AVG(r.grasas) AS Media_Grasas,
                        AVG(r.calorias) AS Media_Calorias
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
    }

?>