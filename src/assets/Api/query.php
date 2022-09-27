<?php

    require_once "./header.php";

    $datos_recibidos = json_decode(file_get_contents("php://input"));
    if(!$datos_recibidos) exit("No hay datos");

    require_once './connect.php';

    $tipo = $datos_recibidos->tipo;
    $subtipo = $datos_recibidos->subtipo;

    switch ($tipo) {
        case 'equipos': //DONE!
            if($subtipo === 'insertar_equipos') {   //Corregido!
                $modelo = $datos_recibidos->modelo;
                $opcion = $datos_recibidos->opcion;
                $codigo = $datos_recibidos->codigo;
                $fecha_envio = '';
                if($opcion === 'Enviados') $fecha_envio = date('d') . '-' . date('m') . '-' . date('Y');
                $stmt = $pdo -> prepare('SELECT COUNT(*) FROM equipos_insertados_app WHERE codigo = ?');
                $stmt -> execute([ $codigo ]);
                if($stmt -> fetchColumn() > 0) {
                    $out = [
                        "resultado" => "el equipo ya existe"
                    ];
                }else {
                    $stmt = $pdo -> prepare('INSERT INTO equipos_insertados_app(modelo, codigo, opcion, fecha_envio) VALUES(?,?,?,?)');
                    $stmt -> execute([ $modelo, $codigo, $opcion, $fecha_envio ]);
                    if($stmt) {
                        $out = [
                            "resultado" => "equipo insertado"
                        ];
                    }else {
                        $out = [
                            "resultado" => "equipo no insertado"
                        ];
                    }
                }
            }
            if($subtipo === 'mostrar_cantidad_opciones') {  //Corregido!
                $modelo = $datos_recibidos->modelo;
                $stmt_en_stock = $pdo -> prepare('SELECT COUNT(*) FROM equipos_insertados_app WHERE modelo = ? AND opcion = "En Stock"');
                $stmt_en_stock -> execute([ $modelo ]);
                $cantidad_en_stock = $stmt_en_stock -> fetchColumn();
                $stmt_en_reparacion = $pdo -> prepare('SELECT COUNT(*) FROM equipos_insertados_app WHERE modelo = ? AND opcion = "En Reparacion"');
                $stmt_en_reparacion -> execute([ $modelo ]);
                $cantidad_en_reparacion = $stmt_en_reparacion -> fetchColumn();
                $stmt_enviados = $pdo -> prepare('SELECT COUNT(*) FROM equipos_insertados_app WHERE modelo = ? AND opcion = "Enviados"');
                $stmt_enviados -> execute([ $modelo ]);
                $cantidad_enviados = $stmt_enviados -> fetchColumn();
                $out = [
                    "en_stock" => $cantidad_en_stock,
                    "en_reparacion" => $cantidad_en_reparacion,
                    "enviados" => $cantidad_enviados
                ];
            }
            if($subtipo === 'mostrar_equipos') {    //Corregido!
                $modelo = $datos_recibidos->modelo;
                $opcion = $datos_recibidos->opcion;
                $stmt = $pdo -> prepare('SELECT * FROM equipos_insertados_app WHERE modelo = ? AND opcion = ?');
                $stmt -> execute([ $modelo, $opcion ]);
                $row = $stmt -> fetchAll();
                $out = $row;
            }
            if($subtipo === 'establecer_opcion') { //Corregido!
                $id = $datos_recibidos->id;
                $opcion = $datos_recibidos->opcion;
                if($opcion === 'Enviados') {
                    $fecha_envio = date('d') . '-' . date('m') . '-' . date('Y');
                    $stmt = $pdo -> prepare('UPDATE equipos_insertados_app SET opcion = ?, fecha_envio = ? WHERE id = ?' );
                    $stmt -> execute([ $opcion, $fecha_envio, $id ]);
                }elseif($opcion === 'Eliminar') {
                    $stmt = $pdo -> prepare('DELETE FROM equipos_insertados_app WHERE id = ?');
                    $stmt -> execute([ $id ]);
                }else {
                    $fecha_envio = '';
                    $stmt = $pdo -> prepare('UPDATE equipos_insertados_app SET opcion = ?, fecha_envio = ? WHERE id = ?' );
                    $stmt -> execute([ $opcion, $fecha_envio, $id ]);
                }
                if($stmt) {
                    $out = [
                        "resultado" => "opcion actualizada"
                    ];
                }else {
                    $out = [
                        "resultado" => "opcion no actualizada"
                    ];
                }
            }
            if($subtipo === 'obtener_modelos') {    //Corregido!
                $stmt = $pdo -> prepare('SELECT * FROM listado_modelos_app');
                $stmt -> execute();
                $row = $stmt -> fetchAll();
                $out = $row;
            }
            break;
        case 'componentes': //DONE!
            if($subtipo === 'obtener_categorias') { //Corregido!
                $stmt = $pdo -> prepare('SELECT * FROM listado_categorias_app');
                $stmt -> execute();
                $row = $stmt -> fetchAll();
                $out = $row;
            }
            if($subtipo === 'obtener_componentes_categoria') {  //Corregido!
                $categoria = $datos_recibidos->categoria;
                $stmt = $pdo -> prepare('SELECT * FROM componentes_app WHERE categoria = ?');
                $stmt -> execute([ $categoria ]);
                $row = $stmt -> fetchAll();
                $out = $row;
            }
            if($subtipo === 'acciones_componentes') {   //Corregido!
                $accion = $datos_recibidos->accion;
                $cantidad_existente = $datos_recibidos->cantidad_existente;
                $cantidad_insertada = $datos_recibidos->cantidad_insertada;
                $id = $datos_recibidos->id;
                $nueva_cantidad = '0';
                if($accion === 'Añadir') {
                    $nueva_cantidad = (int)$cantidad_existente + (int)$cantidad_insertada;
                }elseif($accion === 'Quitar') {
                    $nueva_cantidad = (int)$cantidad_existente - (int)$cantidad_insertada;
                }else {
                    $nueva_cantidad = '0';
                }
                $nueva_cantidad = (string)$nueva_cantidad;
                $stmt = $pdo -> prepare('UPDATE componentes_app SET cantidad = ? WHERE id = ?');
                $stmt -> execute([ $nueva_cantidad, $id ]);
                if($stmt) {
                    $out = [
                        "resultado" => "accion completada"
                    ];
                }else {
                    $out = [
                        "resultado" => "accion no completada"
                    ];
                }
            }
            if($subtipo === 'obtener_componente_recargar_vista') {  //Corregido!
                $id = $datos_recibidos->id;
                $stmt = $pdo -> prepare('SELECT * FROM componentes_app WHERE id = ?');
                $stmt -> execute([ $id ]);
                $row = $stmt -> fetchAll();
                $out = $row;
            }
            break;
        case 'perfil':  //DONE!
            if($subtipo === 'cambiar_contraseña_usuario_actual') {  //Corregido!
                $contrasena_actual = $datos_recibidos->contrasena_actual;
                $contrasena_nueva = $datos_recibidos->contrasena_nueva;
                $email = $datos_recibidos->email;
                $nombre = $datos_recibidos->nombre;
                $id = $datos_recibidos->id;
                $stmt = $pdo -> prepare('SELECT * FROM usuarios_app WHERE id = ?');
                $stmt -> execute([ $id ]);
                $row = $stmt -> fetchAll();
                foreach($row as $rows) {
                    if(password_verify($contrasena_actual, $rows['clave'])) {
                        if(password_verify($contrasena_nueva, $rows['clave'])) {
                            $out = [
                                "resultado" => "contraseña nueva ya en uso"
                            ];
                        }else {
                            $contrasena_nueva_cifrada = password_hash($contrasena_nueva, PASSWORD_DEFAULT);
                            $stmt = $pdo -> prepare('UPDATE usuarios_app SET clave = ? WHERE id = ?');
                            $stmt -> execute([ $contrasena_nueva_cifrada, $id ]);
                            if($stmt) {
                                $to = $email;
                                $subject = 'Cambio De Contraseña';
                                $message = '
                                    <html>
                                    <head>
                                    <meta charset="utf-8">
                                    <title>MerovingioApp - Gestion de Productos</title>
                                    <meta name="viewport" content="width=device-width, initial-scale=1">
                                    <meta http-equiv="Content-Language" content="en-us">
                                    </head>
                                    <body>
                                    <h3>Cambio De Contraseña</h3>
                                    <p>Hola ' . $nombre . ', Se ha cambiado tu contraseña reciéntemente</p>
                                    <p><strong>Si no has hecho tú este cambio o no lo has autorizado, te recomendamos contactar con el equipo de soporte para generar una nueva contraseña</strong></p>
                                    <p>Equipo de soporte MerovingioApp&copy;</p>
                                    </body>
                                    </html>
                                    ';
                                $headers = "MIME-Version: 1.0" . "\r\n";
                                $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
                                $headers .= "From: info.support@merovingioapp.es" . "\r\n" .
                                    "Reply-To: info.support@merovingioapp.es" . "\r\n" .
                                    "X-Mailer: PHP/" . phpversion();
                                if(mail($to, $subject, $message, $headers)) {
                                    $nuevo_token = bin2hex(random_bytes(100));
                                    $stmt = $pdo -> prepare('UPDATE usuarios_app SET token = ? WHERE id = ?');
                                    $stmt -> execute([ $nuevo_token, $id ]);
                                    $out = [
                                    "resultado" => "contraseña actualizada"
                                    ];
                                }else {
                                    $contrasena_actual_cifrada = password_hash($contrasena_actual, PASSWORD_DEFAULT);
                                    $stmt = $pdo -> prepare('UPDATE usuarios_app SET clave = ? WHERE id = ?');
                                    $stmt -> execute([ $contrasena_actual_cifrada ]);
                                    $out = [
                                        "resultado" => "email no enviado"
                                    ];
                                }
                            }else {
                                $out = [
                                    "resultado" => "contraseña no actualizada"
                                ];
                            }
                        }
                    }else {
                        $out = [
                            "resultado" => "la contraseña actual no coincide"
                        ];
                    }
                }
            }
            if($subtipo === 'añadir_nuevo_modelo') {    //Corregido!
                $equipo_nuevo = $datos_recibidos->equipo_nuevo;
                $stmt = $pdo -> prepare('SELECT COUNT(*) FROM listado_modelos_app WHERE nombre = ?');
                $stmt -> execute([ $equipo_nuevo ]);
                if($stmt -> fetchColumn() > 0) {
                    $out = [
                        "resultado" => "el modelo ya existe"
                    ];
                }else {
                    $ruta_imagen = '/assets/equipos/default.png';
                    $stmt = $pdo -> prepare('INSERT INTO listado_modelos_app(nombre, imagen) VALUES(?,?)');
                    $stmt -> execute([ $equipo_nuevo, $ruta_imagen ]);
                    if($stmt) {
                        $out = [
                            "resultado" => "nuevo modelo insertado"
                        ];
                    }else {
                        $out = [
                            "resultado" => "nuevo modelo no insertado"
                        ];
                    }
                }
            }
            if($subtipo === 'eliminar_modelo') {    //Corregido!
                $modelo = $datos_recibidos->modelo;
                $stmt = $pdo -> prepare('DELETE FROM listado_modelos_app WHERE nombre = ?');
                $stmt -> execute([ $modelo ]);
                if($stmt) {
                    $stmt = $pdo -> prepare('DELETE FROM equipos_insertados_app WHERE modelo = ?');
                    $stmt -> execute([ $modelo ]);
                    if($stmt) {
                        $out = [
                            "resultado" => "equipo eliminado"
                        ];
                    }else {
                        $ruta_imagen = '/assets/equipos/default.png';
                        $stmt = $pdo -> prepare('INSERT INTO listado_modelos_app(nombre, imagen) VALUES(?,?)');
                        $stmt -> execute([ $modelo, $ruta_imagen ]);
                        $out = [
                            "resultado" => "equipo no eliminado"
                        ];
                    }
                }else {
                    $out = [
                        "resultado" => "equipo no eliminado"
                    ];
                }
            }
            if($subtipo === 'gestionar_usuarios') { //Corregido!
                $accion = $datos_recibidos->accion;
                $id = $datos_recibidos->id;
                if($accion === 'hacer_administrador') $stmt = $pdo -> prepare('UPDATE usuarios_app SET tipo = "1" WHERE id = ?');
                if($accion === 'quitar_administrador') $stmt = $pdo -> prepare('UPDATE usuarios_app SET tipo = "0" WHERE id = ?');
                if($accion === 'deshabilitar') $stmt = $pdo -> prepare('UPDATE usuarios_app SET estado = "0" WHERE id = ?');
                if($accion === 'habilitar') $stmt = $pdo -> prepare('UPDATE usuarios_app SET estado = "1" WHERE id = ?');
                if($accion === 'eliminar') $stmt = $pdo -> prepare('DELETE FROM usuarios_app WHERE id = ?');
                $stmt -> execute([ $id ]);
                if($stmt) {
                    $out = [
                        "accion" => $accion,
                        "resultado" => "accion completada"
                    ];
                }else {
                    $out = [
                        "accion" => $accion,
                        "resultado" => "accion no completada"
                    ];
                }
            }
            if($subtipo === 'generar_nueva_contraseña') {   //Corregido!
                $accion = $datos_recibidos->accion;
                $id = $datos_recibidos->id;
                $nombre = $datos_recibidos->nombre;
                $email = $datos_recibidos->email;
                $contrasena_generada = bin2hex(random_bytes(4));
                $token = bin2hex(random_bytes(100));
                $contrasena_generada_cifrada = password_hash($contrasena_generada, PASSWORD_DEFAULT);
                $to = $email;
                $subject = 'Contraseña Generada';
                $message = '
                    <html>
                    <head>
                    <meta charset="utf-8">
                    <title>MerovingioApp - Gestion de Productos</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <meta http-equiv="Content-Language" content="en-us">
                    </head>
                    <body>
                    <h3>Contraseña Generada</h3>
                    <p>Hola ' . $nombre . ', se ha generado una nueva contraseña, estos son tus datos de acceso:</p>
                    <ul>
                    <li><strong>Email</strong>: ' . $email . '</li>
                    <li><strong>Contraseña</strong>: ' . $contrasena_generada . '</li>
                    </ul>
                    <p><strong>Recomendamos cambiar la contraseña una vez iniciada sesión en la App. Este email contiene datos sensibles, no lo compartas con otras personas.</strong></p>
                    <p>Equipo de soporte MerovingioApp&copy;</p>
                    </body>
                    </html>
                    ';
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
                $headers .= "From: info.support@merovingioapp.es" . "\r\n" .
                    "Reply-To: info.support@merovingioapp.es" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();
                if(mail($to, $subject, $message, $headers)) {
                    $stmt = $pdo -> prepare('UPDATE usuarios_app SET clave = ?, token = ? WHERE id = ?');
                    $stmt -> execute([ $contrasena_generada_cifrada, $token, $id ]);
                    $out = [
                        "accion" => $accion,
                        "resultado" => "accion completada"
                    ];
                }else {
                    $out = [
                        "accion" => $accion,
                        "resultado" => "accion no completada"
                    ];
                }
            }
            if($subtipo === 'obtener_usuario_seleccionado') {   //Corregido!
                $id = $datos_recibidos->id;
                $stmt = $pdo -> prepare('SELECT * FROM usuarios_app WHERE id = ?');
                $stmt -> execute([ $id ]);
                $row = $stmt -> fetchAll();
                $out = $row;
            }
            if($subtipo === 'obtener_usuarios') {   //Corregido!
                $stmt = $pdo -> prepare('SELECT * FROM usuarios_app');
                $stmt -> execute();
                $row = $stmt -> fetchAll();
                $out = $row;
            }
            if($subtipo === 'añadir_nuevo_usuario') {   //Corregido!
                $nombre = $datos_recibidos->nombre;
                $email = $datos_recibidos->email;
                $administrador = $datos_recibidos->administrador;
                $stmt = $pdo -> prepare('SELECT COUNT(*) FROM usuarios_app WHERE email = ?');
                $stmt -> execute([ $email ]);
                if($stmt -> fetchColumn() > 0) {
                    $out = [
                        "resultado" => "el usuario existe"
                    ];
                }else {
                    $contrasena_generada = bin2hex(random_bytes(4));
                    $token = bin2hex(random_bytes(100));
                    $contrasena_generada_cifrada = password_hash($contrasena_generada, PASSWORD_DEFAULT);
                    if($administrador === 'true') {
                        $stmt = $pdo -> prepare('INSERT INTO usuarios_app(nombre, email, clave, token, estado, tipo) VALUES(?,?,?,?,"1","1")');
                    }else {
                        $stmt = $pdo -> prepare('INSERT INTO usuarios_app(nombre, email, clave, token, estado, tipo) VALUES(?,?,?,?,"1","0")');
                    }
                    $stmt -> execute([ $nombre, $email, $contrasena_generada_cifrada, $token ]);
                    if($stmt) {
                        $to = $email;
                        $subject = 'Datos De Acceso';
                        $message = '
                            <html>
                            <head>
                            <meta charset="utf-8">
                            <title>MerovingioApp - Gestion de Productos</title>
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <meta http-equiv="Content-Language" content="en-us">
                            </head>
                            <body>
                            <h3>Datos de Acceso</h3>
                            <p>Hola ' . $nombre . ', Estos son tus datos de acceso.</p>
                            <ul>
                                <li><strong>Email</strong>: ' . $email . '</li>
                                <li><strong>Contraseña</strong>: ' . $contrasena_generada . '</li>
                            </ul>
                            <p><strong>Recomendamos cambiar la contraseña una vez iniciada sesión en la App. Este email contiene datos sensibles, no lo compartas con otras personas.</strong></p>
                            <p>Equipo de soporte MerovingioApp&copy;</p>
                            </body>
                            </html>
                            ';
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
                        $headers .= "From: info.support@merovingioapp.es" . "\r\n" .
                            "Reply-To: info.support@merovingioapp.es" . "\r\n" .
                            "X-Mailer: PHP/" . phpversion();
                        if(mail($to, $subject, $message, $headers)) {
                            $out = [
                                "resultado" => "usuario insertado"
                            ];
                        }else {
                            $stmt = $pdo -> prepare('DELETE FROM usuarios_app WHERE email = ?');
                            $stmt -> execute([ $email ]);
                            $out = [
                                "resultado" => "usuario no insertado"
                            ];
                        }
                    }else {
                        $out = [
                            "resultado" => "usuario no insertado"
                        ];
                    }
                }
            }
            if($subtipo === 'cambiar_avatar') { //Corregido!
                $avatar = $datos_recibidos->avatar;
                $id = $datos_recibidos->id;
                $stmt = $pdo -> prepare('UPDATE usuarios_app SET avatar = ? WHERE id = ?');
                $stmt -> execute([ $avatar, $id ]);
                if($stmt) {
                    $out = [
                        "resultado" => "avatar cambiado"
                    ];
                }else {
                    $out = [
                        "resultado" => "avatar no cambiado"
                    ];
                }
            }
            if($subtipo === 'añadir_nuevo_componente_categoria_actual') {   //Corregido!
                $categoria_actual = $datos_recibidos->categoria_actual;
                $componente_nuevo = $datos_recibidos->componente_nuevo;
                $cantidad = $datos_recibidos->cantidad;
                $stmt = $pdo -> prepare('INSERT INTO componentes_app(categoria, componente, cantidad) VALUES(?,?,?)');
                $stmt -> execute([ $categoria_actual, $componente_nuevo, $cantidad ]);
                if($stmt) {
                    $out = [
                        "resultado" => "componente insertado"
                    ];
                }else {
                    $out = [
                        "resultado" => "componente no insertado"
                    ];
                }
            }
            if($subtipo === 'añadir_nuevo_componente_categoria_nueva') {    //Corregido!
                $categoria_nueva = $datos_recibidos->categoria_nueva;
                $componente_nuevo = $datos_recibidos->componente_nuevo;
                $cantidad = $datos_recibidos->cantidad;
                $stmt = $pdo -> prepare('INSERT INTO listado_categorias_app(nombre) VALUES(?)');
                $stmt -> execute([ $categoria_nueva ]);
                if($stmt) {
                    $stmt = $pdo -> prepare('INSERT INTO componentes_app(categoria, componente, cantidad) VALUES(?,?,?)');
                    $stmt -> execute([ $categoria_nueva, $componente_nuevo, $cantidad ]);
                    if($stmt) {
                        $out = [
                            "resultado" => "componente nuevo insertado"
                        ];
                    }else {
                        $stmt = $pdo -> prepare('DELETE FROM listado_categorias_app WHERE categoria = ?');
                        $stmt -> execute([ $categoria_nueva ]);
                        $out = [
                            "resultado" => "componente nuevo no insertado"
                        ];
                    }
                }else {
                    $out = [
                        "resultado" => "componente no insertado"
                    ];
                }
            }
            break;
        case 'autorizacion':  //DONE!
            if($subtipo === 'iniciar_sesion') { //Corregido!
                $email = $datos_recibidos->email;
                $contrasena = $datos_recibidos->contrasena;
                $stmt = $pdo -> prepare('SELECT COUNT(*) FROM usuarios_app WHERE email = ?');
                $stmt -> execute([ $email ]);
                if($stmt -> fetchColumn() > 0) {
                    $stmt = $pdo -> prepare('SELECT * FROM usuarios_app WHERE email = ?');
                    $stmt -> execute([ $email ]);
                    $row = $stmt -> fetchAll();
                    foreach($row as $rows) {
                        if(password_verify($contrasena, $rows['clave'])) {
                            if($rows['estado'] === '1') {
                                $out = [
                                    "token" => $rows['token'],
                                    "resultado" => "contraseña correcta"
                                ];
                            }else {
                                $out = [
                                    "resultado" => "usuario deshabilitado"
                                ];
                            }
                        }else {
                            $out = [
                                "resultado" => "contraseña incorrecta"
                            ];
                        }
                    }
                }else {
                    $out = [
                        "resultado" => "usuario no encontrado"
                    ];
                }
            }
            if($subtipo === 'obtener_usuario_logueado') {   //Corregido!
                $token = $datos_recibidos->token;
                $stmt = $pdo -> prepare('SELECT * FROM usuarios_app WHERE token = ?');
                $stmt -> execute([ $token ]);
                $row = $stmt -> fetchAll();
                foreach($row as $rows) {
                    $out = [
                        "nombre" => $rows['nombre'],
                        "email" => $rows['email'],
                        "estado" => $rows['estado'],
                        "tipo" => $rows['tipo'],
                        "ruta" => $rows['avatar'],
                        "id" => $rows['id']
                    ];
                }
            }
            if($subtipo === 'verificar_token') {    //Corregido!
                $token = $datos_recibidos->token;
                $stmt = $pdo -> prepare('SELECT COUNT(*) FROM usuarios_app WHERE token = ?');
                $stmt -> execute([ $token ]);
                if($stmt -> fetchColumn() > 0) {
                    $out = [
                        "resultado" => "el token existe"
                    ];
                }else {
                    $out = [
                        "resultado" => "el token no existe"
                    ];
                }
            }
            break;
    }

    echo json_encode($out);

?>