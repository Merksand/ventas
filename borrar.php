<?php

// require 'database.php';

include "core/autoload.php";

// Conectar a la base de datos$
$conn = new Database();

if ($conn->connect()) {
    echo "Conexión exitosa";
} else {
    echo "Error al conectar a la base de datos";
}


require 'phpWord2/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

try {
    // Consulta para obtener los productos
    $query = "SELECT * FROM tb_productos";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Crear un nuevo documento de Word
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Título del reporte
        $section->addTitle('Reporte de Productos', 1);
        $section->addText('Lista detallada de productos.', ['bold' => true]);
        $section->addTextBreak(1);

        // Añadir encabezados de columna en la tabla
        $table = $section->addTable();
        $table->addRow();
        $table->addCell(2000)->addText("ID", ['bold' => true]);
        $table->addCell(5000)->addText("Nombre", ['bold' => true]);
        $table->addCell(3000)->addText("Precio Compra", ['bold' => true]);
        $table->addCell(3000)->addText("Precio Venta", ['bold' => true]);
        $table->addCell(2000)->addText("Cantidad", ['bold' => true]);

        // Añadir filas de productos
        while ($row = $result->fetch_assoc()) {
            $table->addRow();
            $table->addCell(2000)->addText($row['id_producto']);
            $table->addCell(5000)->addText($row['nombre_producto']);
            $table->addCell(3000)->addText("$" . number_format($row['precio_compra'], 2));
            $table->addCell(3000)->addText("$" . number_format($row['precio_venta'], 2));
            $table->addCell(2000)->addText($row['stock']);
        }

        // Guardar el documento como .docx
        $fileName = "reporte_productos.docx";
        $phpWord->save($fileName, 'Word2007');
        
        echo "Reporte creado exitosamente: <a href='$fileName'>$fileName</a>";
    } else {
        echo "No hay productos disponibles para generar el reporte.";
    }
} catch (Exception $e) {
    echo "Error al obtener productos: " . $e->getMessage();
} finally {
    // Cerrar la conexión
    // $conn->close();
}
?>
