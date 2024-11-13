<?php

include "../core/autoload.php";
include "../core/app/model/PersonData.php";

require_once '../tcpdf/vendor/autoload.php';


$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Sistema');
$pdf->SetTitle('Reporte de Proveedores');
$pdf->SetSubject('Lista de Proveedores');
$pdf->SetKeywords('Proveedores, Reporte, PDF');


$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();


$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'PROVEEDORES', 0, 1, 'C');
$pdf->Ln(5); 


$clients = PersonData::getProviders();


$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(170, 170, 170); 


$pdf->Cell(50, 8, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(60, 8, 'Direccion', 1, 0, 'C', 1);
$pdf->Cell(50, 8, 'Email', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Telefono', 1, 1, 'C', 1);


$pdf->SetFont('helvetica', '', 9);


foreach ($clients as $client) {
    $nombreCompleto = $client->name . " " . $client->lastname;
    $pdf->Cell(50, 8, $nombreCompleto, 1);
    $pdf->Cell(60, 8, $client->address1, 1);
    $pdf->Cell(50, 8, $client->email1, 1);
    $pdf->Cell(30, 8, $client->phone1, 1, 1);
}


$filename = "providers-" . time() . ".pdf";
$pdf->Output($filename, 'D');
?>
