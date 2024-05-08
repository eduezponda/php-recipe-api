<?php
    include_once 'basededatos.php';
   
    function borrarDatos(){
        $con = conexion();
        $tablas = array("final_cocina", "final_dieta", "final_ingrediente", "final_receta", "final_requerimiento", "final_composicion", "final_comida");

        foreach ($tablas as $tabla) {
            $consulta = "DELETE FROM $tabla";
            $resultado = $con->query($consulta);

            if ($resultado) {
                echo "Datos eliminados de la tabla $tabla correctamente.<br>";
            } else {
                echo "Error al eliminar datos de la tabla $tabla: " . $con->error . "<br>";
            }
        }

        $consultaReiniciarAutoIncrement = "ALTER TABLE final_receta AUTO_INCREMENT = 1; ALTER TABLE final_comida AUTO_INCREMENT = 1; 
                                           ALTER TABLE final_requerimiento AUTO_INCREMENT = 1; ALTER TABLE final_composicion AUTO_INCREMENT = 1;";
        $resultado = $con->query($consultaReiniciarAutoIncrement);

        $con->close();
    }
?>
