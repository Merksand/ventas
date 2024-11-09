<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<pre>";
    foreach ($_POST as $key => $value) {
        echo "$key: $value\n";
    }
    echo "</pre>";
}

// Imprime los valores de `$_GET` para confirmar que llegan correctamente
echo "<pre>";
print_r($_GET);
echo "</pre>";

// Obtiene la operación por ID
$operationId = $_GET["opid"];
$operation = OperationData::getById($operationId);

// Verifica si la operación existe y obtiene el producto asociado
if ($operation) {
    $product = $operation->getProduct($operation->id_producto);

    if ($product) {
        // Calcula el stock nuevo sin modificar el stock actual en el objeto producto
        if ($operation->tipo_operacion === 'entrada') {
            $new_stock = $product->stock - $operation->stock_actual;  // Restamos el stock de la entrada
        } elseif ($operation->tipo_operacion === 'salida') {
            $new_stock = $product->stock + $operation->stock_actual;  // Sumamos el stock de la salida
        }

        // Asegura que el stock no sea negativo
        $new_stock = max(0, $new_stock);

        // Muestra los valores para verificar
        echo "Stock actual en BD: {$product->stock}<br>";
        echo "Stock de la operación: {$operation->stock_actual}<br>";
        echo "Nuevo stock calculado: {$new_stock}<br>";

        // Actualiza el stock en la base de datos con el valor calculado
        ProductData::updateStockInventary($product->id_producto, $new_stock);

        // Elimina la operación de la base de datos
        OperationData::del($operationId);

        echo "Operación eliminada y stock actualizado correctamente.";
    } else {
        echo "Error: No se encontró el producto asociado a la operación.";
    }
} else {
    echo "Error: No se encontró la operación.";
}

// Redirección a la página de historial del producto después de eliminar
header("Location: index.php?view=" . $_GET['ref'] . "&product_id=" . $_GET['pid']);
exit();
