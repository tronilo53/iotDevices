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

    $stmt = $pdo -> prepare('SELECT * FROM usuarios WHERE token = ?');
    $stmt -> execute([ $params->token ]);

    $row = $stmt->fetchAll();

    foreach($row as $rows) {

        $stmt_dispositivos = $pdo -> prepare('SELECT * FROM dispositivos WHERE id_usuario = ?');
        $stmt_dispositivos -> execute([ $rows['id'] ]);

        $row_disp = $stmt_dispositivos->fetchAll();

        if(empty($row_disp)) {

            $response = new Result();
            $response -> result = 'No hay dispositivos';
        }else {
            foreach($row_disp as $rows_disp) {

                $response = new Result();
                $response -> result = 'dispositivos obtenidos';
                $response -> dispositivos = $row_disp;
            }
        }

    }

    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($response);
?>