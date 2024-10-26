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
    $product->stock_minimo = !empty($_POST["stock_minimo"]) ? $_POST["stock_minimo"] : NULL; // Asignar stock mínimo

    // Asignar la categoría, si se seleccionó
    $product->id_categoria = !empty($_POST["id_categoria"]) ? $_POST["id_categoria"] : NULL; // Asignar categoría

    // Agregar producto a la base de datos
    $prod = $product->add(); // Método que agrega el producto sin imagen ni lógica de stock

    // Redirigir después de agregar el producto

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == UPLOAD_ERR_OK) {
        $image = new Upload($_FILES["imagen"]);
        $image->file_max_size = '10M'; // Ajustar a un valor adecuado
        $image->process("storage/products/");
    
        if ($image->processed) {
            $product->imagen = $image->file_dst_name; // Guardar el nombre del archivo de la imagen en el producto
            $prod = $product->add_with_image(); // Agregar producto con imagen
        } else {
            echo 'Error al procesar la imagen: ' . $image->error;
        }
    } else {
        echo 'Error en la carga de la imagen: ' . $_FILES["imagen"]["error"];
        // Manejar errores específicos
    }
    
    print "<script>window.location='index.php?view=products';</script>";
}
?>
