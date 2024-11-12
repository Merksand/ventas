<?php
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/CategoryData.php";

require_once '../phpWord2/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;

$word = new PhpWord();

$products = ProductData::getAll();

$section1 = $word->addSection();
$section1->addText("Reporte de Productos", array("size" => 24, "bold" => true), array("alignment" => "center"));
$section1->addTextBreak(1);

$styleTable = array('borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80);
$styleFirstRow = array('bgColor' => 'DDDDDD');

$word->addTableStyle('ProductTable', $styleTable, $styleFirstRow);

$table1 = $section1->addTable('ProductTable');
$table1->addRow();
$table1->addCell(800)->addText("ID", array("bold" => true));
$table1->addCell(3000)->addText("Nombre", array("bold" => true));
$table1->addCell(1500)->addText("Precio Entrada", array("bold" => true));
$table1->addCell(1500)->addText("Precio Salida", array("bold" => true));
$table1->addCell(1200)->addText("Stock", array("bold" => true));
$table1->addCell(2000)->addText("Categoría", array("bold" => true));
$table1->addCell(1500)->addText("Mín. Inv.", array("bold" => true));

foreach ($products as $product) {
    $table1->addRow();
    $table1->addCell(800)->addText($product->id_producto);
    $table1->addCell(3000)->addText($product->nombre_producto);
    $table1->addCell(1500)->addText(number_format($product->precio_compra, 2));
    $table1->addCell(1500)->addText(number_format($product->precio_venta, 2));
    $table1->addCell(1200)->addText($product->stock);

    $categoryName = $product->id_categoria ? $product->getCategory()->name : "---";
    $table1->addCell(2000)->addText($categoryName);

    $table1->addCell(1500)->addText($product->stock_minimo);
}

$filename = "productos-" . time() . ".docx";
$word->save($filename, "Word2007");

header("Content-Disposition: attachment; filename=\"{$filename}\"");
readfile($filename);
unlink($filename);