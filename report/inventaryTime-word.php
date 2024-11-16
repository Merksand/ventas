<?php
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/OperationData.php";

require_once '../tcpdf/vendor/autoload.php';

// Obtener parámetros de fecha y producto

$startDate = isset($_GET['sd']) ? $_GET['sd'] : '';
$endDate = isset($_GET['ed']) ? $_GET['ed'] : '';
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';

if ($product_id == "") {
    $operations = OperationData::getProductsByDateAndOperation($startDate, $endDate);
} else {
    $operations = OperationData::getAllByDateOfficialBP($product_id, $startDate, $endDate);
}

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema');
$pdf->SetTitle('Reporte de Inventario');
$pdf->SetSubject('Inventario');
$pdf->SetKeywords('Inventario, PDF');

// Configuración de la página
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(79, 129, 189);
$pdf->Cell(0, 10, 'Reporte de Inventario', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->SetTextColor(127, 127, 127);
$pdf->Cell(0, 10, "Desde: $startDate Hasta: $endDate", 0, 1, 'C');
$pdf->Ln(5); // Espacio adicional

// Configuración de la tabla
$pdf->SetFillColor(79, 129, 189);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 10);

// Encabezado de la tabla
$pdf->Cell(60, 8, 'Producto', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(40, 8, 'Operación', 1, 0, 'C', 1);
$pdf->Cell(50, 8, 'Fecha', 1, 1, 'C', 1);

// Contenido de la tabla
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);

foreach ($operations as $operation) {
    $pdf->Cell(60, 8, $operation->nombre_producto, 1, 0, 'L');
    $pdf->Cell(30, 8, $operation->stock_actual, 1, 0, 'C');
    $pdf->Cell(40, 8, $operation->tipo_operacion == 'entrada' ? 'Compra' : 'Venta', 1, 0, 'C');
    $pdf->Cell(50, 8, $operation->fyh_creacion, 1, 1, 'C');
}

// Guardar el documento y enviarlo al navegador para su descarga
$filename = "inventario_" . time() . ".pdf";
$pdf->Output($filename, 'D');
?>
