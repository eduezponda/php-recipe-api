<?php
    include '../frontOffice/funcionalidadesUsuario.php'; 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['name'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $language = $_POST['language'];

        signUp($username, $email, $language, $password);
    }
?>
