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

    if($params->email === '' || $params->clave === '') {

        $response = new Result();
        $response -> result = 'Todos los campos son requeridos';
    }else{

        //Verificar si existe el usuario;
        $stmt = $pdo -> prepare('SELECT COUNT(*) FROM usuarios WHERE email = ?');
        $stmt -> execute([ $params->email ]);

        if($stmt->fetchColumn() > 0) {

            $stmt = $pdo -> prepare('SELECT * FROM usuarios WHERE email = ?');
            $stmt -> execute([ $params->email ]);
            
            $row = $stmt->fetchAll();

            foreach($row as $rows) {
                $clave_hasheada = $rows['clave'];
                $estado = $rows['estado'];

                if(password_verify($params->clave, $clave_hasheada)) {

                    if($estado === 'HAB') {

                        $response = new Result();
                        $response -> result = 'usuario correcto';
                        $response -> token = $rows['token'];
                    }else {
                        $response = new Result();
                        $response -> result = 'usuario deshabilitado';
                    }
                }else {
                    $response = new Result();
                    $response -> result = 'la clave no coincide';
                }
            }
        }else {

            $response = new Result();
            $response -> result = 'El usuario no existe';
        }
    }

    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($response);
?>