<?php

    require_once "./config.php";

    header("Access-Control-Allow-Origin: " . URL);
    //header("Access-Control-Allow-Headers: access");
    //header("Access-Control-Allow-Methods: POST");
    //header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

    $params = json_decode(file_get_contents("php://input"));
    if(!$params) exit("No hay datos");

    require_once './connect.php';

    $stmt = $pdo -> prepare('SELECT COUNT(*) FROM usuarios WHERE token = ?');
    $stmt -> execute([ $params->token ]);

    if($stmt->fetchColumn() > 0) {
        $response = new Result();
        $response -> result = 'El token existe';
    }else {
        $response = new Result();
        $response -> result = 'El token no existe';
    }

    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($response);
?>