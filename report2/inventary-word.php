<?php
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/OperationData.php";

require_once '../PhpWord2/vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;

// Crear una instancia de PhpWord
$word = new PhpWord();

// Obtener todos los productos
$products = ProductData::getAll();

// Crear la sección del documento
$section1 = $word->addSection();
$section1->addText("INVENTARIO", array("size" => 22, "bold" => true));

// Estilos de tabla
$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

// Crear la tabla para el inventario
$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("Id");
$table1->addCell()->addText("Nombre");
$table1->addCell()->addText("Disponible");

foreach ($products as $product) {
    $disponible = OperationData::GetQYesF($product->id_producto);
    $table1->addRow();
    $table1->addCell(300)->addText($product->id_producto);
    $table1->addCell(11000)->addText($product->nombre_producto);
    $table1->addCell(500)->addText($disponible);
}

$word->addTableStyle('table1', $styleTable, $styleFirstRow);

// Guardar y descargar el archivo
$filename = "inventario-" . time() . ".docx";
$word->save($filename, "Word2007");
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);
unlink($filename);  // Eliminar el archivo temporal después de la descarga
?>
