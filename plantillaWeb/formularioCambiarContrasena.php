<?php
    include '../frontOffice/funcionalidadesUsuario.php'; 
    
    if (!isset($_SESSION['user_name'])) {
        header('Location: home.php');
        exit();
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $newPassword = $_POST['newPassword'];
        $verificateSamePassword = $_POST['verificateSamePassword'];

        if ($newPassword == '' || $verificateSamePassword == '' || strcmp($verificateSamePassword, $newPassword) !== 0){
            echo "<script>alert('La contraseña es distinta o está vacía');</script>";
            exit();
        }

        cambiarContrasena($username, $username, $newPassword);
    }

    exit();
?>
