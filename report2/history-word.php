<?php
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/CategoryData.php";
include "../core/app/model/OperationData.php";

require_once '../PhpWord2/vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;

// Crear una instancia de PhpWord
$word = new PhpWord();

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

// Crear la sección del documento
$section1 = $word->addSection();
$section1->addText($product->nombre_producto, array("size" => 22, "bold" => true));
$section1->addText("Historial del Producto", array("size" => 14, "bold" => true));

// Estilos de tabla
$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

// Tabla resumen de cantidades
$table0 = $section1->addTable("table0");
$table0->addRow();
$table0->addCell()->addText("Entradas");
$table0->addCell()->addText("Disponibles");
$table0->addCell()->addText("Salidas");
$table0->addRow();
$table0->addCell(4000)->addText($entradas);
$table0->addCell(4000)->addText($disponibles);
$table0->addCell(4000)->addText($salidas);

$word->addTableStyle('table0', $styleTable, $styleFirstRow);
$section1->addText("");

// Tabla de historial de operaciones
$operations = OperationData::getAllInventaryByProductId($product->id_producto);
$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("Cantidad");
$table1->addCell()->addText("Tipo");
$table1->addCell()->addText("Fecha");

foreach ($operations as $operation) {
    $table1->addRow();
    $table1->addCell(4000)->addText($operation->stock_actual);
    $table1->addCell(4000)->addText($operation->tipo_operacion);
    $table1->addCell(4000)->addText($operation->fyh_creacion);
}

$word->addTableStyle('table1', $styleTable, $styleFirstRow);

// Guardar y descargar el archivo
$filename = "history-" . time() . ".docx";
$word->save($filename, "Word2007");
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);
unlink($filename);
?>
