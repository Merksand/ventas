<?php
$debug = true;
if ($debug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

include "../core/autoload.php";
include "../core/app/model/PersonData.php";

require_once '../phpWord2/vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;

$word = new PhpWord();
$clients = PersonData::getClients();

$section1 = $word->addSection();
$section1->addText("Lista de Clientes", array("size" => 22, "bold" => true, "align" => "center"));
$section1->addText("Fecha: " . date("d/m/Y"), array("size" => 12), "center");
$section1->addTextBreak(1);

$styleTable = array('borderSize' => 6, 'borderColor' => '666666', 'cellMargin' => 80);
$styleFirstRow = array('bgColor' => 'CCCCCC');
$word->addTableStyle('clientTable', $styleTable, $styleFirstRow);

$table1 = $section1->addTable('clientTable');
$table1->addRow();
$table1->addCell(5000)->addText("Nombre Completo", array("bold" => true));
$table1->addCell(3000)->addText("Dirección", array("bold" => true));
$table1->addCell(3000)->addText("Email", array("bold" => true));
$table1->addCell(2000)->addText("Teléfono", array("bold" => true));
$table1->addCell(2000)->addText("C.I.", array("bold" => true));

foreach ($clients as $client) {
    $table1->addRow();
    $table1->addCell(5000)->addText($client->name . " " . $client->lastname. " ". $client->lastname2);
    $table1->addCell(3000)->addText($client->address);
    $table1->addCell(3000)->addText($client->email);
    $table1->addCell(2000)->addText($client->phone);
    $table1->addCell(2000)->addText($client->CI);
}

$filename = "clientes-" . time() . ".docx";
$word->save($filename, "Word2007");
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);
unlink($filename);
?>
