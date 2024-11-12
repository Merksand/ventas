<?php
// Incluir archivos necesarios
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/CategoryData.php";
include "../core/app/model/OperationData.php";

require_once '../tcpdf/vendor/autoload.php';
use TCPDF;

// Obtener el producto usando el ID proporcionado
$product = ProductData::getById($_GET["id"]);
if (!$product) {
    echo "Producto no encontrado con ID: " . $_GET["id"];
    exit;
}

// Obtener las cantidades de entradas, salidas y el stock actual
$entradas = OperationData::GetInputQProduct($product->id_producto);
$salidas = OperationData::GetOutputQProduct($product->id_producto);
$disponibles = ProductData::getAllProductById($product->id_producto)->stock;

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Sistema');
$pdf->SetTitle('Historial del Producto');
$pdf->SetSubject('Historial de Producto');
$pdf->SetKeywords('Producto, Historial, Reporte, PDF');

// Configuración de la página
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, $product->nombre_producto, 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Historial del Producto', 0, 1, 'C');
$pdf->Ln(5); // Espacio adicional

// Tabla resumen de cantidades
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(170, 170, 170); // Fondo gris claro para encabezados
$pdf->Cell(60, 8, 'Entradas', 1, 0, 'C', 1);
$pdf->Cell(60, 8, 'Disponibles', 1, 0, 'C', 1);
$pdf->Cell(60, 8, 'Salidas', 1, 1, 'C', 1);

// Valores de la tabla resumen
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(60, 8, $entradas, 1, 0, 'C');
$pdf->Cell(60, 8, $disponibles, 1, 0, 'C');
$pdf->Cell(60, 8, $salidas, 1, 1, 'C');
$pdf->Ln(10); // Espacio adicional

// Tabla de historial de operaciones
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 8, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(60, 8, 'Tipo', 1, 0, 'C', 1);
$pdf->Cell(60, 8, 'Fecha', 1, 1, 'C', 1);

// Fuente para el contenido de la tabla de historial
$pdf->SetFont('helvetica', '', 10);

// Obtener el historial de operaciones para el producto
$operations = OperationData::getAllInventaryByProductId($product->id_producto);
foreach ($operations as $operation) {
    $pdf->Cell(60, 8, $operation->stock_actual, 1, 0, 'C');
    $pdf->Cell(60, 8, $operation->tipo_operacion, 1, 0, 'C');
    $pdf->Cell(60, 8, date("d/m/Y", strtotime($operation->fyh_creacion)), 1, 1, 'C');
}

// Guardar el documento y enviarlo al navegador para su descarga
$filename = "history-" . time() . ".pdf";
$pdf->Output($filename, 'D');
?>
