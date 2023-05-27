<?php

require_once('../conexion/config.php');
$mysql = new connection();
$conexion = $mysql->getConnection();

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$message = array();

$usuarios = array();
$usuarios['datos'] = array();

$rol = $data['rol'];

$statement = $conexion->prepare("SELECT idPersona as codigo, identificacion, P.nombre as nombres, P.apellido as apellidos, P.correo, P.telefono, U.nombre as usuario, U.contrasena, U.rol FROM usuario U, persona P
WHERE U.idPersonaFK = P.idPersona AND rol = ?");
$statement->bind_param("i",$rol);

$statement->execute(); 
if($resultSet = $statement->get_result()){
    $result = $resultSet->fetch_all(MYSQLI_ASSOC);
    foreach($result as $data){
    foreach($data as $k => $v)
        $datos[$k] = utf8_encode($v);
        array_push($usuarios['datos'], $datos);
    }
    $conexion->close();
} else {
    echo json_encode(array('status'=>false));
}

if (count($usuarios) > 0) {
    echo json_encode(array('status'=>true,'datos'=>$usuarios['datos']));
} else {
    echo json_encode(array('status'=>false));
}
?>