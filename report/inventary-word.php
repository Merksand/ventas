<?php
// Incluir archivos necesarios
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/OperationData.php";

require_once '../tcpdf/vendor/autoload.php';
use TCPDF;

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Sistema');
$pdf->SetTitle('Reporte de Inventario');
$pdf->SetSubject('Inventario');
$pdf->SetKeywords('Inventario, Reporte, PDF');

// Configuración de la página
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'INVENTARIO', 0, 1, 'C');
$pdf->Ln(5); // Espacio adicional

// Obtener todos los productos
$products = ProductData::getAll();

// Estilos de encabezado de la tabla
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(170, 170, 170); // Fondo gris claro para encabezados

// Crear encabezado de la tabla
$pdf->Cell(20, 8, 'Id', 1, 0, 'C', 1);
$pdf->Cell(100, 8, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Disponible', 1, 1, 'C', 1);

// Fuente para el contenido de la tabla
$pdf->SetFont('helvetica', '', 9);

// Agregar filas de datos para cada producto
foreach ($products as $product) {
    $disponible = OperationData::GetQYesF($product->id_producto);
    $pdf->Cell(20, 8, $product->id_producto, 1, 0, 'C');
    $pdf->Cell(100, 8, $product->nombre_producto, 1, 0, 'L');
    $pdf->Cell(30, 8, $disponible, 1, 1, 'C');
}

// Guardar el documento y enviarlo al navegador para su descarga
$filename = "inventario-" . time() . ".pdf";
$pdf->Output($filename, 'D');
?>
