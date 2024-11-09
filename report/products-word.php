<?php
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/CategoryData.php";

require_once '../phpWord2/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;

$word = new PhpWord();

$products = ProductData::getAll();

$section1 = $word->addSection();
$section1->addText("PRODUCTOS", array("size" => 22, "bold" => true, "align" => "right"));

$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$word->addTableStyle('table1', $styleTable, $styleFirstRow);

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("Id");
$table1->addCell()->addText("Nombre");
$table1->addCell()->addText("Precio Entrada");
$table1->addCell()->addText("Precio Salida");
$table1->addCell()->addText("Unidad");
$table1->addCell()->addText("Categoría");
$table1->addCell()->addText("Mínima en Inv.");
$table1->addCell()->addText("Activo");

foreach ($products as $product) {
	$table1->addRow();
	$table1->addCell(500)->addText($product->id_producto);
	$table1->addCell(5000)->addText($product->nombre_producto);
	$table1->addCell(2000)->addText($product->precio_compra);
	$table1->addCell(2000)->addText($product->precio_venta);
	$table1->addCell(2000)->addText($product->stock);

	if ($product->id_categoria != null) {
		$table1->addCell(2000)->addText($product->getCategory()->name);
	} else {
		$table1->addCell(2000)->addText("---");
	}

	$table1->addCell(2000)->addText($product->stock_minimo);
	$table1->addCell(100)->addText($product->is_active ? "Si" : "No");
}

$filename = "productos-" . time() . ".docx";
$word->save($filename, "Word2007");

header("Content-Disposition: attachment; filename=\"{$filename}\"");

readfile($filename);
unlink($filename);
?>
