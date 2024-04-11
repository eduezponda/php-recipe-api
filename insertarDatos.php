<?php
    include 'basededatos.php';
    include 'recipeFoodApi.php';

    function insertarReceta($receta, $con, $query){
        $stmt = $con->prepare( "select id from comida where query = ? and minutos = ?");
        $stmt->bind_param("ss", $query, $receta['readyInMinutes']);
        $stmt->execute();
        $idComida = $stmt->get_result();

        if($idComida->num_rows === 0) {
            $consulta = "insert into comida (query, minutos) values ('$query', " . $receta['readyInMinutes'] . ")";
            $con->query($consulta);
            $consulta = "select id from comida where query = $query and minutos = " . $receta['readyInMinutes'];
            $idComida = $con->query($consulta);
        }

        $rangos = [25, 5, 5, 5, 10, 1];
        $valores = [$receta['nutrition'][0]['amount'], $receta['nutrition'][1]['amount'], $receta['nutrition'][2]['amount'], 
                    $receta['nutrition'][3]['amount'], $receta['nutrition'][8]['amount'], $receta['nutrition'][33]['amount'],
        ];
        $valoresMinimos = [0, 0, 0, 0, 0, 0];

        for ($i = 0; $i < count($rangos); $i++){
            $valoresMinimos[$i] = intdiv($valores[$i], $rangos[$i])*$rangos[$i];
        }

        $stmt = $con->prepare("select id from requerimiento where calorias = ? and proteinas = ? 
        and grasas = ? and carbohidratos = ?");
        $stmt->bind_param("ssss", $valoresMinimos[0], $valoresMinimos[1], $valoresMinimos[2], $valoresMinimos[3]);
        $stmt->execute();
        $idRequerimiento = $stmt->get_result();

        if ($idRequerimiento->num_rows === 0){
            $consulta = "insert into requerimiento (calorias, proteinas, grasas, carbohidratos) values 
                                ($valoresMinimos[0], $valoresMinimos[1], $valoresMinimos[2], $valoresMinimos[3])";
            $con->query($consulta);
            $consulta = "select id from requerimiento where calorias = $valoresMinimos[0] and proteinas = $valoresMinimos[1] 
                                                    and grasas = $valoresMinimos[2] and carbohidratos = $valoresMinimos[3]";
            $idRequerimiento = $con->query($consulta);
        }

        $consulta = "select id from composicion where azucar = $valoresMinimos[5] and colesterol = $valoresMinimos[4]";
        $idComposicion = $con->query($consulta);

        if ($idComposicion->num_rows === 0){
            $consulta = "insert into composicion (colesterol, azucar) values ($valoresMinimos[4], $valoresMinimos[5])";
            $con->query($consulta);
            $consulta = "select id from composicion where azucar = $valoresMinimos[5] and colesterol = $valoresMinimos[4]";
            $idComposicion = $con->query($consulta);
        }

        $consulta = "insert into receta (titulo, imagen, resumen, id_requerimiento, id_composicion, id_comida) values
                                        ('" . $receta['title'] . "','" . $receta['image'] . "','" . $receta['summary'] 
                                           . "',$id_requerimiento, $id_composicion, $id_comida)";
        $idComposicion = $con->query($consulta);

        $consulta = "select id from receta where titulo = '" . $receta['title'] . "' and imagen = '" . $receta['image'] 
                                                           . "' and resumen = '" . $receta['summary'] . "'";
        $idReceta = $con->query($consulta);

        foreach ($receta['cuisines'] as $cuisine) {
            $consulta = "insert into cocina (id_receta, cocina) values ($idReceta, '$cuisine')";
            $con->query($consulta);
        }

        foreach ($receta['diets'] as $diet) {
            $consulta = "insert into dieta (id_receta, dieta) values ($idReceta, '$diet')";
            $con->query($consulta);
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
    /*foreach ($comidas as $comida) {
        $data = json_decode(getRecipes($comida), true);

        if(isset($data['results'])) {
            foreach($data['results'] as $receta) {
                insertarReceta($receta, $con, $comida);
            }
        } else {
            echo "No results found.";
        }
    }*/
    $data = json_decode(getRecipes($comidas[0]), true);

        if(isset($data['results'])) {
            foreach($data['results'] as $receta) {
                insertarReceta($receta, $con, $comidas[0]);
            }
        } else {
            echo "No results found.";
        }
    /*foreach ($comidas as $comida) {
        $response = getRecipes($comida);
        if ($i == 0){
            echo $response;
            $i = 1;
        }

        foreach($response as $receta){
            echo $receta;
            insertarReceta($receta, $con, $comida);
        }
    }*/

?>