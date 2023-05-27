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

$materiales = array();
$materiales['datos'] = array();

$statement = $conexion->prepare("
SELECT
idCosto AS id,
idViviendaFK AS codigoVivienda,
idMaterialFK AS codigo,
M.nombre,
'Material' AS tipo,
cantidadMaterial AS cantidad,
ROUND((costoMaterial / cantidadMaterial),2) AS precio,
costoMaterial AS total
FROM
costo_vivienda CV, material M
WHERE
CV.idMaterialFK = M.idMaterial
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