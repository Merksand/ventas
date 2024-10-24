<?php

if (count($_POST) > 0) {
    $product = new ProductData();
    
    // Asignar los valores del formulario a las propiedades del objeto ProductData
    $product->codigo_producto = $_POST["codigo_producto"]; // Código del producto
    $product->nombre_producto = $_POST["nombre_producto"]; // Nombre del producto
    $product->precio_compra = $_POST["precio_compra"]; // Precio de compra
    $product->precio_venta = $_POST["precio_venta"]; // Precio de venta
    $product->descripcion = $_POST["descripcion"]; // Descripción
    $product->stock = $_POST["stock"]; // Cantidad en stock

    // Manejo del stock mínimo
    $stock_minimo = "NULL"; // Por defecto a NULL
    if (!empty($_POST["stock_minimo"])) {
        $stock_minimo = $_POST["stock_minimo"];
    }
    $product->stock_minimo = $stock_minimo; // Asignar stock mínimo

    // Asignar la categoría, si se seleccionó
    $category_id = "NULL"; // Por defecto a NULL
    if (!empty($_POST["id_categoria"])) {
        $category_id = $_POST["id_categoria"];
    }
    $product->id_categoria = $category_id;

    // Asignar el ID del usuario que está agregando el producto
    $product->user_id = $_SESSION["user_id"];

    // Manejo de la imagen
    if (isset($_FILES["imagen"])) {
        $image = new Upload($_FILES["imagen"]);
        if ($image->uploaded) {
            $image->Process("storage/products/");
            if ($image->processed) {
                $product->imagen = $image->file_dst_name; // Nombre del archivo de la imagen
                $prod = $product->add_with_image(); // Agregar producto con imagen
            }
        } else {
            $prod = $product->add(); // Agregar producto sin imagen
        }
    } else {
        $prod = $product->add(); // Agregar producto sin imagen
    }

    // Manejo del stock inicial
    if (!empty($_POST["stock"]) && $_POST["stock"] != "0") {
        $op = new OperationData();
        $op->product_id = $prod[1]; // ID del producto agregado
        $op->operation_type_id = OperationTypeData::getByName("entrada")->id; // Tipo de operación: entrada
        $op->q = $_POST["stock"]; // Cantidad inicial en stock
        $op->sell_id = "NULL"; // Sin ID de venta
        $op->is_oficial = 1; // Marcar como oficial
        $op->add(); // Agregar operación de stock
    }

    // Redirigir después de agregar el producto
    print "<script>window.location='index.php?view=products';</script>";
}
?>
