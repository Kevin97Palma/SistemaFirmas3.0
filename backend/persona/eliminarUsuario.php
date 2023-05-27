<?php

require_once('../conexion/config.php');
$mysql = new connection();
$conexion = $mysql->getConnection();

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$codigoUsuario = $data['codigo'];

    $statement = $conexion->prepare("CALL eliminarUsuario (?)");
    $statement->bind_param("i",$codigoUsuario);

    if ($statement->execute()) {
        http_response_code(201);
        echo true;
    }else{
        http_response_code(422);
        echo false;
    }

    $conexion->close();
?>