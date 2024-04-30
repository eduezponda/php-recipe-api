<?php
    include_once '/var/www/html/Trabajo Final/basededatosAPI/basededatos.php';

    function obtenerUltimaFechaDeActualizacion(){
        $con = conexion();

        $consulta = "SELECT MAX(fecha) AS ultima_fecha FROM final_fechaActualizacion";
        $resultado = $con->query($consulta);

        $ultimaFechaActualizacion = [];

        if ($row = $resultado->fetch_assoc()) {
            $ultimaFechaActualizacion[] = $row;
        }

        $con->close();

        return [
            'ultimaFechaActualizacion' => $ultimaFechaActualizacion
        ];
    }

?>
