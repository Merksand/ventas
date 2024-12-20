<?php
// session_start(); // Asegúrate de que la sesión esté activa // Modificado

if (!isset($_SESSION["cart"])) {

    // Crear el carrito con el primer producto
    $product = array("product_id" => $_POST["product_id"], "q" => $_POST["q"]); // Modificado
    $_SESSION["cart"] = array($product); // Modificado

    $cart = $_SESSION["cart"]; // Modificado
    $quantity = isset($_POST['q']) ? (int)$_POST['q'] : 0; // Modificado

    ///////////////////////////////////////////////////////////////////
    $num_succ = 0;
    $process = false;
    $errors = array();
    foreach ($cart as $c) {
        // Usar el nuevo método getStock
        $q = OperationData::getStock($c["product_id"]);

        if ($c["q"] <= $q) {
            $num_succ++;
        } else {
            $error = array(
                "product_id" => $c["product_id"],
                "message" => "No hay suficiente cantidad de producto en inventario. Stock disponible: " . $q
            );
            $errors[] = $error;
        }
    }
    ///////////////////////////////////////////////////////////////////

    if ($num_succ == count($cart)) {
        $process = true;
    }
    if ($process == false) {
        unset($_SESSION["cart"]); // Modificado
        $_SESSION["errors"] = $errors; // Modificado
        ?>
        <script>
            window.location = "index.php?view=sell";
        </script>
        <?php
    }
} else {
    $found = false;
    $cart = $_SESSION["cart"]; // Modificado
    $index = 0;

    // Usar el nuevo método getStock
    $q = OperationData::getStock($_POST["product_id"]);

    $can = true;
    $errors = array();
    if ($_POST["q"] <= $q) {
    } else {
        $error = array(
            "product_id" => $_POST["product_id"],
            "message" => "No hay suficiente cantidad de producto en inventario. Stock disponible: " . $q
        );
        $errors[] = $error;
        $can = false;
    }

    if ($can == false) {
        $_SESSION["errors"] = $errors;
        ?>
        <script>
            window.location = "index.php?view=sell";
        </script>
        <?php
    }

    if ($can == true) {
        foreach ($cart as $c) {
            if ($c["product_id"] == $_POST["product_id"]) {
                $found = true;
                break;
            }
            $index++;
        }

        if ($found == true) {
            $q1 = $cart[$index]["q"];
            $q2 = $_POST["q"];
            $cart[$index]["q"] = $q1 + $q2;
            $_SESSION["cart"] = $cart;
        }

        if ($found == false) {
            $nc = count($cart);
            $product = array("product_id" => $_POST["product_id"], "q" => $_POST["q"]);
            $cart[$nc] = $product;
            $_SESSION["cart"] = $cart;
        }
    }
}

print "<script>window.location='index.php?view=sell';</script>";
?>
