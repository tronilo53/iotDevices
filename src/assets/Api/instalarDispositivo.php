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

    if($params->alias === '' || $params->serie === '') {
        $response = new Result();
        $response -> result = 'error';
        $response -> message = 'Todos los campos son requeridos';
    
    }else {
        //verificar si existe el dispositivo;
        $stmt = $pdo -> prepare('SELECT COUNT(*) FROM dispositivos_master WHERE serie = ?');
        $stmt -> execute([ $params->serie ]);

        if($stmt->fetchColumn() > 0) {
            $stmt = $pdo -> prepare('SELECT * FROM dispositivos_master WHERE serie = ?');
            $stmt -> execute([ $params->serie ]);
            
            $row = $stmt->fetchAll();
            foreach($row as $rows) {
                if($rows['estado'] == 'HAB') {
                    if($rows['propietario'] == 'NO') {

                        $stmt = $pdo -> prepare('INSERT INTO dispositivos(alias, serie, id_usuario) VALUES (?,?,?)');
                        $stmt -> execute([ $params->alias, $params->serie, $params->id_usuario ]);

                        if($stmt) {
                            $response = new Result();
                            $response -> result = 'success';
                            $response -> message = 'Dispositivo instalado';
                        }else {
                            $response = new Result();
                            $response -> result = 'error';
                            $response -> message = 'No se ha podido instalar el dispositivo';
                        }
                    }else {
                        $response = new Result();
                        $response -> result = 'error';
                        $response -> message = 'Este dispositivo ya tiene propietario';
                    }
                }else {
                    $response = new Result();
                    $response -> result = 'error';
                    $response -> message = 'Este dispositivo está deshabilitado';
                }
            }
        }else {
            $response = new Result();
            $response -> result = 'error';
            $response -> message = 'No existe el dispositivo';
        }
    }

    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($response);
?>