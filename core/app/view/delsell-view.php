<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);


foreach ($operations as $operation) {
    $product = $operation->getProduct($operation->id_producto);

    if ($product) {
        $new_stock = $product->stock + $operation->cantidad;

        echo $new_stock;

        ProductData::updateStockRevert($product->id_producto, $new_stock);
    }
    $id_almacen = OperationData::getAlmacenIdByProductoAndTipo($operation->id_producto);
    $idAlmacen = $id_almacen[0]->id_almacen;

    echo "<pre>";
    print_r($idAlmacen);
    echo "</pre>";

    if ($id_almacen) {
        OperationData::deleteAlmacenById($idAlmacen);
    }

    OperationData::delById($operation->id_detalle_venta);
}
echo $_GET["id"];

SellData::delById($_GET["id"]);




// Core::redir("./index.php?view=sells");
