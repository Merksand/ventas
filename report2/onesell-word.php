<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/OperationTypeData.php";
include "../core/app/model/ProductData.php";

require_once '../phpWord2/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;

// Crear una instancia de PhpWord
$word = new PhpWord();

$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);

// Obtener cliente y usuario
$client = $sell->id_cliente != null ? $sell->getClient($sell->id_cliente) : null;
$user = $sell->getBuyUser($sell->id_usuario);

// Agregar sección y título al documento de Word
$section1 = $word->addSection();
$section1->addText("RESUMEN DE VENTA", array("size" => 22, "bold" => true, "align" => "right"));

// Estilos de tabla
$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

// Crear la primera tabla para mostrar "Atendido por" y "Cliente"
$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell(3000)->addText("Atendido por");
$table1->addCell(9000)->addText($user ? $user->nombre . " " . $user->apellido_paterno . " " . $user->apellido_materno : "Usuario no encontrado");

$table1->addRow();
$table1->addCell()->addText("Cliente");
$table1->addCell()->addText($client ? $client->nombre . " " . $client->apellido_paterno . " " . $client->apellido_materno : "Cliente no registrado");

$section1->addText("");

// Crear segunda tabla para mostrar el detalle de productos
$table2 = $section1->addTable("table2");
$table2->addRow();
$table2->addCell(1000)->addText("Código");
$table2->addCell(1000)->addText("Cantidad");
$table2->addCell(6000)->addText("Nombre del producto");
$table2->addCell(1000)->addText("Precio Unidad");
$table2->addCell(2000)->addText("Total");

$total = 0;
foreach ($operations as $operation) {
    $product = $operation->getProduct($operation->id_producto);
    if ($product) {
        $table2->addRow();
        $table2->addCell()->addText($product->id_producto);
        $table2->addCell()->addText($operation->cantidad);
        $table2->addCell()->addText($product->nombre_producto);
        $table2->addCell()->addText("Bs" . number_format($product->precio_venta, 2, ".", ","));
        $table2->addCell()->addText("Bs" . number_format($operation->cantidad * $product->precio_venta, 2, ".", ","));
        $total += $operation->cantidad * $product->precio_venta;
    } else {
        $table2->addRow();
        $table2->addCell()->addText("Producto no encontrado");
    }
}

$section1->addText("");
$section1->addText("Total: Bs" . number_format($total, 2, ".", ","), array("size" => 20));

// Aplicar estilos de tabla
$word->addTableStyle('table1', $styleTable);
$word->addTableStyle('table2', $styleTable, $styleFirstRow);

// Guardar el archivo
$filename = "Venta-" . time() . ".docx";
$word->save($filename, "Word2007");

// Descargar el archivo y eliminar el temporal
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);
unlink($filename);

?>
