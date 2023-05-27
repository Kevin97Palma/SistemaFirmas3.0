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

$costos = array();
$costos['datos'] = array();

$statement = $conexion->prepare("
SELECT
C.nombre as ciudad,
S.nombre as sector,
P.nombre as proyecto,
V.nombre as vivienda
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
AND
V.idVivienda = ?
GROUP BY idViviendaFK;
");
$statement->bind_param("i",$data['codigoVivienda']);
$statement->execute(); 

if($resultSet = $statement->get_result()){
    $result = $resultSet->fetch_all(MYSQLI_ASSOC);
    foreach($result as $data){
    foreach($data as $k => $v)
        $datos[$k] = utf8_encode($v);
        array_push($costos['datos'], $datos);
    }
    $conexion->close();
} else {
    echo json_encode(array('status'=>false));
}

if (count($costos) > 0) {
    echo json_encode(array('status'=>true,'datos'=>$costos['datos']));
} else {
    echo json_encode(array('status'=>false));
}

?>