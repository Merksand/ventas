<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/SellData.php";
require_once '../phpWord2/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;

$client_id = $_GET["client_id"] ?? "";
$sd = $_GET["sd"] ?? "";
$ed = $_GET["ed"] ?? "";

$word = new PhpWord();
$section = $word->addSection();

// Título del reporte
$section->addText("Reporte de Ventas", array("size" => 22, "bold" => true, "align" => "center"));
$section->addText("Fecha: " . date("d/m/Y"), array("size" => 12), "center");

if ($client_id) {
    $client = SellData::getClient($client_id);
    $section->addText("Cliente: " . $client->nombre . " " . $client->apellido_paterno . " " . $client->apellido_materno, array("size" => 14, "bold" => true));
} else {
    $section->addText("Cliente: Todos", array("size" => 14, "bold" => true));
}
$section->addText("Rango de fechas: $sd a $ed", array("size" => 12));
$section->addTextBreak(2); // Espacio adicional

// Obtener datos según el cliente y las fechas proporcionadas
if ($client_id == "") {
    $operations = SellData::getAllByDateOp($sd, $ed);
} else {
    $operations = SellData::getAllByDateBCOp($client_id, $sd, $ed);
}

if (count($operations) > 0) {
    // Estilos para la tabla
    $tableStyle = array('borderSize' => 6, 'borderColor' => '666666', 'cellMargin' => 80);
    $firstRowStyle = array('bgColor' => 'CCCCCC');
    $word->addTableStyle('table', $tableStyle, $firstRowStyle);

    $table = $section->addTable('table');
    $table->addRow();
    $table->addCell(1500)->addText("ID Venta", array("bold" => true));
    $table->addCell(3000)->addText("Total (Bs)", array("bold" => true));
    $table->addCell(2000)->addText("Fecha", array("bold" => true));

    $supertotal = 0;
    foreach ($operations as $operation) {
        $table->addRow();
        $table->addCell(1500)->addText($operation->id_venta);
        $table->addCell(3000)->addText(number_format($operation->total_venta, 2, '.', ','));
        $table->addCell(2000)->addText(date("d/m/Y", strtotime($operation->fecha_venta)));
        $supertotal += $operation->total_venta;
    }

    // Total final
    $section->addTextBreak(1);
    $section->addText("Total de ventas: Bs " . number_format($supertotal, 2, '.', ','), array("size" => 16, "bold" => true));
} else {
    $section->addText("No hay operaciones para el rango seleccionado.", array("size" => 12, "italic" => true));
}

// Guardar y descargar el archivo
$filename = "reporte-ventas-" . time() . ".docx";
$word->save($filename, "Word2007");
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);
unlink($filename);
?>
