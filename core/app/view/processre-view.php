<?php
if (isset($_SESSION["reabastecer"])) {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<pre><b>POST Data:</b>\n";
        foreach ($_POST as $key => $value) {
            echo htmlspecialchars("$key: $value\n");
        }
        echo "</pre>";
    }

    $cart = $_SESSION["reabastecer"];
    echo "<pre><b>Cart Content:</b>\n";
    print_r($cart);
    echo "</pre>";

    if (count($cart) > 0) {
        $process = true;

        if ($process) {
            $sell = new SellData();
            $sell->user_id = $_SESSION["user_id"];

            if (!empty($_POST["client_id"])) {
                $sell->person_id = $_POST["client_id"];
                $s = $sell->add_re_with_client();
            } else {
                $s = $sell->add_re();
            }

            if (!is_null($s[0])) {
                echo "<p><b>Reabastecimiento exitoso con ID:</b> " . htmlspecialchars($s[0]) . "</p>";
            } else {
                echo "<p><b>Error al agregar el reabastecimiento:</b> " . htmlspecialchars($s[1]) . "</p>";
            }

            // Inicializa la variable de total
            $total = 0;

            foreach ($cart as $c) {
                $product_id = $c["product_id"];
                $sell_id = $s[0];
                $quantity = $c["q"];

                if (empty($product_id) || empty($sell_id) || empty($quantity)) {
                    echo "<p><b>Error:</b> Datos faltantes para la operación con Product ID: {$product_id}, Sell ID: {$sell_id}, Quantity: {$quantity}</p>";
                    continue;
                }

                $op = new OperationData();
                $op->product_id = $product_id;
                $op->sell_id = $sell_id;
                $op->q = $quantity;

                if (!empty($_POST["is_oficial"])) {
                    $op->is_oficial = 1;
                }

                // Obtiene el precio de compra del producto
                $product = ProductData::getById($product_id);
                $subtotal = $product->precio_compra * $quantity;
                $total += $subtotal; // Acumula el total

                $op->add($product_id, $sell_id, $quantity);

                echo "<p><b>Subtotal para el Producto ID {$product_id}:</b> $ " . number_format($subtotal, 2) . "</p>";
            }

            // Actualizar el total de la compra en `tb_compras`
            $sqlUpdateTotal = "UPDATE tb_compras SET total_compra = $total WHERE id_compra = $sell_id";
            Executor::doit($sqlUpdateTotal);

            echo "<h3><b>Total de la Compra:</b> $ " . number_format($total, 2) . "</h3>";

            // Limpiar sesión y redirigir
            unset($_SESSION["reabastecer"]);
            setcookie("selled", "selled");

            // echo "<script>window.location='index.php?view=onere&id={$s[0]}';</script>";
        }
    }
}
