<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
echo "<pre>";
print_r($sell);

echo "-----------------";
echo "</pre>";
echo "<pre>";
print_r($operations);
echo "</pre>";

foreach ($operations as $operation) {
    $product = $operation->getProduct($operation->id_producto);

    if ($product) {
        // Calcular el nuevo stock sumando la cantidad específica de esta operación
        $new_stock = $product->stock + $operation->cantidad;

        // Asegurarse de aplicar el nuevo stock solo para el producto de esta operación
        ProductData::updateStockRevert($product->id_producto, $new_stock);
    }
    // echo $operation->id_producto;

    // Obtener el id_almacen correspondiente a esta operación de venta y producto
    $id_almacen = OperationData::getAlmacenIdByProductoAndTipo($operation->id_producto);
    $idAlmacen = $id_almacen->id_almacen;

    echo "<pre>";
    print_r($idAlmacen);
    echo "</pre>";

    // Eliminar el registro en tb_almacen si existe
    if ($id_almacen) {
        OperationData::deleteAlmacenById($idAlmacen);
    }

    // Eliminar el detalle de la venta después de ajustar el stock

    // Eliminar el detalle de la venta después de ajustar el stock
    OperationData::delById($operation->id_detalle_venta);
}

// Eliminar la venta principal
SellData::delById($_GET["id"]);

// Core::redir("./index.php?view=sells");
