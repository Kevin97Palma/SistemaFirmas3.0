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

$materiales = array();
$materiales['datos'] = array();

$statement = $conexion->prepare("SELECT idServicio as codigo, razonSocial as nombreProveedor, nombre, costoHora, costoViaticos, total, idProveedorFK as codigoProveedor FROM servicio, proveedor WHERE servicio.idProveedorFK = proveedor.idProveedor");
$statement->execute(); 

if($resultSet = $statement->get_result()){
    $result = $resultSet->fetch_all(MYSQLI_ASSOC);
    foreach($result as $data){
    foreach($data as $k => $v)
        $datos[$k] = utf8_encode($v);
        array_push($materiales['datos'], $datos);
    }
    $conexion->close();
} else {
    echo json_encode(array('status'=>false));
}   

if (count($materiales) > 0) {
    echo json_encode(array('status'=>true,'datos'=>$materiales['datos']));
} else {
    echo json_encode(array('status'=>false));
}

?>