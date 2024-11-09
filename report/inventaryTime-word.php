<?php
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/OperationData.php";

require_once '../phpWord2/vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;

$startDate = isset($_GET['sd']) ? $_GET['sd'] : '';
$endDate = isset($_GET['ed']) ? $_GET['ed'] : '';
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';

if ($product_id == "") {
    $operations = OperationData::getProductsByDateAndOperation($startDate, $endDate);
} else {
    $operations = OperationData::getAllByDateOfficialBP($product_id, $startDate, $endDate);
}

$word = new PhpWord();
$section = $word->addSection();

$section->addText("Reporte de Inventario", array("size" => 22, "bold" => true, "color" => "4F81BD"), array("alignment" => "center"));
$section->addText("Desde: $startDate Hasta: $endDate", array("size" => 12, "italic" => true, "color" => "7F7F7F"), array("alignment" => "center"));
$section->addTextBreak(2);

$styleTable = array('borderSize' => 6, 'borderColor' => '4F81BD', 'cellMargin' => 80);
$styleFirstRow = array('bgColor' => 'D9E1F2');
$word->addTableStyle('inventoryTable', $styleTable, $styleFirstRow);

$table = $section->addTable('inventoryTable');

$table->addRow();
$table->addCell(3000, array('bgColor' => '4F81BD'))->addText("Producto", array("bold" => true, "color" => "FFFFFF"), array("alignment" => "center"));
$table->addCell(2000, array('bgColor' => '4F81BD'))->addText("Cantidad", array("bold" => true, "color" => "FFFFFF"), array("alignment" => "center"));
$table->addCell(2000, array('bgColor' => '4F81BD'))->addText("Operación", array("bold" => true, "color" => "FFFFFF"), array("alignment" => "center"));
$table->addCell(3000, array('bgColor' => '4F81BD'))->addText("Fecha", array("bold" => true, "color" => "FFFFFF"), array("alignment" => "center"));

foreach ($operations as $operation) {
    $table->addRow();
    $table->addCell(3000)->addText($operation->nombre_producto, array("size" => 12), array("alignment" => "left"));
    $table->addCell(2000)->addText($operation->stock_actual, array("size" => 12), array("alignment" => "center"));
    $table->addCell(2000)->addText($operation->tipo_operacion == 'entrada' ? 'Compra' : 'Venta', array("size" => 12), array("alignment" => "center"));
    $table->addCell(3000)->addText($operation->fyh_creacion, array("size" => 12), array("alignment" => "center"));
}

$filename = "inventario_" . time() . ".docx";
$word->save($filename, "Word2007");
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);
unlink($filename);
?>
