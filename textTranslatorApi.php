<?php

function getTranslateText($target_language, $title) {
    $request = new HttpRequest();
    $request->setUrl('https://text-translator2.p.rapidapi.com/translate');
    $request->setMethod(HTTP_METH_POST);

    $request->setHeaders([
        'content-type' => 'application/x-www-form-urlencoded',
        'X-RapidAPI-Key' => 'SIGN-UP-FOR-KEY',
        'X-RapidAPI-Host' => 'text-translator2.p.rapidapi.com'
    ]);

    $request->setContentType('application/x-www-form-urlencoded');
    $request->setPostFields([
        'source_language' => 'en',
        'target_language' => $target_language,
        'text' => $title
    ]);

    try {
        $response = $request->send();
        return $response->getBody();
    } catch (HttpException $ex) {
        return $ex;
    }
}
?>
