<?php
require_once '../frontOffice/funcionalidadesUsuario.php';

if (isset($_POST['username'])) {
    $nombreUsuario = $_POST['username'];
    exportarInformacionPDF($nombreUsuario);
}
?>
