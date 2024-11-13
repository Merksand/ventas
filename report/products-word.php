<?php
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/CategoryData.php";

require_once '../tcpdf/vendor/autoload.php';




$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Sistema');
$pdf->SetTitle('Reporte de Productos');
$pdf->SetSubject('Reporte de Productos');
$pdf->SetKeywords('Productos, Reporte, PDF');


$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();


$pdf->SetFont('helvetica', 'B', 14);  
$pdf->Cell(0, 10, 'Reporte de Productos', 0, 1, 'C');
$pdf->Ln(5);


$pdf->SetFont('helvetica', 'B', 10);  
$pdf->SetFillColor(221, 221, 221);  
$pdf->Cell(49, 8, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(26, 8, 'Precio Entrada', 1, 0, 'C', 1);
$pdf->Cell(25, 8, 'Precio Salida', 1, 0, 'C', 1);
$pdf->Cell(17, 8, 'Stock', 1, 0, 'C', 1);
$pdf->Cell(49, 8, 'Categoría', 1, 0, 'C', 1);
$pdf->Cell(20, 8, 'Mín. Inv.', 1, 1, 'C', 1);


$pdf->SetFont('helvetica', '', 9);  


$products = ProductData::getAll();


foreach ($products as $product) {
    $pdf->Cell(49, 8, $product->nombre_producto, 1, 0, 'L');
    $pdf->Cell(26, 8, "Bs " . number_format($product->precio_compra, 2), 1, 0, 'R');
    $pdf->Cell(25, 8, "Bs " . number_format($product->precio_venta, 2), 1, 0, 'R');
    $pdf->Cell(17, 8, $product->stock, 1, 0, 'C');

    
    $categoryName = $product->id_categoria ? $product->getCategory()->name : "---";
    $pdf->Cell(49, 8, $categoryName, 1, 0, 'L');

    $pdf->Cell(20, 8, $product->stock_minimo, 1, 1, 'C');
}


$filename = "productos_" . time() . ".pdf";
$pdf->Output($filename, 'D');
?>