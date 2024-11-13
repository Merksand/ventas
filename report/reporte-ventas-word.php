<?php

include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/SellData.php";

require_once '../tcpdf/vendor/autoload.php';


$client_id = $_GET["client_id"] ?? "";
$sd = $_GET["sd"] ?? "";
$ed = $_GET["ed"] ?? "";


$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Sistema');
$pdf->SetTitle('Reporte de Ventas');
$pdf->SetSubject('Reporte de Ventas');
$pdf->SetKeywords('Ventas, Reporte, PDF');


$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();


$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Ventas', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Fecha: ' . date("d/m/Y"), 0, 1, 'C');
$pdf->Ln(5); 


$pdf->SetFont('helvetica', 'B', 12);
if ($client_id) {
    $client = SellData::getClient($client_id);
    $pdf->Cell(0, 10, "Cliente: " . $client->nombre . " " . $client->apellido_paterno . " " . $client->apellido_materno, 0, 1);
} else {
    $pdf->Cell(0, 10, "Cliente: Todos", 0, 1);
}


$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, "Rango de fechas: $sd a $ed", 0, 1);
$pdf->Ln(5); 


$operations = ($client_id == "") 
    ? SellData::getAllByDateOp($sd, $ed) 
    : SellData::getAllByDateBCOp($client_id, $sd, $ed);


if (count($operations) > 0) {
    
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetFillColor(221, 221, 221);
    $pdf->Cell(30, 8, 'ID Venta', 1, 0, 'C', 1);
    $pdf->Cell(50, 8, 'Total (Bs)', 1, 0, 'C', 1);
    $pdf->Cell(40, 8, 'Fecha', 1, 1, 'C', 1);

    
    $pdf->SetFont('helvetica', '', 10);
    $supertotal = 0;
    foreach ($operations as $operation) {
        $pdf->Cell(30, 8, $operation->id_venta, 1, 0, 'C');
        $pdf->Cell(50, 8, number_format($operation->total_venta, 2, '.', ','), 1, 0, 'R');
        $pdf->Cell(40, 8, date("d/m/Y", strtotime($operation->fecha_venta)), 1, 1, 'C');
        $supertotal += $operation->total_venta;
    }

    
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, "Total de ventas: Bs " . number_format($supertotal, 2, '.', ','), 0, 1, 'R');
} else {
    
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'I', 12);
    $pdf->Cell(0, 10, "No hay operaciones para el rango seleccionado.", 0, 1, 'C');
}


$filename = "reporte-ventas-" . time() . ".pdf";
$pdf->Output($filename, 'D');
?>
