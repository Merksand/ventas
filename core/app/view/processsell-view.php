<?php
// session_start(); // Asegúrate de iniciar la sesión si no está iniciada

if (isset($_SESSION["cart"]) && count($_SESSION["cart"]) > 0) {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<pre>";
        foreach ($_POST as $key => $value) {
            echo "$key: $value\n";
        }
        echo "</pre>";
    }

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

        // echo "Error del proceso de venta ". $process;
        // echo 
        // echo "<script>window.location = 'index.php?view=sell';</script>";
        exit;
    }

    $idUsuario = $_SESSION["user_id"];
    $totalVenta = floatval($_POST["total"]);
    $cantidadTotal = array_sum(array_column($cart, "q")); // Cantidad total de productos en el carrito
    $efectivo = floatval($_POST["money"]);
    $idCliente = isset($_POST["client_id"]) && $_POST["client_id"] != "" ? $_POST["client_id"] : null;

    $idCliente = PersonData::getClientIdByPersonId($idCliente);


    // echo "ID del usuario : " . $idUsuario . "<br>";
    // echo "Total de la venta : " . $totalVenta . "<br>";
    // echo "Cantidad total : " . $cantidadTotal . "<br>";
    // echo "Efectivo : " . $efectivo . "<br>";
    // echo "ID del cliente : " . $idCliente . "<br>";

    $con = new Database();
    $con = $con->connect();

    // if ($con->connect_error) {
    //     echo "Error de conexión: " . $con->connect_error;
    // } else {
    //     echo "Conexión exitosa: " . $con->host_info;
    // }



    $sqlVenta = "INSERT INTO tb_ventas (id_cliente, id_usuario, total_venta, cantidad_total, efectivo) VALUES (?, ?, ?, ?, ?)";
    $stmtVenta = $con->prepare($sqlVenta);


    $stmtVenta->bind_param("iidii", $idCliente, $idUsuario, $totalVenta, $cantidadTotal, $efectivo);


    $stmtVenta->execute();



    $idVenta = $stmtVenta->insert_id; // Obtén el ID de la venta creada
    $stmtVenta->close();

    // 5. Inserta los detalles de la venta en `tb_detalle_venta`
    $sqlDetalle = "INSERT INTO tb_detalle_venta (id_venta, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
    $stmtDetalle = $con->prepare($sqlDetalle);

    $sqlUpdateInventario = "UPDATE tb_almacen SET stock_actual = stock_actual - ? WHERE id_producto = ?";
    $stmtUpdate = $con->prepare($sqlUpdateInventario);

    foreach ($cart as $item) {
        $idProducto = $item["product_id"];
        $cantidad = $item["q"];
        $precioUnitario = ProductData::getById($idProducto)->precio_venta;

        echo "ID del producto : " . $idProducto . "<br>";
        echo "Cantidad : " . $cantidad . "<br>";
        echo "Precio unitario : " . $precioUnitario . "<br>";

        // Inserta el detalle de la venta
        $stmtDetalle->bind_param("iiid", $idVenta, $idProducto, $cantidad, $precioUnitario);
        $stmtDetalle->execute();

        // Disminuye el inventario en `tb_almacen`
        $stmtUpdate->bind_param("ii", $cantidad, $idProducto);
        if ($stmtUpdate->execute()) {
            if ($stmtUpdate->affected_rows > 0) {
                echo "Inventario actualizado correctamente para el producto con ID: $idProducto<br>";
            } else {
                echo "No se encontró el producto con ID: $idProducto en el inventario, o no se necesitó ajustar el stock.<br>";
            }
        } else {
            echo "Error al actualizar el inventario para el producto con ID: $idProducto. Error: " . $stmtUpdate->error . "<br>";
        }
    }

    $stmtDetalle->close();
    $stmtUpdate->close();


    // 6. Limpia el carrito de la sesión y redirige a la página de confirmación
    unset($_SESSION["cart"]);
    setcookie("selled", "selled", time() + 3600, "/");


    echo "<script>window.location = 'index.php?view=onesell&id=$idVenta';</script>";
} else {
    echo "<script>alert('El carrito está vacío o faltan datos.'); window.location = 'index.php?view=sell';</script>";
}
