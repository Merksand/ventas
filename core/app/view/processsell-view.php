<?php
session_start(); // Asegúrate de iniciar la sesión si no está iniciada

if (isset($_SESSION["cart"]) && count($_SESSION["cart"]) > 0) {
    $cart = $_SESSION["cart"];
    $errors = [];
    $num_succ = 0;
    $process = false;

    // 1. Validación de existencia de productos en inventario
    foreach ($cart as $item) {
        $cantidadEnInventario = OperationData::getQYesF($item["product_id"]); // Obtener cantidad disponible del producto

        if ($item["q"] <= $cantidadEnInventario) {
            if (isset($_POST["is_oficial"])) {
                $cantidadFacturable = OperationData::getQYesF($item["product_id"]);
                if ($item["q"] <= $cantidadFacturable) {
                    $num_succ++;
                } else {
                    $errors[] = [
                        "product_id" => $item["product_id"],
                        "message" => "No hay suficiente cantidad de producto para facturar en inventario."
                    ];
                }
            } else {
                $num_succ++;
            }
        } else {
            $errors[] = [
                "product_id" => $item["product_id"],
                "message" => "No hay suficiente cantidad de producto en inventario."
            ];
        }
    }

    // 2. Verificación si todos los productos cumplen con la cantidad requerida
    if ($num_succ == count($cart)) {
        $process = true;
    }

    // 3. Si hay errores, redirige de regreso a la vista de ventas
    if (!$process) {
        $_SESSION["errors"] = $errors;
        echo "<script>window.location = 'index.php?view=sell';</script>";
        exit;
    }

    // 4. Si el proceso es válido, inserta la venta en `tb_ventas`
    $idUsuario = $_SESSION["user_id"];
    $totalVenta = floatval($_POST["total"]);
    $cantidadTotal = array_sum(array_column($cart, "q")); // Cantidad total de productos en el carrito
    $efectivo = floatval($_POST["money"]);
    $idCliente = isset($_POST["client_id"]) && $_POST["client_id"] != "" ? $_POST["client_id"] : null;

    echo "ID del cliente : ".$idCliente;

    // Inserta la venta en `tb_ventas`
    include "./../../controller/Database.php";
	$con = new Database();
	$con = $con->connect();

    $sqlVenta = "INSERT INTO tb_ventas (id_cliente, id_usuario, total_venta, cantidad_total, efectivo, fecha_venta) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmtVenta = $con->prepare($sqlVenta);
    $stmtVenta->bind_param("iidii", $idCliente, $idUsuario, $totalVenta, $cantidadTotal, $efectivo);
    $stmtVenta->execute();

    $idVenta = $stmtVenta->insert_id; // Obtén el ID de la venta creada
    $stmtVenta->close();

    // 5. Inserta los detalles de la venta en `tb_detalle_venta`
    $sqlDetalle = "INSERT INTO tb_detalle_venta (id_venta, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
    $stmtDetalle = $con->prepare($sqlDetalle);

    foreach ($cart as $item) {
        $idProducto = $item["product_id"];
        $cantidad = $item["q"];
        $precioUnitario = ProductData::getById($idProducto)->precio_venta;

        $stmtDetalle->bind_param("iiid", $idVenta, $idProducto, $cantidad, $precioUnitario);
        $stmtDetalle->execute();
    }

    $stmtDetalle->close();

    // 6. Limpia el carrito de la sesión y redirige a la página de confirmación
    unset($_SESSION["cart"]);
    setcookie("selled", "selled", time() + 3600, "/");
    
    echo "<script>window.location = 'index.php?view=onesell&id=$idVenta';</script>";
} else {
    echo "<script>alert('El carrito está vacío o faltan datos.'); window.location = 'index.php?view=sell';</script>";
}
?>
