<?php
    include_once 'MYPDF.php';
    include_once '../basededatosAPI/basededatos.php';

    function cambiarContrasena($usuario, $usuarioCambiar, $nuevaContrasena){
        $con = conexion();
        $consulta = "UPDATE final_usuario SET passwordHash = ? WHERE nombreUsuario = ? AND nombreUsuario = ?";
        $stmt = $con->prepare($consulta);
        $passwordHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

        $stmt->bind_param("sss", $passwordHash, $usuario, $usuarioCambiar);
        $stmt->execute();

        if ($stmt->affected_rows <= 0) {
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

                if ($stmt->affected_rows <= 0) {
                    echo "Usuario no existe <br>";
                } 
            }
            else{
                echo "Usuario no tiene permisos para cambiar la contraseña de otro usuario<br>";
            }
        }
        $stmt->close();

        header('Location: ../plantillaWeb/user.php');
        exit();

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

    function obtenerIdiomaUsuario($user) {
        $con = conexion();

        $consulta = "SELECT u.claveIdioma FROM final_usuario u WHERE u.nombreUsuario = '$user'";

        $resultado = $con->query($consulta);
        $fila = $resultado->fetch_assoc();
        $idioma = $fila['claveIdioma'];

        $con->close();

        return $idioma;
    }

    function cambiarIdioma($nombreUsuario, $nuevoIdioma){
        $con = conexion();
        
        $consulta = "UPDATE final_usuario SET claveIdioma = '$nuevoIdioma' WHERE nombreUsuario = '$nombreUsuario'";
        $con->query($consulta);
    
        $con->close();

        header('Location: ../plantillaWeb/user.php');
        exit();
    }
    

function exportarInformacionPDF($nombreUsuario, $titulo, $comida, $imagen, $resumen, $minutos, $carbohidratos,
                                $proteinas, $grasas, $calorias, $colesterol, $azucar) {
    $informacionUsuario = verInformacionUsuario($nombreUsuario);

    if ($informacionUsuario !== false) {
        list($correo, $idioma) = $informacionUsuario;
    } else {
        $correo = "N/A";
        $idioma = "N/A";
    }

    $pdf = new MYPDF($nombreUsuario);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($nombreUsuario);
    $pdf->SetTitle('Mazapan Corporate Info');
    $pdf->SetSubject('Detalles del Usuario');

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $pdf->AddPage();

    $pdf->SetFont('helvetica', '', 12);

    $contenido = '<h1>Detalles de la Receta</h1>';
    $contenido .= '<p><strong>Correo:</strong> ' . $correo . '</p>';
    $contenido .= '<p><strong>Idioma:</strong> ' . $idioma . '</p>';
    $contenido .= '<p><strong>Título:</strong> ' . $titulo . '</p>';
    $contenido .= '<p><strong>Comida:</strong> ' . $comida . '</p>';
    $contenido .= '<p><strong>Tiempo de preparación:</strong> ' . $minutos . ' minutos</p>';
    $contenido .= '<p><strong>Resumen:</strong> ' . $resumen . '</p>';
    $contenido .= '<p><strong>Carbohidratos:</strong> ' . $carbohidratos . ' g</p>';
    $contenido .= '<p><strong>Proteínas:</strong> ' . $proteinas . ' g</p>';
    $contenido .= '<p><strong>Grasas:</strong> ' . $grasas . ' g</p>';
    $contenido .= '<p><strong>Calorías:</strong> ' . $calorias . ' kcal</p>';
    $contenido .= '<p><strong>Colesterol:</strong> ' . $colesterol . ' mg</p>';
    $contenido .= '<p><strong>Azúcar:</strong> ' . $azucar . ' g</p>';
    $contenido .= '<center><img src="' . $imagen . '" alt="Imagen de la receta" style="width:200px;height:150px;" /></center>';

    $pdf->writeHTML($contenido, true, false, true, false, '');

    $pdf->Output('mazapanCorporateInfo.pdf', 'I');
}

    
    function signUp($nombreUsuario, $correo, $language, $password){
        $con = conexion();

        $username = mysqli_real_escape_string($con, $nombreUsuario);

        $password = mysqli_real_escape_string($con, $password);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);

        if (filter_var($correo, FILTER_VALIDATE_EMAIL) === false)
        {
            return -1;
        }

        $consulta = "INSERT INTO final_usuario (nombreUsuario, tipo, passwordHash, correo, claveIdioma) VALUES ('$username', 'user', '$passwordHash', '$correo', '$language')";

        try {
            if(!$con->query($consulta)){
                return -2;
            }
            else{
                header("Location: ../plantillaWeb/logIn.php");
                exit();
            }
        } catch (mysqli_sql_exception $e) {
            return -2;
        }
    }

    function login($nombreUsuario, $password){
        $con = conexion();

        ini_set('session.cookie_secure', 1);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        session_start();

        $nombreUsuario = mysqli_real_escape_string($con, $nombreUsuario);
        $password = mysqli_real_escape_string($con, $password);

        $consulta = "SELECT passwordHash, tipo FROM final_usuario WHERE nombreUsuario = '$nombreUsuario'";

        $resultado = $con->query($consulta);

        if ($row = $resultado->fetch_assoc()) {
            if (password_verify($password, $row['passwordHash'])) {
                $_SESSION['user_name'] = $nombreUsuario;

                if ($row['tipo'] === 'admin') {
                    $_SESSION['admin'] = true;
                    header("Location: ../backOffice/PlantillaBackOffice/admin.php");
                    exit();
                }

                header("Location: ../plantillaWeb/home.php");
                exit();
                
            } else {
                //echo "Contraseña incorrecta."; 
                return -2;
            }
        } else {
            //echo "Usuario no encontrado.";
            return -1;
        }
    }
?>
