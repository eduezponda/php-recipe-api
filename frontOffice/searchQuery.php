<?php
    require_once '../basededatosAPI/basededatos.php';

    if (isset($_POST['query'])) {
        $query = $_POST['query'];
        $con = conexion();

        $stmt = $con->prepare("SELECT DISTINCT query FROM final_comida WHERE query LIKE ?");

        $query = $query . '%';
        $stmt->bind_param("s", $query);
        $stmt->execute();

        $result = $stmt->get_result();  

        while ($row = $result->fetch_assoc()) {  
            echo '<p>' . htmlspecialchars($row['query']) . '</p>'; 
        }

        $con->close();
    }

    if(isset($_POST['querySearch'])){
        $query = $_POST['querySearch'];
        $con = conexion();

        $stmt = $con->prepare("SELECT id FROM final_comida WHERE query = ?");

        $stmt->bind_param("s", $query);
        $stmt->execute();

        $result = $stmt->get_result();  

        $idRecetas = [];
        while ($row = $result->fetch_assoc()) {
            $idRecetas[] = $row['id']; 
            $id = $idRecetas[0]; // Asumiendo que 'id' es el nombre de la columna
        }
    
        $con->close();
        echo json_encode($id);
        exit;
    }

?>