<?php
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/CategoryData.php";

require_once '../tcpdf/vendor/autoload.php';

use TCPDF;


// Crear una nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Sistema');
$pdf->SetTitle('Reporte de Productos');
$pdf->SetSubject('Reporte de Productos');
$pdf->SetKeywords('Productos, Reporte, PDF');

// Configuración de la página
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 14);  // Tamaño de fuente del título reducido
$pdf->Cell(0, 10, 'Reporte de Productos', 0, 1, 'C');
$pdf->Ln(5);

// Configuración de la tabla: Encabezados
$pdf->SetFont('helvetica', 'B', 10);  // Reducido a 10 puntos para los encabezados
$pdf->SetFillColor(221, 221, 221);  // Fondo gris claro para encabezado
$pdf->Cell(49, 8, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(26, 8, 'Precio Entrada', 1, 0, 'C', 1);
$pdf->Cell(25, 8, 'Precio Salida', 1, 0, 'C', 1);
$pdf->Cell(17, 8, 'Stock', 1, 0, 'C', 1);
$pdf->Cell(49, 8, 'Categoría', 1, 0, 'C', 1);
$pdf->Cell(20, 8, 'Mín. Inv.', 1, 1, 'C', 1);

// Fuente para el contenido de la tabla
$pdf->SetFont('helvetica', '', 9);  // Reducido a 9 puntos para el contenido

// Obtener productos de la base de datos
$products = ProductData::getAll();

// Rellenar filas de productos
foreach ($products as $product) {
    $pdf->Cell(49, 8, $product->nombre_producto, 1, 0, 'L');
    $pdf->Cell(26, 8, "Bs " . number_format($product->precio_compra, 2), 1, 0, 'R');
    $pdf->Cell(25, 8, "Bs " . number_format($product->precio_venta, 2), 1, 0, 'R');
    $pdf->Cell(17, 8, $product->stock, 1, 0, 'C');

    // Verificar si el producto tiene una categoría
    $categoryName = $product->id_categoria ? $product->getCategory()->name : "---";
    $pdf->Cell(49, 8, $categoryName, 1, 0, 'L');

    $pdf->Cell(20, 8, $product->stock_minimo, 1, 1, 'C');
}

// Guardar el documento y enviarlo al navegador
$filename = "productos_" . time() . ".pdf";
$pdf->Output($filename, 'D');
?>