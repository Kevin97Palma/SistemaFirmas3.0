<?php

require_once('../conexion/config.php');
$mysql = new connection();
$conexion = $mysql->getConnection();

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$message = array();

$userAcces = array();
$userAcces['credentials'] = array();

$usuario = $data['usuario'];
$contrasena = $data['contrasena'];

$statement = $conexion->prepare("SELECT nombre as usuario, contrasena, rol FROM usuario WHERE nombre = ? LIMIT 1");
$statement->bind_param("s",$usuario);

$statement->execute(); 
$resultSet = $statement->get_result();  
$result = $resultSet->fetch_all(MYSQLI_ASSOC);
foreach($result as $data){
    foreach($data as $k => $v)
        $datos[$k] = utf8_encode($v);
        array_push($userAcces['credentials'], $datos);
}

$existUser = count($userAcces['credentials']);

if ($existUser > 0) {
    $usrDB = $userAcces['credentials'][0]['usuario'];
    $pswDB = $userAcces['credentials'][0]['contrasena'];
    $type = $userAcces['credentials'][0]['rol'];
    if ($usrDB === $usuario ) {
        if (password_verify($contrasena,$pswDB)) {
            $response []= array("access" => "ok", "type" => $type);
        } else {
            $response []= array("access" => "error");
        }
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);

$conexion->close();
?>