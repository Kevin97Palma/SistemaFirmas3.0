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

$proyectos = array();
$proyectos['datos'] = array();

$statement = $conexion->prepare("SELECT idProyecto as codigoProyecto,p.nombre FROM proyecto AS p WHERE p.idSectorFK = ? AND p.idCiudadFK = ?");
$statement->bind_param("ii",$data['codigoSector'],$data['codigoCiudad']); 
$statement->execute(); 

if($resultSet = $statement->get_result()){
    $result = $resultSet->fetch_all(MYSQLI_ASSOC);
    foreach($result as $data){
    foreach($data as $k => $v)
        $datos[$k] = utf8_encode($v);
        array_push($proyectos['datos'], $datos);
    }
    $conexion->close();
} else {
    echo json_encode(array('status'=>false));
}

if (count($proyectos) > 0) {
    echo json_encode(array('status'=>true,'datos'=>$proyectos['datos']));
} else {
    echo json_encode(array('status'=>false));
}

?>