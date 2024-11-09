<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);

foreach ($operations as $operation) {
    $product = $operation->getProduct($operation->id_producto);

    if ($product) {
        // Calcular el nuevo stock sumando la cantidad específica de esta operación
        $new_stock = $product->stock + $operation->cantidad;

        // Asegurarse de aplicar el nuevo stock solo para el producto de esta operación
        ProductData::updateStockRevert($product->id_producto, $new_stock);
    }

    // Eliminar el detalle de la venta después de ajustar el stock
    OperationData::delById($operation->id_detalle_venta);
}

// Eliminar la venta principal
SellData::delById($_GET["id"]);

Core::redir("./index.php?view=sells");
?>
