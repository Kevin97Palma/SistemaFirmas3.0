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

$inversion = array();
$inversion['datos'] = array();

$statement = $conexion->prepare("
SELECT COALESCE(SUM(costoMaterial), 0) + COALESCE(SUM(costoServicio), 0) AS inversion
FROM costo_vivienda;");
$statement->execute(); 

if($resultSet = $statement->get_result()){
    $result = $resultSet->fetch_all(MYSQLI_ASSOC);
    foreach($result as $data){
    foreach($data as $k => $v)
        $datos[$k] = utf8_encode($v);
        array_push($inversion['datos'], $datos);
    }
    $conexion->close();
} else {
    echo json_encode(array('status'=>false));
}

if (count($inversion) > 0) {
    echo json_encode(array('status'=>true,'datos'=>$inversion['datos']));
} else {
    echo json_encode(array('status'=>false));
}

?>