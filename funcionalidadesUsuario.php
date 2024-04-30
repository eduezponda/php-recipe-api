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
?>
