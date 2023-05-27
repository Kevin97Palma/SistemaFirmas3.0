<?php

include "../../conexion/config.php";
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: POST");
header("Allow: POST");

$mysql = new connection();
$conexion = $mysql->getConnection();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$message = array();

if(isset($data['nombre']) && !empty($data['nombre']) && isset($data['direccion']) && !empty($data['direccion'])
&& isset($data['referencia']) && !empty($data['referencia']) && isset($data['gps']) && !empty($data['gps'])){

    $statement = $conexion->prepare("INSERT INTO sector(nombre,direccion,referencia,gpsLink) VALUES (?,?,?,?);");
    $statement->bind_param("ssss",$data['nombre'],$data['direccion'],$data['referencia'],$data['gps']);

    if ($statement->execute()) {
        http_response_code(201);
        $message['status'] = true;
    }else{
        http_response_code(422);
        $message['status'] = false;
    }

    $conexion->close();
} else {
    $message['status'] = false;
}

echo json_encode($message);
?>