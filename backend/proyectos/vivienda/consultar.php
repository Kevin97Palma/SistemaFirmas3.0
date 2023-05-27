<?php

include "../../conexion/config.php";
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET");
header("Allow: GET");

$mysql = new connection();
$conexion = $mysql->getConnection();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$message = array();

$viviendas = array();
$viviendas['datos'] = array();

$statement = $conexion->prepare("SELECT idVivienda as codigo,idProyectoFK as codigoProyecto,V.nombre,V.numero,V.estado,V.precio,V.factura,V.idSectorFK as codigoSector,V.linkGPS FROM vivienda V,proyecto P WHERE idProyectoFK = idProyecto");
$statement->execute(); 

if($resultSet = $statement->get_result()){
    $result = $resultSet->fetch_all(MYSQLI_ASSOC);
    foreach($result as $data){
    foreach($data as $k => $v)
        $datos[$k] = utf8_encode($v);
        array_push($viviendas['datos'], $datos);
    }
    $conexion->close();
} else {
    echo json_encode(array('status'=>false));
}

if (count($viviendas) > 0) {
    echo json_encode(array('status'=>true,'datos'=>$viviendas['datos']));
} else {
    echo json_encode(array('status'=>false));
}

?>