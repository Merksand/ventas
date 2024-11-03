<?php
// index.php

// Iniciar la sesión al inicio del archivo
session_start();

// Verificar la acción
if (isset($_GET['view']) && $_GET['view'] == 'addtocart') {
    // Capturar los datos del formulario
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
    $quantity = isset($_POST['q']) ? (int)$_POST['q'] : 0;

    // Verificar que los valores sean válidos
    if ($product_id && $quantity > 0) {
        // Verificar si el carrito ya existe en la sesión
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = []; // Inicializar el carrito si no existe
        }

        // Agregar o actualizar la cantidad del producto en el carrito
        if (isset($_SESSION['cart'][$product_id])) {
            // Si ya existe el producto, sumar la cantidad
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            // Si no existe, agregar el producto con la cantidad inicial
            $_SESSION['cart'][$product_id] = $quantity;
        }

        // Redireccionar a la interfaz de venta o mostrar mensaje de éxito
        header("Location: index.php?view=venta");
        exit();
    } else {
        echo "Error: Debes seleccionar un producto y una cantidad válida.";
    }
}
