<?php
use Http\HttpRequest;
function getRecipes($query) {
    $url = 'https://spoonacular-recipe-food-nutrition-v1.p.rapidapi.com/recipes/complexSearch';
    $queryData = [
        'query' => $query,
        'number' => 100,
        'addRecipeInformation' => True
    ];

    $headers = [
        'X-RapidAPI-Key: 57cde7fd1fmsha9c569f721e685bp1927abjsne3aad9677af0',
        'X-RapidAPI-Host: spoonacular-recipe-food-nutrition-v1.p.rapidapi.com'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($queryData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}


/*$response = getRecipes(
    'pasta',            // query
    'italian',          // cuisine
    'vegetarian',       // diet
    'gluten',           // intolerances
    '20',               // maxReadyTime
    '10',               // minCarbs
    '100',              // maxCarbs
    '10',               // minProtein
    '100',              // maxProtein
    '50',               // minCalories
    '800',              // maxCalories
    '10',               // minFat
    '100',              // maxFat
    '0',                // minCholesterol
    '100',              // maxCholesterol
    '0',                // minSugar
    '100'               // maxSugar
);
$html_original = file_get_contents("../Practica 2/ejercicio11.html");
$html_original = str_replace("##cuerpo##", $response, $html_original);
echo $html_original;*/

?>
