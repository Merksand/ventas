<?php
// Habilitar modo de depuración si está activo
$debug = true;
if ($debug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Incluir archivos necesarios
include "../core/autoload.php";
include "../core/app/model/PersonData.php";

// Cargar la biblioteca TCPDF
require_once '../tcpdf/vendor/autoload.php';
use TCPDF;

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Sistema');
$pdf->SetTitle('Lista de Clientes');
$pdf->SetSubject('Reporte de Clientes');
$pdf->SetKeywords('Clientes, Reporte, PDF');

// Configuración de la página
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Lista de Clientes', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Fecha: ' . date("d/m/Y"), 0, 1, 'C');
$pdf->Ln(5); // Espacio adicional

// Configuración de la tabla: Encabezados
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(221, 221, 221); // Fondo gris claro para encabezados
$pdf->Cell(50, 8, 'Nombre Completo', 1, 0, 'C', 1);
$pdf->Cell(50, 8, 'Dirección', 1, 0, 'C', 1);
$pdf->Cell(50, 8, 'Email', 1, 0, 'C', 1);
$pdf->Cell(21, 8, 'Teléfono', 1, 0, 'C', 1);
$pdf->Cell(20, 8, 'C.I.', 1, 1, 'C', 1);

// Fuente para el contenido de la tabla
$pdf->SetFont('helvetica', '', 9); // Reducido para que todo el contenido quepa

// Obtener clientes de la base de datos
$clients = PersonData::getClients();

// Rellenar filas de clientes
foreach ($clients as $client) {
    $nombreCompleto = $client->name . " " . $client->lastname . " " . $client->lastname2;
    $pdf->Cell(50, 8, $nombreCompleto, 1, 0, 'L');
    $pdf->Cell(50, 8, $client->address, 1, 0, 'L');
    $pdf->Cell(50, 8, $client->email, 1, 0, 'L');
    $pdf->Cell(21, 8, $client->phone, 1, 0, 'L');
    $pdf->Cell(20, 8, $client->CI, 1, 1, 'L');
}

// Guardar el documento y enviarlo al navegador para su descarga
$filename = "clientes_" . time() . ".pdf";
$pdf->Output($filename, 'D');
?>
