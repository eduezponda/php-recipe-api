<?php
    include 'basededatos.php';
    include 'recipeFoodApi.php';

    function insertarReceta($receta, $con, $query){
        $stmt = $con->prepare( "select id from comida where query = ? and minutos = ?");
        $stmt->bind_param("si", $query, $receta['readyInMinutes']);
        $stmt->execute();
        $idComida = $stmt->get_result();

        if($idComida->num_rows === 0) {
            $consulta = "insert into comida (query, minutos) values ('$query', " . $receta['readyInMinutes'] . ")";
            if (!$con->query($consulta)) {
                echo "Error al ejecutar la inserción de comida: " . $con->error . "<br>";
            }
            $con->query($consulta);
            $idComida = $con->insert_id;
        }
        else {
            $row = $idComida->fetch_assoc();
            $idComida = $row['id'];
        }

        $rangos = [25, 5, 5, 5, 10, 1];
        $valores = [$receta['nutrition']['nutrients'][0]['amount'], $receta['nutrition']['nutrients'][1]['amount'],
                    $receta['nutrition']['nutrients'][2]['amount'], $receta['nutrition']['nutrients'][3]['amount'],
                    $receta['nutrition']['nutrients'][4]['amount'], $receta['nutrition']['nutrients'][5]['amount'],
        ];
        $valoresMinimos = [0, 0, 0, 0, 0, 0];

        for ($i = 0; $i < count($rangos); $i++){
            $valoresMinimos[$i] = intdiv($valores[$i], $rangos[$i])*$rangos[$i];
        }

        $stmt = $con->prepare("select id from requerimiento where calorias = ? and proteinas = ? 
        and grasas = ? and carbohidratos = ?");
        $stmt->bind_param("dddd", $valoresMinimos[0], $valoresMinimos[1], $valoresMinimos[2], $valoresMinimos[3]);
        $stmt->execute();
        $idRequerimiento = $stmt->get_result();

        if ($idRequerimiento->num_rows === 0){
            $consulta = "insert into requerimiento (calorias, proteinas, grasas, carbohidratos) values 
                                ($valoresMinimos[0], $valoresMinimos[1], $valoresMinimos[2], $valoresMinimos[3])";
            if (!$con->query($consulta)) {
                echo "Error al ejecutar la inserción de requerimiento: " . $con->error . "<br>";
            }
            $idRequerimiento = $con->insert_id;
        }
        else {
            $row = $idRequerimiento->fetch_assoc();
            $idRequerimiento = $row['id'];
        }

        $stmt = $con->prepare("select id from composicion where azucar = ? and colesterol = ?");
        $stmt->bind_param("dd", $valoresMinimos[5], $valoresMinimos[4]);
        $stmt->execute();
        $idComposicion = $stmt->get_result();

        if ($idComposicion->num_rows === 0){
            $consulta = "insert into composicion (colesterol, azucar) values ($valoresMinimos[4], $valoresMinimos[5])";
            if (!$con->query($consulta)) {
                echo "Error al ejecutar la inserción de composición: " . $con->error . "<br>";
            }
            $idComposicion = $con->insert_id;
        }
        else {
            $row = $idComposicion->fetch_assoc();
            $idComposicion = $row['id'];
        }
        
        $consulta = "insert into receta (titulo, imagen, resumen, id_requerimiento, id_composicion, id_comida) values
                                        ('" . str_replace("'", " ", $receta['title']) . "','" . $receta['image'] . "','" 
                                        . str_replace("'", " ", $receta['summary']) . "',$idRequerimiento, $idComposicion, $idComida)";
        if (!$con->query($consulta)) {
            echo "Error al ejecutar la inserción de receta: " . $con->error . "<br>";
        }

        $idReceta = $con->insert_id;

        foreach ($receta['cuisines'] as $cuisine) {
            $consulta = "insert into cocina (id_receta, cocina) values ($idReceta, '$cuisine')";
            if (!$con->query($consulta)) {
                echo "Error al ejecutar la inserción de cocina: " . $con->error . "<br>";
            }
        }

        foreach ($receta['diets'] as $diet) {
            $consulta = "insert into dieta (id_receta, dieta) values ($idReceta, '$diet')";
            if (!$con->query($consulta)) {
                echo "Error al ejecutar la inserción de dieta: " . $con->error . "<br>";
            }
        }
    }

    $con = conexion();

    $comidas = [
        'pasta', 'rice', 'chicken', 'vegetable', 'salad', 'pizza', 'burguer', 'tacos', 'sushi',
        'omelette', 'hot dog', 'paella', 'fish', 'meat', 'lasagna', 'meatballs', 'apple pie',
        'kebab', 'steak', 'tomato', 'bb', 'spaghetti', 'chiles', 'empanada', 'lentil', 'spring rolls',
        'beef', 'pork', 'soup', 'spinach', 'potato', 'calamari', 'fajitas', 'toast', 'burrito', 'biscuit',
        'cake', 'pie'
    ];

    foreach($comidas as $comida) {
        $data = json_decode(getRecipes($comida), true);

        if(isset($data['results'])) 
        {
            foreach($data['results'] as $receta) 
            {
                insertarReceta($receta, $con, $comida);
            }
        } 
        else 
        {
            echo "No results found.";
        }
    }

    if (isset($con)) {
        $con->close();
    }
?>