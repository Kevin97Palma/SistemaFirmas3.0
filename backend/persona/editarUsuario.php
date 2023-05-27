<?php

require_once('../conexion/config.php');
$mysql = new connection();
$conexion = $mysql->getConnection();

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$usuario = $data['identificacion'];
$nombre = $data['nombres'];
$apellido = $data['apellidos'];
$correo = $data['correo'];
$telefono = $data['telefono'];
$contrasena = $data['contrasena'];
$rol = $data['rol'];

$codigoUsuario = $data['codigo'];

if($usuario && $contrasena != ''){

    $hash = password_hash($contrasena,PASSWORD_DEFAULT,['cost' => 10]);

    $statement = $conexion->prepare("CALL actualizarUsuario (?,?,?,?,?,?,?,?)");
    $statement->bind_param("sssssssi",$usuario,$nombre,$apellido,$correo,$telefono,$hash,$rol,$codigoUsuario);

    if ($statement->execute()) {
        http_response_code(201);
        echo true;
    }else{
        http_response_code(422);
        echo false;
    }

    $conexion->close();
} else {
    echo false;
}

?>