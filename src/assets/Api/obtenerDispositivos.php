<?php

    require_once "./config.php";

    header("Access-Control-Allow-Origin: " . $url);
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    class Result {}
    $response = new Result();
    $response -> resultado = 'OK';
    $response -> mensaje = 'datos de prueba obtenidos';

    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($response);

?>