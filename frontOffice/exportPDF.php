<?php
require_once 'funcionalidadesUsuario.php';

session_start();

if (isset($_SESSION['user_name'])) {
    $nombreUsuario = $_SESSION['user_name'];
    $resumen = $_POST['resumen'];

    $datosReceta = json_decode($_POST['datosReceta'], true);

    $titulo = $datosReceta[0]['titulo'];
    $comida = $datosReceta[0]['comida'];
    $imagen = $datosReceta[0]['imagen'];
    $minutos = $datosReceta[0]['minutos'];

    $carbohidratos = $datosReceta[0]['carbohidratos'];
    $proteinas = $datosReceta[0]['proteinas'];
    $grasas = $datosReceta[0]['grasas'];
    $calorias = $datosReceta[0]['calorias'];
    $colesterol = $datosReceta[0]['colesterol'];
    $azucar = $datosReceta[0]['azucar'];

    exportarInformacionPDF($nombreUsuario, $titulo, $comida, $imagen, $resumen, $minutos, $carbohidratos,
                           $proteinas, $grasas, $calorias, $colesterol, $azucar);
}
?>
