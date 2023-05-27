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

if(isset($data['codigo']) && !empty($data['codigo']) && isset($data['nombre']) && !empty($data['nombre']) && isset($data['numero']) && !empty($data['numero'])
&& isset($data['estado']) && !empty($data['estado']) && isset($data['precio']) && !empty($data['precio']) && isset($data['codigoSector']) && !empty($data['codigoSector'])
&& isset($data['linkGPS']) && !empty($data['linkGPS'])){

    $statement = $conexion->prepare("UPDATE vivienda SET nombre = ?,numero= ?,estado= ?,precio= ?,factura= ?,idSectorFK = ?,linkGPS = ? WHERE idVivienda = ?");
    $statement->bind_param("sssssssi",$data['nombre'],$data['numero'],$data['estado'],$data['precio'],$data['factura'],$data['codigoSector'],$data['linkGPS'],$data['codigo']);

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