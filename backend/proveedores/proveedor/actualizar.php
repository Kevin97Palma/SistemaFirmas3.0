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

if(isset($data['ruc']) && !empty($data['ruc']) && isset($data['razonSocial']) && !empty($data['razonSocial'])
&& isset($data['direccion']) && !empty($data['direccion']) && isset($data['telefono']) && !empty($data['telefono'])
&& isset($data['correo']) && !empty($data['correo'])){

    $statement = $conexion->prepare("UPDATE proveedor SET ruc = ?,razonSocial= ?,direccion= ?,telefono= ?,correo= ? WHERE idProveedor = ?");
    $statement->bind_param("sssssi",$data['ruc'],$data['razonSocial'],$data['direccion'],$data['telefono'],$data['correo'],$data['codigo']);

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