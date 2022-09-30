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

    if($params->nombre === '' || $params->email === '' || $params->clave === '') {
        $response = new Result();
        $response -> result = 'Todos los campos son requeridos';
    }else{
        //Verificar si ya existe el usuario;
        $stmt = $pdo -> prepare('SELECT COUNT(*) FROM usuarios WHERE email = ?');
        $stmt -> execute([ $params->email ]);

        if($stmt->fetchColumn() > 0) {
            $response = new Result();
            $response -> result = 'El usuario ya existe';
        }else {
            //Hashear la contraseña;
            $clave_hasheada = password_hash($params->clave, PASSWORD_DEFAULT);
            //Generar token;
            $token = bin2hex(random_bytes(100));
            //crear fecha;
            $anio = date('Y');
            $mes = date('m');
            $dia = date('d');
            $fecha = $anio . '-' . $mes . '-' . $dia;
            //Insertar usuario
            $stmt = $pdo -> prepare('INSERT INTO usuarios(nombre, email, clave, token, fecha_registro) VALUES (?,?,?,?,?)');
            $stmt -> execute([ $params->nombre, $params->email, $clave_hasheada, $token, $fecha ]);

            if($stmt) {

                $nombre = $params->nombre;
                $email = $params->email;

                $url = "https://merovingioapp.es/Api/guardVerify.php?token=" . $token;

                $to = $email;
                $subject = 'Registro en iotDevices';
                $message = '
                    <html>
                        <head>
                            <meta charset="utf-8">
                            <title>iotDevices - Gestión de Dispositivos</title>
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <meta http-equiv="Content-Language" content="es-ES">
                        </head>
                        <body>
                            <h2>Hola ' . $nombre . '! Ya estás a un paso más cerca de poder gestionar tu dispositivo con todo lo que eso conlleva.</h2>
                            <p>Para poder seguir adelante tendrás que verificar tu cuenta pinchando en el enlace que tienes más abajo.</p>
                            <h3>Solo un paso más!</h3>
                            <a href="' . $url . '">' . $url . '</a>
                            <p><strong>Este email contiene datos sensibles, no lo compartas con otras personas.</strong></p>
                            <p>Equipo de soporte iotDevices&copy;</p>
                        </body>
                    </html>
                ';

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
                $headers .= "From: info.support@merovingioapp.es" . "\r\n" .
                    "Reply-To: info.support@merovingioapp.es" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();
                
                if(mail($to, $subject, $message, $headers)) {
                    $response = new Result();
                    $response -> result = 'Usuario insertado';
                }else {
                    $stmt = $pdo -> prepare('DELETE FROM usuarios WHERE email = ?');
                    $stmt -> execute([ $params->email ]);
                    $response = new Result();
                    $response -> result = 'Usuario no insertado';
                }
            }else {
                $response = new Result();
                $response -> result = 'Usuario no insertado';
            }
        }
    }

    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($response);
?>