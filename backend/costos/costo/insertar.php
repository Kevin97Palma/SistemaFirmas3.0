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

function verificarVivienda($conexion,$data){
    $codigo = $data[0]['codigovivienda'];

    // echo json_encode($data[0]['codigovivienda']);

    $statement1 = $conexion->prepare("SELECT '' FROM costo_vivienda WHERE idViviendaFK = ?");
    $statement1->bind_param("i", $codigo);
    $statement1->execute();
    $resultado = $statement1->get_result();

    // echo json_encode($resultado->num_rows);

    $filas = $resultado->num_rows;

    if ($filas > 0) {
        echo json_encode(array('status'=>false,'msg'=>'Esta Vivienda ya se Encuentra Registrada'));
    } else {
        $sql = "INSERT INTO costo_vivienda(idViviendaFK, idMaterialFK, cantidadMaterial, costoMaterial, idServicioFK, cantidadServicio, costoServicio) VALUES ";

        foreach ($data as $row) {
            $codV = utf8_decode($row['codigovivienda']);
            $codM = utf8_decode($row['codigoMaterial']);
            $cantM = utf8_decode($row['cantidadMaterial']);
            $costM = utf8_decode($row['costoMaterial']);
            $codS = utf8_decode($row['codigoServicio']);
            $cantS = utf8_decode($row['cantidadServicio']);
            $costS = utf8_decode($row['costoServicio']);
            $valores[] = "({$codV}, {$codM}, {$cantM}, {$costM}, {$codS}, {$cantS}, {$costS})";
        }

        $sql .= implode(", ", $valores);

        if(count($valores) > 0){

            $statement = $conexion->prepare($sql);

            if ($statement->execute()) {
                http_response_code(201);
                // $message['status'] = true;
                echo json_encode(array('status'=>true));
            }else{
                http_response_code(422);
                // $message['status'] = false;
                echo json_encode(array('status'=>false,'msg'=>'Intente Nuevamente'));
            }

            $conexion->close();
        } else {
            // $message['status'] = false;
            echo json_encode(array('status'=>false,'msg'=>'Intente Nuevamente'));
        }

        // echo json_encode($message);
    }
}

verificarVivienda($conexion,$data);


// echo json_encode($sql);
?>