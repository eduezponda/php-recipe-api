<?php
    include_once 'basededatosAPI/borrarDatosTablasAPI.php';
    include_once 'basededatosAPI/insertarDatos.php';
    include_once 'MYPDF.php';

    function eliminarUsuario($usuario){
        $con = conexion();
        $consulta = "DELETE FROM final_usuario WHERE nombreUsuario = ? AND tipo != 'admin'";
        $stmt = $con->prepare($consulta);

        if ($stmt) {
            $stmt->bind_param("s", $usuario);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Usuario eliminado correctamente. <br>";
            } else {
                echo "No se encontró el usuario o el usuario es un administrador. <br>";
            }
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $con->error . "<br>";
        }
    }

    function cambiarContrasena($usuario, $usuarioCambiar, $nuevaContrasena){
        $con = conexion();
        $consulta = "UPDATE final_usuario SET passwordHash = ? WHERE nombreUsuario = ? AND nombreUsuario = ?";
        $stmt = $con->prepare($consulta);
        $passwordHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

        $stmt->bind_param("sss", $passwordHash, $usuario, $usuarioCambiar);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Contraseña cambiada correctamente. <br>";
        } else {
            $stmt = $con->prepare("SELECT tipo FROM final_usuario WHERE nombreUsuario = ?");
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $tipoUsuario = $stmt->get_result();

            $row = $tipoUsuario->fetch_assoc();
            $tipoUsuario = $row['tipo'];

            if($tipoUsuario == 'admin'){
                $consulta = "UPDATE final_usuario SET passwordHash = ? WHERE nombreUsuario = ? ";
                $stmt = $con->prepare($consulta);
        
                $stmt->bind_param("ss", $passwordHash, $usuarioCambiar);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "Contraseña cambiada correctamente. <br>";
                } 
                else{
                    echo "Usuario no existe <br>";
                }
            }
            else{
                echo "Usuario no tiene permisos para cambiar la contraseña de otro usuario<br>";
            }
        }
        $stmt->close();
    }

    function borrarDatosAPI(){
        borrarDatos();
    }

    function actualizarDatosAPI(){
        borrarDatos();
        $con = conexion();

        $comidas = [
            'pasta', 'rice', 'chicken', 'vegetable', 'salad', 'pizza', 'burguer', 'tacos', 'sushi',
            'omelette', 'hot dog', 'paella', 'fish', 'meat', 'lasagna', 'meatballs', 'apple pie',
            'kebab', 'steak', 'tomato', 'bb', 'spaghetti', 'chiles', 'empanada', 'lentil', 'spring rolls',
            'beef', 'pork', 'soup', 'spinach', 'potato', 'calamari', 'fajitas', 'toast', 'burrito', 'biscuit',
            'cake', 'pie'
        ];
    
        foreach($comidas as $comida) {
            $data = json_decode(getRecipes($comida), true);
    
            if(isset($data['results'])) 
            {
                foreach($data['results'] as $receta) 
                {
                    insertarReceta($receta, $con, $comida);
                }
            } 
            else 
            {
                echo "No results found.";
            }
        }


        date_default_timezone_set('Europe/Madrid');
        $fechaActual = new DateTime();
        $fechaActual->setTimezone(new DateTimeZone('Europe/Madrid'));
        $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');

        $consulta = "select count(*) as numeroRecetas from final_receta";
        $resultado = $con->query($consulta);
        $row = $resultado->fetch_assoc();
        $numeroRecetas = $row['numeroRecetas'];

        $consulta = "insert into final_fechaActualizacion (fecha, numeroRecetas) values ('$fechaFormateada', $numeroRecetas)";
        $resultado = $con->query($consulta);

        echo "Datos insertados en las tablas correctamente";
    
        if (isset($con)) {
            $con->close();
        }
    }

    function verInformacionUsuario($nombreUsuario){
        $con = conexion();
        
        $consulta = "SELECT u.correo, i.idioma 
             FROM final_usuario u 
             INNER JOIN final_idioma i ON u.claveIdioma = i.clave
             WHERE u.nombreUsuario = '$nombreUsuario'";

        $resultado = $con->query($consulta);
        $fila = $resultado->fetch_assoc();

        $correo = $fila['correo'];
        $idioma = $fila['idioma'];

        $con->close();

        return array($correo, $idioma);
    }

    function exportarInformacionPDF($nombreUsuario){
        $informacionUsuario = verInformacionUsuario($nombreUsuario);

        if ($informacionUsuario !== false) {
            list($correo, $idioma) = $informacionUsuario;
        }

        $pdf = new MYPDF($nombreUsuario, $correo, $idioma);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($nombreUsuario);
        $pdf->SetTitle('Mazapan Corporate Info');
        $pdf->SetSubject('Detalles del Usuario');

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->AddPage();

        /*
        $pdf->SetFont('helvetica', '', 12);

        $contenido = '<p><strong>Nombre de usuario:</strong> ' . "hamza" . '</p>';
        $contenido .= '<p><strong>Correo:</strong> ' . "hamza@machete.com" . '</p>';
        $contenido .= '<p><strong>Idioma:</strong> ' . "marroqui" . '</p>';
        $pdf->writeHTML($contenido, true, false, true, false, '');
        */

        $pdf->Output('mazapanCorporateInfo.pdf', 'I');
    }

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
