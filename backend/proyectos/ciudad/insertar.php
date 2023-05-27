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

if(isset($data['nombre']) && !empty($data['nombre'])){

    $statement = $conexion->prepare("INSERT INTO ciudad(nombre) VALUES (?);");
    $statement->bind_param("s",$data['nombre']);

    if ($statement->execute()) {
        http_response_code(201);
        $message['status'] = "1";
    }else{
        http_response_code(422);
        $message['status'] = "0";
    }

    $conexion->close();
} else {
    $message['status'] = "0";
}

echo json_encode($message);
?>