<?php
    include '../frontOffice/funcionalidadesUsuario.php'; 
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['name'];
        $password = $_POST['password'];

        login($username, $password);
    }
?>
