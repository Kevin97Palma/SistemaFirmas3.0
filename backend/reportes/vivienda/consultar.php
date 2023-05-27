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

$statement = $conexion->prepare("
SELECT
C.nombre as ciudad,
P.nombre as proyecto,
S.nombre as sector,
V.nombre as nombre,
V.estado as estado,
V.precio,
COALESCE(SUM(costoMaterial), 0) + COALESCE(SUM(costoServicio), 0) AS inversion
FROM
ciudad C, proyecto P, sector S, costo_vivienda CV, vivienda V
WHERE
CV.idViviendaFK = V.idVivienda
AND
V.idSectorFK = S.idSector
AND
V.idProyectoFK = P.idProyecto
AND
P.idCiudadFK = C.idCiudad
AND
P.idSectorFK = S.idSector
GROUP BY idViviendaFK;
");
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