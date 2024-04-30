<?php
    include_once 'MYPDF.php';
    include_once '/var/www/html/Trabajo Final/basededatosAPI/basededatos.php';

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
