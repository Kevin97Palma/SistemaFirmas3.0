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
$valores = array();
$cantFilas = 0;
$insertadas = 0;

$sql = "";

foreach ($data as $row) {
    $cod = $row['codigo'];
    $codV = $row['codigovivienda'];
    $codM = $row['codigoMaterial'];
    $cantM = $row['cantidadMaterial'];
    $costM = $row['costoMaterial'];
    $codS = $row['codigoServicio'];
    $cantS = $row['cantidadServicio'];
    $costS = $row['costoServicio'];

    // $sql .= $cod.", ".$codV.", ".$codM.", ".$cantM.", ".$costM.", ".$codS.", ".$cantS.", ".$costS." ";

    $statement = $conexion->prepare("INSERT INTO costo_vivienda(idCosto,idViviendaFK, idMaterialFK, cantidadMaterial, costoMaterial, idServicioFK, cantidadServicio, costoServicio) 
    VALUES ($cod, $codV, $codM, $cantM, $costM, $codS, $cantS, $costS) ON DUPLICATE KEY UPDATE idViviendaFK=VALUES(idViviendaFK), idMaterialFK=VALUES(idMaterialFK),
    cantidadMaterial=VALUES(cantidadMaterial), costoMaterial=VALUES(costoMaterial), idServicioFK=VALUES(idServicioFK), cantidadServicio=VALUES(cantidadServicio), 
    costoServicio=VALUES(costoServicio);");
    
    // $statement->bind_param("ssiddidd",$cod, $codV, $codM, $cantM, $costM, $codS, $cantS, $costS);

    if ($statement->execute()) {
        $insertadas ++;
    }
}

if(count($data) == $insertadas){
    $message['status'] = true;
} else {
    $message['status'] = false;
}

echo json_encode($message);
$conexion->close();
// echo json_encode($sql);
?>