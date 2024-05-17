<?php
    require_once '../basededatosAPI/basededatos.php';

    if (isset($_POST['query'])) {
        $query = $_POST['query'] . '%';
        $con = conexion();

        $stmt = $con->prepare("SELECT DISTINCT query FROM final_comida WHERE query LIKE ?");
        $stmt->bind_param("s", $query);
        $stmt->execute();
        $result = $stmt->get_result();  

        $data = array();

        while ($row = $result->fetch_assoc()) {  
            $data[] = $row['query'];
        }

        echo json_encode($data);

        $con->close();
    }


    if(isset($_POST['querySearch'])){
        $query = $_POST['querySearch'];
        $con = conexion();

        $stmt = $con->prepare("SELECT r.id FROM final_receta AS r JOIN final_comida AS c ON r.id_comida = c.id WHERE c.query = ?");

        $stmt->bind_param("s", $query);
        $stmt->execute();

        $result = $stmt->get_result();  

        $idRecetas = [];
        while ($row = $result->fetch_assoc()) {
            $idRecetas[] = $row['id']; 
        }
    
        $con->close();

        echo json_encode($idRecetas);
        exit;
    }

?>