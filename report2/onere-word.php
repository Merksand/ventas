<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/ProductData.php";

require_once '../phpWord2/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;

// Crear una instancia de PhpWord
$word = new PhpWord();

// Crear una instancia de PhpWord
$word = new PhpOffice\PhpWord\PhpWord();

// Obtener el reabastecimiento (compra) y sus detalles
$sell = SellData::getByIdReabastecimiento($_GET["id"]);
$operations = OperationData::getAllProductsByBuyId($_GET["id"]);

// Obtener datos del proveedor y usuario directamente desde `$sell`
$client = $sell->getPersonProviderById($sell->person_id); // Obtener proveedor si `person_id` está presente
$user = $sell->getBuyUser($sell->user_id);    // Obtener usuario que realizó la compra


$section1 = $word->AddSection();
$section1->addText("RESUMEN DE REABASTECIMIENTO", array("size" => 22, "bold" => true, "align" => "right"));

// Estilos de la tabla
$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');
$word->addTableStyle('table1', $styleTable);
$word->addTableStyle('table2', $styleTable, $styleFirstRow);

// Tabla para información de proveedor y usuario
$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell(3000)->addText("Atendido por");
$table1->addCell(9000)->addText($user->nombre . " " . $user->apellido_paterno . " " . $user->apellido_materno);

if ($client) {
    $table1->addRow();
    $table1->addCell()->addText("Proveedor");
    $table1->addCell()->addText($client->nombre . " " . $client->apellido_paterno . " " . $client->apellido_materno);
}

$section1->addText("");

// Tabla de productos
$table2 = $section1->addTable("table2");
$table2->addRow();
$table2->addCell(1000)->addText("Codigo");
$table2->addCell(1000)->addText("Cantidad");
$table2->addCell(6000)->addText("Nombre del producto");
$table2->addCell(1000)->addText("P.U");
$table2->addCell(2000)->addText("Total");

$total = 0;

foreach ($operations as $operation) {
    // Obtener el producto asociado a la operación
    $product = $operation->getProduct($operation->id_producto);

    // Solo usamos los campos necesarios, ignorando los adicionales
    $table2->addRow();
    $table2->addCell()->addText($product->id_producto ?? '');
    $table2->addCell()->addText($operation->cantidad ?? '');
    $table2->addCell()->addText($product->nombre_producto ?? '');
    $table2->addCell()->addText("Bs " . number_format($product->precio_compra, 2, ".", ","));
    $table2->addCell()->addText("Bs " . number_format($operation->cantidad * $product->precio_compra, 2, ".", ","));
    $total += $operation->cantidad * $product->precio_compra;
}

// Total de la compra
$section1->addText("");
$section1->addText("Total: Bs " . number_format($total, 2, ".", ","), array("size" => 20));

// Guardar el documento
$filename = "reabastecimiento-" . time() . ".docx";
$word->save($filename, "Word2007");

// Descargar y eliminar el archivo temporal
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);
unlink($filename);



?>
