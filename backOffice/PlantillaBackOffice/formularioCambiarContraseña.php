<?php
    include '../funcionalidadesAdmin.php'; 

    session_start();
    
    if (!isset($_SESSION['admin'])) {
        header('Location: ../plantillaWeb/home.php');
        exit();
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $newPassword = $_POST['newPassword'];
        $verificateSamePassword = $_POST['verificateSamePassword'];

        if ($newPassword == '' || $verificateSamePassword == '' || strcmp($verificateSamePassword, $newPassword) !== 0){
            header('Location: changePss.php?result=-1');
            exit();
        }

        cambiarContrasena($username, $username, $newPassword);
    }

    header('Location: admin.php');
    exit();
?>
