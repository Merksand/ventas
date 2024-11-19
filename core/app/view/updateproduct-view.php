<?php

if (count($_POST) > 0) {


	$product = ProductData::getById($_POST["product_id"]);

	$product->codigo_producto = $_POST["codigo_producto"];
	$product->nombre_producto = $_POST["nombre_producto"];
	$product->descripcion = $_POST["descripcion"];
	$product->precio_compra = $_POST["precio_compra"];
	$product->precio_venta = $_POST["precio_venta"];
	$product->stock = $_POST["stock"];
	$product->stock_minimo = $_POST["stock_minimo"];
	$product->id_categoria = !empty($_POST["id_categoria"]) ? $_POST["id_categoria"] : null;
	$product->is_active = $_POST["is_active"];
	$product->user_id = $_SESSION["user_id"];  

	$product->update();

	if (isset($_FILES["image"])) {
		$image = new Upload($_FILES["image"]);
		if ($image->uploaded) {
			$image->Process("storage/products/");
			if ($image->processed) {
				$product->imagen = $image->file_dst_name;
				$product->update_image(); 
			}
		}
	}

	setcookie("prdupd", "true");
	// echo "<script;>window.location='index.php?view=editproduct&id=" . $_POST["product_id"] . "';</script;
	echo "<script>window.location='index.php?view=products';</script>";
}
