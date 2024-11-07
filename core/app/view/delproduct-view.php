<?php
// Obtener el producto y desactivarlo en lugar de eliminarlo
$product = ProductData::getById($_GET["id"]);
$product->is_active = 0; // Marcar el producto como inactivo
$product->update(); // Actualizar el estado del producto en la base de datos

// Redirigir a la lista de productos
Core::redir("./index.php?view=products");
?>
