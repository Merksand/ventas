<?php
if (isset($_SESSION["reabastecer"])) {

    // Verificar si la solicitud es POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Mostrar datos enviados por el formulario para depuración
        echo "<pre><b>POST Data:</b>\n"; 
        foreach ($_POST as $key => $value) {
            echo htmlspecialchars("$key: $value\n");
        }
        echo "</pre>";
    }

    $cart = $_SESSION["reabastecer"];
    echo "<pre><b>Cart Content:</b>\n";
    print_r($cart); // Verificar el contenido del carrito
    echo "</pre>";

    if (count($cart) > 0) {

        $process = true;

        if ($process) {
            // Crear nueva instancia de SellData para registrar la venta
            $sell = new SellData();
            $sell->user_id = $_SESSION["user_id"];

            echo "<pre><b>User ID:</b> {$sell->user_id}</pre>"; // Verificar el ID de usuario

            // Verificar si se ha seleccionado un cliente
            if (!empty($_POST["client_id"])) {
                $sell->person_id = $_POST["client_id"];
                echo "<pre><b>Client ID:</b> {$sell->person_id}</pre>"; // Verificar el ID de cliente

                $s = $sell->add_re_with_client(); // Método para agregar con cliente
                echo "<p><b>add_re_with_client() Result:</b></p><pre>";
                print_r($s);
                echo "</pre>";
            } else {
                $s = $sell->add_re(); // Método para agregar sin cliente
                echo "<p><b>add_re() Result:</b></p><pre>";
                print_r($s);
                echo "</pre>";
            }

            // Manejar la respuesta del método de adición
            if (!is_null($s[0])) {
                echo "<p><b>Reabastecimiento exitoso con ID:</b> " . htmlspecialchars($s[0]) . "</p>";
            } else {
                echo "<p><b>Error al agregar el reabastecimiento:</b> " . htmlspecialchars($s[1]) . "</p>";
            }

            // Procesar cada producto del carrito
            foreach ($cart as $c) {
                $product_id = $c["product_id"];
                $sell_id = $s[0];
                $quantity = $c["q"];

                echo "<pre><b>Processing Product:</b> Product ID: {$product_id}, Sell ID: {$sell_id}, Quantity: {$quantity}</pre>";

                if (empty($product_id) || empty($sell_id) || empty($quantity)) {
                    echo "<p><b>Error:</b> Datos faltantes para la operación con Product ID: {$product_id}, Sell ID: {$sell_id}, Quantity: {$quantity}</p>";
                    continue; // Saltar esta operación si faltan datos
                }

                // Crear una nueva instancia de OperationData
                $op = new OperationData();
                $op->product_id = $product_id;
                $op->sell_id = $sell_id;
                $op->q = $quantity;

                // Verificar si es una operación oficial
                if (!empty($_POST["is_oficial"])) {
                    $op->is_oficial = 1;
                    echo "<pre><b>Operation is official</b></pre>";
                }

                // Agregar operación y mostrar resultado
                $result = $op->add($product_id, $sell_id, $quantity);
                echo "<p><b>Operation add() Result for Product ID {$product_id}:</b></p><pre>";
                print_r($result);
                echo "</pre>";
            }

            // Limpiar la sesión del carrito de reabastecimiento
            unset($_SESSION["reabastecer"]);
            setcookie("selled", "selled");

            // Redirección comentada para depuración
            echo "<script>window.location='index.php?view=onere&id={$s[0]}';</script>";
        }
    }
}
?>
