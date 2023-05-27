<?php
    require('../../fpdf/fpdf.php');
    include "../../conexion/config.php";

    $mysql = new connection();
    $conexion = $mysql->getConnection();

    $codigo = $_GET['id'];

    class PDF extends FPDF
    {
    // Cabecera de página
        // function Header()
        // {
        //     // Arial bold 15
        //     $this->SetFont('Arial','B',15);
        //     // Movernos a la derecha
        //     $this->Cell(110);
        //     // Título
        //     $this->Cell(52,10,utf8_decode('HISTORIA CLÍNICA No. '.$id),0,0,'C');
        //     // Salto de línea
        //     $this->Ln(20);
        // }

        // Pie de página
        function Footer()
        { 
            // Posición: a 1,5 cm del final
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Número de página
            $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
        }
    }

    $pdf = new PDF('P','mm','A4');
    $pdf->AddPage();
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','',16);

    $consultaEnca = "
        SELECT
        C.nombre as ciudad,
        S.nombre as sector,
        P.nombre as proyecto,
        V.nombre as vivienda,
        V.precio
        FROM ciudad C, proyecto P, sector S, costo_vivienda CV, vivienda V
        WHERE CV.idViviendaFK = V.idVivienda AND V.idSectorFK = S.idSector
        AND V.idProyectoFK = P.idProyecto AND P.idCiudadFK = C.idCiudad
        AND P.idSectorFK = S.idSector AND V.idVivienda = ? GROUP BY idViviendaFK;
    ";

    $consultaMate = "
        SELECT
        M.nombre,
        'Material' AS tipo,
        cantidadMaterial AS cantidad,
        ROUND((costoMaterial / cantidadMaterial),2) AS precio,
        costoMaterial AS total
        FROM costo_vivienda CV, material M
        WHERE CV.idMaterialFK = M.idMaterial
        AND idViviendaFK = ?;
    ";

    $consultaServi = "
        SELECT
        S.nombre,
        'Servicio' AS tipo,
        cantidadServicio AS cantidad,
        ROUND((costoServicio / cantidadServicio),2) AS precio,
        costoServicio AS total
        FROM costo_vivienda CV, servicio S
        WHERE CV.idServicioFK = S.idServicio
        AND idViviendaFK = ?;
    ";

    function consultarDatos($sqlQuery,$conexion,$codigo){
        $info = array();
        $info['datos'] = array();

        $statement = $conexion->prepare($sqlQuery);
        $statement->bind_param("i",$codigo);
        $statement->execute(); 

        $resultSet = $statement->get_result();
        $result = $resultSet->fetch_all(MYSQLI_ASSOC);
        foreach($result as $data){
        foreach($data as $k => $v)
            $datos[$k] = utf8_encode($v);
            array_push($info['datos'], $datos);
        }

        return $info['datos'];
    }

    $encabezado = consultarDatos($consultaEnca,$conexion,$codigo);
    $materiales = consultarDatos($consultaMate,$conexion,$codigo);
    $servicios = consultarDatos($consultaServi,$conexion,$codigo);

    $i = 1;
    $subTotal = 0;
    $conexion->close();

    /*
    $statement = $conexion->prepare("
        SELECT
        C.nombre as ciudad,
        S.nombre as sector,
        P.nombre as proyecto,
        V.nombre as vivienda
        FROM ciudad C, proyecto P, sector S, costo_vivienda CV, vivienda V
        WHERE CV.idViviendaFK = V.idVivienda AND V.idSectorFK = S.idSector
        AND V.idProyectoFK = P.idProyecto AND P.idCiudadFK = C.idCiudad
        AND P.idSectorFK = S.idSector AND V.idVivienda = ? GROUP BY idViviendaFK;
    ");
    $statement->bind_param("i",$codigo);
    $statement->execute(); 

    $resultSet = $statement->get_result();
    $result = $resultSet->fetch_all(MYSQLI_ASSOC);
    foreach($result as $data){
    foreach($data as $k => $v)
        $datos[$k] = utf8_encode($v);
        array_push($encab['datos'], $datos);
    }
    $conexion->close();

    $statement2 = $conexion->prepare("
        SELECT
        C.nombre as ciudad,
        S.nombre as sector,
        P.nombre as proyecto,
        V.nombre as vivienda
        FROM ciudad C, proyecto P, sector S, costo_vivienda CV, vivienda V
        WHERE CV.idViviendaFK = V.idVivienda AND V.idSectorFK = S.idSector
        AND V.idProyectoFK = P.idProyecto AND P.idCiudadFK = C.idCiudad
        AND P.idSectorFK = S.idSector AND V.idVivienda = ? GROUP BY idViviendaFK;
    ");
    $statement2->bind_param("i",$codigo);
    $statement2->execute(); 

    $resultSet2 = $statement2->get_result();
    $result2 = $resultSet2->fetch_all(MYSQLI_ASSOC);
    foreach($result2 as $data){
    foreach($data as $k => $v)
        $datos[$k] = utf8_encode($v);
        array_push($mater['datos'], $datos);
    }
    $conexion->close();
    */

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(190,5,utf8_decode('DETALLE DE COSTOS VIVIENDA'),0,1,'C',0);
    $pdf->Cell(10,5,'',0,1,'C',0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40);
    $pdf->Cell(35,12,utf8_decode('Ciudad:'),1,0,'L',0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(75,12,utf8_decode($encabezado[0]['ciudad']),1,1,'L',0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40);
    $pdf->Cell(35,12,utf8_decode('Sector:'),1,0,'L',0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(75,12,utf8_decode($encabezado[0]['sector']),1,1,'L',0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40);
    $pdf->Cell(35,12,utf8_decode('Proyecto:'),1,0,'L',0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(75,12,utf8_decode($encabezado[0]['proyecto']),1,1,'L',0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40);
    $pdf->Cell(35,12,utf8_decode('Nombre Vivienda:'),1,0,'L',0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(75,12,utf8_decode($encabezado[0]['vivienda']),1,1,'L',0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40);
    $pdf->Cell(35,12,utf8_decode('Precio Vivienda:'),1,0,'L',0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(75,12,utf8_decode($encabezado[0]['precio']),1,1,'L',0);
    $pdf->Ln(15);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(190,5,utf8_decode('Servicios y Materiales Utilizados'),0,1,'L',0);
    $pdf->Cell(190,5,utf8_decode(''),0,1,'L',0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(15,10,utf8_decode('No.'),1,0,'C',0);
    $pdf->Cell(30,10,utf8_decode('Tipo'),1,0,'C',0);
    $pdf->Cell(75,10,utf8_decode('Nombre'),1,0,'C',0);
    $pdf->Cell(25,10,utf8_decode('Cantidad'),1,0,'C',0);
    $pdf->Cell(25,10,utf8_decode('Precio'),1,0,'C',0);        
    $pdf->Cell(25,10,utf8_decode('Total'),1,1,'C',0);  
    foreach ($materiales as $row) {
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(15,10,utf8_decode($i++),1,0,'C',0);
        $pdf->Cell(30,10,utf8_decode($row['tipo']),1,0,'C',0);
        $pdf->Cell(75,10,utf8_decode($row['nombre']),1,0,'C',0);
        $pdf->Cell(25,10,utf8_decode($row['cantidad']),1,0,'C',0);
        $pdf->Cell(25,10,utf8_decode($row['precio']),1,0,'C',0);        
        $pdf->Cell(25,10,utf8_decode($row['total']),1,1,'C',0);        
        $subTotal = $subTotal + $row['total'];
    }

    foreach ($servicios as $row) {
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(15,10,utf8_decode($i++),1,0,'C',0);
        $pdf->Cell(30,10,utf8_decode($row['tipo']),1,0,'C',0);
        $pdf->Cell(75,10,utf8_decode($row['nombre']),1,0,'C',0);
        $pdf->Cell(25,10,utf8_decode($row['cantidad']),1,0,'C',0);
        $pdf->Cell(25,10,utf8_decode($row['precio']),1,0,'C',0);        
        $pdf->Cell(25,10,utf8_decode($row['total']),1,1,'C',0);    
        $subTotal = $subTotal + $row['total'];    
    }

    $pdf->Ln(15);
    $pdf->Cell(50);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(30,10,utf8_decode('PRECIO'),1,0,'C',0);
    $pdf->Cell(30,10,utf8_decode('INVERSION'),1,0,'C',0);
    $pdf->Cell(30,10,utf8_decode('GANANCIA'),1,1,'C',0);
    $pdf->Cell(50);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,10,utf8_decode($encabezado[0]['precio']),1,0,'C',0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,10,utf8_decode($subTotal),1,0,'C',0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(30,10,utf8_decode($encabezado[0]['precio'] - $subTotal),1,1,'C',0);
    $nombreDoc = "";

$pdf->Output('I',$nombreDoc,false);

?>