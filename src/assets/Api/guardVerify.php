<?php

    require_once "./connect.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <title>iotDevices - Gestión de Dispositivos</title>
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <link rel="stylesheet" href="dist/bootstrap.min.css">
    <style>
        body, html {
            background: #56CCF2 !important; 
            background: -webkit-linear-gradient(to right, #2F80ED, #56CCF2) !important;
            background: linear-gradient(to right, #2F80ED, #56CCF2) !important;
        }
        .container {
            margin-top: 200px;
        }
        .logo {
            position: absolute;
            top: 1rem;
            left: 1rem;
            width: 3rem;
        }
        .imagen {
            width: 12rem;
        }
    </style>
</head>
<body>

    <img src="favicon/apple-icon-152x152.png" class="logo" alt="logo.png">

    <div class="container">

        <div class="row text-center">
            <div class="col">
                <img class="imagen" src="favicon/logo_large.png" alt="logo_large.png">
            </div>
        </div>
    
    <?php
    
        if(isset($_GET['token']) && !empty($_GET['token'])) {

            //verificar si el token existe;
            $stmt = $pdo -> prepare('SELECT COUNT(*) FROM usuarios WHERE token = ?');
            $stmt -> execute([ $_GET['token'] ]);

            if($stmt->fetchColumn() > 0) {

                $stmt = $pdo -> prepare('SELECT * FROM usuarios WHERE token = ?');
                $stmt -> execute([ $_GET['token'] ]);

                $row = $stmt->fetchAll();

                foreach($row as $rows) {

                    $nombre = $rows['nombre'];

                    $stmt_up = $pdo -> prepare('UPDATE usuarios SET token = "", estado = "HAB" WHERE token = ?');
                    $stmt_up -> execute([ $_GET['token'] ]);

                    if($stmt_up) {

                        ?>

                        <div class="row mt-5 text-center">
                            <div class="col">
                                <div class="alert alert-light" role="alert">
                                    <h3>Hola <?php echo $nombre ?> !</h3>
                                    <h1>Tu cuenta se ha verificado con éxito</h1>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 text-center">
                            <div class="col">
                                <h2>Ya puedes iniciar sesión y gestionar tus dispositivos!</h2>
                                <h4>¿A qué esperas?</h4>
                            </div>
                        </div>
                        <div class="row mt-3 text-center">
                            <div class="col">
                                <a href="https://merovingioapp.es/iotDevices" class="btn btn-light">Adelante!</a>
                            </div>
                        </div>

                        <?php
                    }else {
                        
                        ?>

                        <div class="alert alert-danger mt-5 text-center" role="alert">
                            <h1>404</h1>
                            <h3>Page not found</h3>
                        </div>

                        <?php
                    }
                }
            }else {

                ?>

                <div class="alert alert-danger mt-5 text-center" role="alert">
                    <h1>404</h1>
                    <h3>Page not found</h3>
                </div>

                <?php
            }
        }else {

            ?>

            <div class="alert alert-danger mt-5 text-center" role="alert">
                <h1>404</h1>
                <h3>Page not found</h3>
            </div>

            <?php
        }
    
    ?>

    </div>

</body>
</html>