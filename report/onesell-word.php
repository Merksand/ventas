<?php
// Incluir archivos necesarios
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/OperationTypeData.php";
include "../core/app/model/ProductData.php";

require_once '../tcpdf/vendor/autoload.php';
use TCPDF;

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Sistema');
$pdf->SetTitle('Resumen de Venta');
$pdf->SetSubject('Venta');
$pdf->SetKeywords('Resumen de Venta, PDF');

// Configuración de la página
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'RESUMEN DE VENTA', 0, 1, 'C');
$pdf->Ln(5); // Espacio adicional

// Obtener la venta, cliente, usuario y operaciones
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$client = $sell->id_cliente != null ? $sell->getClient($sell->id_cliente) : null;
$user = $sell->getBuyUser($sell->id_usuario);

// Primera tabla para mostrar "Atendido por" y "Cliente"
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(170, 170, 170);
$pdf->Cell(40, 8, 'Atendido por', 1, 0, 'C', 1);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(140, 8, $user ? $user->nombre . " " . $user->apellido_paterno . " " . $user->apellido_materno : "Usuario no encontrado", 1, 1);

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(40, 8, 'Cliente', 1, 0, 'C', 1);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(140, 8, $client ? $client->nombre . " " . $client->apellido_paterno . " " . $client->apellido_materno : "Cliente no registrado", 1, 1);
$pdf->Ln(10); // Espacio adicional

// Segunda tabla para mostrar el detalle de productos
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(170, 170, 170);
$pdf->Cell(30, 8, 'Código', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(60, 8, 'Nombre del producto', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Precio Unidad', 1, 0, 'C', 1);
$pdf->Cell(40, 8, 'Total', 1, 1, 'C', 1);

// Fuente para el contenido de la tabla de productos
$pdf->SetFont('helvetica', '', 10);
$total = 0;

foreach ($operations as $operation) {
    $product = $operation->getProduct($operation->id_producto);
    if ($product) {
        $pdf->Cell(30, 8, $product->id_producto, 1, 0, 'C');
        $pdf->Cell(30, 8, $operation->cantidad, 1, 0, 'C');
        $pdf->Cell(60, 8, $product->nombre_producto, 1, 0, 'L');
        $pdf->Cell(30, 8, "Bs" . number_format($product->precio_venta, 2, ".", ","), 1, 0, 'R');
        $pdf->Cell(40, 8, "Bs" . number_format($operation->cantidad * $product->precio_venta, 2, ".", ","), 1, 1, 'R');
        $total += $operation->cantidad * $product->precio_venta;
    } else {
        $pdf->Cell(0, 8, 'Producto no encontrado', 1, 1, 'C');
    }
}

// Total de la venta
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(150, 8, 'Total:', 1, 0, 'R');
$pdf->Cell(40, 8, "Bs" . number_format($total, 2, ".", ","), 1, 1, 'R');

// Guardar el documento y enviarlo al navegador para su descarga
$filename = "Venta-" . time() . ".pdf";
$pdf->Output($filename, 'D');
?>
