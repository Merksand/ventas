<?php

$sell = SellData::getByIdReabastecimiento($_GET["id"]);
$operations = OperationData::getAllProductsByBuyId($_GET["id"]);

// echo "<pre>";
// print_r($operations);
// echo "</pre>";


foreach ($operations as $operation) {
    
    $product = $operation->getProduct($operation->id_producto);

    if ($product) {
        
        $new_stock = $product->stock - $operation->cantidad;

        
        ProductData::updateStockRevert($product->id_producto, $new_stock);
    }

    
    $id_almacen = OperationData::getAlmacenIdByCompraId($operation->id_compra);

    // echo $id_almacen;

    
    if ($id_almacen) {
        OperationData::deleteAlmacenById($id_almacen);
    }

    
    OperationData::delBuyById($operation->id_detalle_compra);
}



SellData::delBuyById($_GET["id"]);


Core::redir("./index.php?view=res");
?>
