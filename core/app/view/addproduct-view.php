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
    $product->stock_minimo = $_POST["stock_minimo"];

    // Manejo del stock mínimo
    $product->stock_minimo = !empty($_POST["stock_minimo"]) ? $_POST["stock_minimo"] : NULL; // Asignar stock mínimo

    // Asignar la categoría, si se seleccionó
    $product->id_categoria = !empty($_POST["id_categoria"]) ? $_POST["id_categoria"] : NULL; // Asignar categoría

    // Agregar producto a la base de datos
    // $prod = $product->add(); // Método que agrega el producto sin imagen ni lógica de stock

    // Redirigir después de agregar el producto

    if (isset($_FILES["imagen"])) {
        if ($_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {

            $image = new Upload($_FILES["imagen"]);
            if ($image->uploaded) {
                $image->Process("storage/products/");
                if ($image->processed) {
                    $product->imagen = $image->file_dst_name;
                    $prod = $product->add_with_image();
                }
            }
        } else {
            echo "Error al cargar la imagen: " . $_FILES["imagen"]["error"];
        }
    }



    // $product -> addAlmacenEntry(1, 'entrada', $stock_minimo, 5);
    // $product->add_with_stock();  

    $lastId = ProductData::getLastId(); // Obtiene el último ID insertado

    // Ahora insertar en la tabla de almacen
    $product->addAlmacenEntry($lastId, 'entrada', $product->stock_minimo, $product->stock); // Agregar entrada a almacen




    print "<script>window.location='index.php?view=products';</script>";
}
