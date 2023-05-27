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

$servicios = array();
$servicios['datos'] = array();

$statement = $conexion->prepare("
SELECT
idCosto AS id,
idViviendaFK AS codigoVivienda,
idServicioFK AS codigo,
S.nombre,
'Servicio' AS tipo,
cantidadServicio AS cantidad,
ROUND((costoServicio / cantidadServicio),2) AS precio,
costoServicio AS total
FROM
costo_vivienda CV, servicio S
WHERE
CV.idServicioFK = S.idServicio
AND
idViviendaFK = ?;
");
$statement->bind_param("i",$data['codigoVivienda']);
$statement->execute(); 

if($resultSet = $statement->get_result()){
    $result = $resultSet->fetch_all(MYSQLI_ASSOC);
    foreach($result as $data){
    foreach($data as $k => $v)
        $datos[$k] = utf8_encode($v);
        array_push($servicios['datos'], $datos);
    }
    $conexion->close();
} else {
    echo json_encode(array('status'=>false));
}

if (count($servicios) > 0) {
    echo json_encode(array('status'=>true,'datos'=>$servicios['datos']));
} else {
    echo json_encode(array('status'=>false));
}

?>