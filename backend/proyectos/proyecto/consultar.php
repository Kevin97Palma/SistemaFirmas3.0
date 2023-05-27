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

$proyectos = array();
$proyectos['datos'] = array();

$statement = $conexion->prepare("SELECT idProyecto as codigo,p.nombre,p.jefeObra as responsable, p.idCiudadFK as codigoCiudad, p.idSectorFK as codigoSector, c.nombre as nombreCiudad, s.nombre as nombreSector
FROM proyecto AS p, ciudad AS c, sector AS s WHERE p.idSectorFK = s.idSector AND p.idCiudadFK = c.idCiudad");
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