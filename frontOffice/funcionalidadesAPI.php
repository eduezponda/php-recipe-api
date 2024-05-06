<?php
    include_once '/var/www/html/Trabajo Final/basededatosAPI/basededatos.php';

    function verResumenDatosAPI (){
        $con = conexion();

        $consulta = "SELECT CAST(AVG(calorias) AS INT) AS Calorias_Medias FROM final_requerimiento;
        ";
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

    function obtenerUltimaFechaDeActualizacion(){
        $con = conexion();

        $consulta = "SELECT MAX(fecha) AS ultima_fecha FROM final_fechaActualizacion";
        $resultado = $con->query($consulta);

        $con->close();

        $row = $resultado->fetch_assoc();

        return $row['ultima_fecha'];
    }

    function recogerDatosGraficasAPI ($requerimiento){

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

        $consulta = "SELECT c.minutos, r.${requerimiento} 
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

    function obtenerDatosReceta($id_receta) {

        $con = conexion();

        $consulta = "SELECT 
                        r.id AS receta_id,
                        r.titulo,
                        r.imagen,
                        r.resumen,
                        c.query AS comida,
                        c.minutos,
                        req.carbohidratos,
                        req.proteinas,
                        req.grasas,
                        req.calorias,
                        comp.colesterol,
                        comp.azucar,
                        co.cocina,
                        d.dieta,
                    FROM 
                        final_receta AS r
                    LEFT JOIN 
                        final_comida AS c ON r.id_comida = c.id
                    LEFT JOIN 
                        final_requerimiento AS req ON r.id_requerimiento = req.id
                    LEFT JOIN 
                        final_composicion AS comp ON r.id_composicion = comp.id
                    LEFT JOIN 
                        final_cocina AS co ON r.id = co.id_receta
                    LEFT JOIN 
                        final_dieta AS d ON r.id = d.id_receta
                    WHERE 
                        r.id = ?";

        if ($stmt = $con->prepare($consulta)) {
            $stmt->bind_param("i", $id_receta);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            $datos = [];
            
            while ($fila = $resultado->fetch_assoc()) {
                $datos[] = $fila;
            }
            
            $stmt->close();
            
            return $datos;
        } else {
            echo "Error al recoger los datos de la receta de la base de datos";
            return null;
        }
    }

    function obtenerIngredientesReceta($id_receta) {
        $con = conexion();

        $consulta = "SELECT * FROM final_ingrediente AS i WHERE i.id_receta = ?";

        if ($stmt = $con->prepare($consulta)) {
            $stmt->bind_param("i", $id_receta);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            $datos = [];
            
            while ($fila = $resultado->fetch_assoc()) {
                $datos[] = $fila;
            }
            
            $stmt->close();
            
            return $datos;
        } else {
            echo "Error al recoger los ingredientes de la receta de la base de datos";
            return null;
        }
    }
?>
