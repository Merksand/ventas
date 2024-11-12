<?php
// Incluir archivos necesarios
include "../core/autoload.php";
include "../core/app/model/PersonData.php";

require_once '../tcpdf/vendor/autoload.php';
use TCPDF;

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Sistema');
$pdf->SetTitle('Reporte de Proveedores');
$pdf->SetSubject('Lista de Proveedores');
$pdf->SetKeywords('Proveedores, Reporte, PDF');

// Configuración de la página
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'PROVEEDORES', 0, 1, 'C');
$pdf->Ln(5); // Espacio adicional

// Obtener datos de proveedores
$clients = PersonData::getProviders();

// Estilos de encabezado de la tabla
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(170, 170, 170); // Fondo gris claro para encabezados

// Crear encabezado de la tabla
$pdf->Cell(50, 8, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(60, 8, 'Direccion', 1, 0, 'C', 1);
$pdf->Cell(50, 8, 'Email', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Telefono', 1, 1, 'C', 1);

// Fuente para el contenido de la tabla
$pdf->SetFont('helvetica', '', 9);

// Agregar filas de datos para cada proveedor
foreach ($clients as $client) {
    $nombreCompleto = $client->name . " " . $client->lastname;
    $pdf->Cell(50, 8, $nombreCompleto, 1);
    $pdf->Cell(60, 8, $client->address1, 1);
    $pdf->Cell(50, 8, $client->email1, 1);
    $pdf->Cell(30, 8, $client->phone1, 1, 1);
}

// Guardar el documento y enviarlo al navegador para su descarga
$filename = "providers-" . time() . ".pdf";
$pdf->Output($filename, 'D');
?>
