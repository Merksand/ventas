<?php
$debug = true;
if ($debug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

include "../core/autoload.php";
include "../core/app/model/PersonData.php";

require_once '../tcpdf/vendor/autoload.php';


$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Sistema');
$pdf->SetTitle('Lista de Clientes');
$pdf->SetSubject('Reporte de Clientes');
$pdf->SetKeywords('Clientes, Reporte, PDF');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Lista de Clientes', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Fecha: ' . date("d/m/Y"), 0, 1, 'C');
$pdf->Ln(5); 

$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(221, 221, 221); 
$pdf->Cell(50, 8, 'Nombre Completo', 1, 0, 'C', 1);
$pdf->Cell(50, 8, 'Dirección', 1, 0, 'C', 1);
$pdf->Cell(50, 8, 'Email', 1, 0, 'C', 1);
$pdf->Cell(21, 8, 'Teléfono', 1, 0, 'C', 1);
$pdf->Cell(20, 8, 'C.I.', 1, 1, 'C', 1);


$pdf->SetFont('helvetica', '', 9); 


$clients = PersonData::getClients();


foreach ($clients as $client) {
    $nombreCompleto = $client->name . " " . $client->lastname . " " . $client->lastname2;
    $pdf->Cell(50, 8, $nombreCompleto, 1, 0, 'L');
    $pdf->Cell(50, 8, $client->address, 1, 0, 'L');
    $pdf->Cell(50, 8, $client->email, 1, 0, 'L');
    $pdf->Cell(21, 8, $client->phone, 1, 0, 'L');
    $pdf->Cell(20, 8, $client->CI, 1, 1, 'L');
}


$filename = "clientes_" . time() . ".pdf";
$pdf->Output($filename, 'D');
?>
