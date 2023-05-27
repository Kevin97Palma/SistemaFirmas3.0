<?php

require_once('../conexion/config.php');
$mysql = new connection();
$conexion = $mysql->getConnection();

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$message = array();

$userAcces = array();
$userAcces['credentials'] = array();

$usuario = $data['identificacion'];
$nombre = $data['nombres'];
$apellido = $data['apellidos'];
$correo = $data['correo'];
$telefono = $data['telefono'];
$contrasena = $data['contrasena'];
$rol = $data['rol'];

function verificarUsuario($conexion,$usuario,$nombre,$apellido,$correo,$telefono,$contrasena,$rol){
    $statement1 = $conexion->prepare("SELECT '' FROM persona WHERE identificacion = ?");
    $statement1->bind_param("s", $usuario);
    $statement1->execute();
    $resultado = $statement1->get_result();

    if ($resultado->num_rows > 0) {
        echo json_encode(array('status'=>false,'msg'=>'El usuario ya se encuentra registrado'));
    } else {
        registrarUsuario($conexion,$usuario,$nombre,$apellido,$correo,$telefono,$contrasena,$rol);
    }

    $conexion->close();
}

if (isset($data['identificacion']) && isset($data['nombres']) && isset($data['apellidos']) && isset($data['correo']) && isset($data['telefono']) && isset($data['contrasena']) && isset($data['rol'])) {

    $usuario = $data['identificacion'];
    $nombre = $data['nombres'];
    $apellido = $data['apellidos'];
    $correo = $data['correo'];
    $telefono = $data['telefono'];
    $contrasena = $data['contrasena'];
    $rol = $data['rol'];

    verificarUsuario($conexion,$usuario,$nombre,$apellido,$correo,$telefono,$contrasena,$rol);
} else {
    echo json_encode(array('status'=>false,'msg'=>'Faltan datos en la petición'));
}

function registrarUsuario($conexion,$usuario,$nombre,$apellido,$correo,$telefono,$contrasena,$rol){

    $hash = password_hash($contrasena,PASSWORD_DEFAULT,['cost' => 10]);

    $statement2 = $conexion->prepare("CALL registrarUsuario (?,?,?,?,?,?,?)");
    $statement2->bind_param("sssssss",$usuario,$nombre,$apellido,$correo,$telefono,$hash,$rol);
        
    if ($statement2->execute()) {
        http_response_code(201);
        echo json_encode(array('status'=>true));
    }else{
        http_response_code(422);
        echo json_encode(array('status'=>false,'msg'=>'Intentelo Nuevamente'));
    }
}
?>