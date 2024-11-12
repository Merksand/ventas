<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/ProductData.php";

require_once '../tcpdf/vendor/autoload.php';
use TCPDF;

// Crear una instancia de TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema');
$pdf->SetTitle('Resumen de Reabastecimiento');
$pdf->SetSubject('Reabastecimiento');
$pdf->SetKeywords('Reabastecimiento, PDF');

// Configuración de la página
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'RESUMEN DE REABASTECIMIENTO', 0, 1, 'C');
$pdf->Ln(5); // Espacio adicional

// Obtener el reabastecimiento (compra) y sus detalles
$sell = SellData::getByIdReabastecimiento($_GET["id"]);
$operations = OperationData::getAllProductsByBuyId($_GET["id"]);

// Obtener datos del proveedor y usuario
$client = $sell->getPersonProviderById($sell->person_id); // Obtener proveedor si `person_id` está presente
$user = $sell->getBuyUser($sell->user_id); // Obtener usuario que realizó la compra

// Información del usuario que atendió
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(40, 8, 'Atendido por:', 0, 0);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(60, 8, $user->nombre . " " . $user->apellido_paterno . " " . $user->apellido_materno, 0, 1);

if ($client) {
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(40, 8, 'Proveedor:', 0, 0);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(60, 8, $client->nombre . " " . $client->apellido_paterno . " " . $client->apellido_materno, 0, 1);
}

$pdf->Ln(10); // Espacio adicional

// Encabezado de la tabla de productos
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(170, 170, 170);
$pdf->Cell(30, 8, 'Codigo', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(60, 8, 'Nombre del producto', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'P.U', 1, 0, 'C', 1);
$pdf->Cell(40, 8, 'Total', 1, 1, 'C', 1);

$pdf->SetFont('helvetica', '', 10);
$total = 0;

foreach ($operations as $operation) {
    // Obtener el producto asociado a la operación
    $product = $operation->getProduct($operation->id_producto);

    // Llenado de la tabla
    $pdf->Cell(30, 8, $product->id_producto, 1, 0, 'C');
    $pdf->Cell(30, 8, $operation->cantidad, 1, 0, 'C');
    $pdf->Cell(60, 8, $product->nombre_producto, 1, 0, 'L');
    $pdf->Cell(30, 8, "Bs " . number_format($product->precio_compra, 2, ".", ","), 1, 0, 'R');
    $pdf->Cell(40, 8, "Bs " . number_format($operation->cantidad * $product->precio_compra, 2, ".", ","), 1, 1, 'R');
    $total += $operation->cantidad * $product->precio_compra;
}

// Total de la compra
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(150, 8, 'Total:', 1, 0, 'R');
$pdf->Cell(40, 8, "Bs " . number_format($total, 2, ".", ","), 1, 1, 'R');

// Guardar el documento y enviarlo al navegador para su descarga
$filename = "reabastecimiento-" . time() . ".pdf";
$pdf->Output($filename, 'D');
?>
