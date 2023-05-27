<?php
    require('../../fpdf/fpdf.php');
    include "../../conexion/config.php";

    $mysql = new connection();
    $conexion = $mysql->getConnection();

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

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

    $viviendas = array();
    $viviendas['datos'] = array();
    $statement = $conexion->prepare("
    SELECT
    P.nombre as proyecto,
    S.nombre as sector,
    V.nombre as vivienda,
    V.numero as numero,
    V.estado as estado,
    V.precio as precio
    FROM
    proyecto P, sector S, vivienda V
    WHERE
    V.idSectorFK = S.idSector
    AND
    V.idProyectoFK = P.idProyecto
    AND
    P.idSectorFK = S.idSector
    ");
    $statement->execute(); 

    $resultSet = $statement->get_result();
    $result = $resultSet->fetch_all(MYSQLI_ASSOC);
    foreach($result as $data){
    foreach($data as $k => $v)
        $datos[$k] = utf8_encode($v);
        array_push($viviendas['datos'], $datos);
    }
    $conexion->close();

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(190,5,utf8_decode('REPORTE DE VIVIENDAS'),0,1,'C',0);
    $pdf->Cell(10,5,'',0,1,'C',0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(35,12,utf8_decode('PROYECTO'),1,0,'C',0);
    $pdf->Cell(30,12,utf8_decode('SECTOR'),1,0,'C',0);
    $pdf->Cell(70,12,utf8_decode('NOMBRE VIVIENDA'),1,0,'C',0);
    $pdf->Cell(15,12,utf8_decode('NO.'),1,0,'C',0);
    $pdf->Cell(25,12,utf8_decode('ESTADO'),1,0,'C',0);
    $pdf->Cell(20,12,utf8_decode('PRECIO'),1,1,'C',0);
    foreach ($viviendas['datos'] as $row) {
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(35,10,utf8_decode($row['proyecto']),1,0,'C',0);
        $pdf->Cell(30,10,utf8_decode($row['sector']),1,0,'C',0);
        $pdf->Cell(70,10,utf8_decode($row['vivienda']),1,0,'C',0);
        $pdf->Cell(15,10,utf8_decode($row['numero']),1,0,'C',0);
        $pdf->Cell(25,10,utf8_decode($row['estado']),1,0,'C',0);        
        $pdf->Cell(20,10,utf8_decode($row['precio']),1,1,'C',0);        
    }
    $nombreDoc = "";

$pdf->Output('I',$nombreDoc,false);

?>