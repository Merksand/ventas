<?php

if (count($_POST) > 0) {

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mostrar todos los datos del formulario
    echo "<pre>"; // Para formatear mejor la salida
    foreach ($_POST as $key => $value) {
        echo "$key: $value\n"; // Imprime la clave y el valor
    }
    echo "</pre>";
}

	// Obtener el producto a editar
	$product = ProductData::getById($_POST["product_id"]);

	// Asignar valores desde el formulario a las propiedades del producto
	$product->codigo_producto = $_POST["codigo_producto"];
	$product->nombre_producto = $_POST["nombre_producto"];
	$product->descripcion = $_POST["descripcion"];
	$product->precio_compra = $_POST["precio_compra"];
	$product->precio_venta = $_POST["precio_venta"];
	$product->stock = $_POST["stock"];
	$product->stock_minimo = $_POST["stock_minimo"];
	// $product->unidad = $_POST["unidad"];
	// $product->presentacion = $_POST["presentacion"];
	$product->id_categoria = !empty($_POST["id_categoria"]) ? $_POST["id_categoria"] : null;
	$product->is_active = $_POST["is_active"];
	$product->user_id = $_SESSION["user_id"]; // Usuario que realiza la edición

	// Actualizar el producto en la base de datos
	$product->update();

	// Manejo de imagen, si fue cargada
	if (isset($_FILES["image"])) {
		$image = new Upload($_FILES["image"]);
		if ($image->uploaded) {
			$image->Process("storage/products/");
			if ($image->processed) {
				$product->imagen = $image->file_dst_name;
				$product->update_image(); // Actualizar solo la imagen en la base de datos
			}
		}
	}

	// Establecer cookie de éxito y redirigir
	setcookie("prdupd", "true");
	echo "<script>window.location='index.php?view=editproduct&id=" . $_POST["product_id"] . "';</script>";
}
