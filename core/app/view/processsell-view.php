<?php

if (isset($_SESSION["cart"]) && count($_SESSION["cart"]) > 0) {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $cart = $_SESSION["cart"];
        $errors = [];
        $num_succ = 0;
        $process = false;

        // 1. Validación de existencia de productos en inventario
        foreach ($cart as $item) {
            $cantidadEnInventario = OperationData::getQYesF($item["product_id"]);
            if ($item["q"] <= $cantidadEnInventario) {
                $num_succ++;
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
        // if (!$process) {
        //     $_SESSION["errors"] = $errors;
        //     exit;
        // }

        $con = new Database();
        $con = $con->connect();
    

        // 4. Insertar Venta
        $idUsuario = $_SESSION["user_id"];

        $idUsuario = PersonData::getUsuarioIdByPersonId($idUsuario);
        $totalVenta = floatval($_POST["total"]);
        $cantidadTotal = array_sum(array_column($cart, "q"));
        $efectivo = floatval($_POST["money"]);
        $idCliente = isset($_POST["client_id"]) && $_POST["client_id"] != "" ? $_POST["client_id"] : null;
        $idCliente = PersonData::getClientIdByPersonId($idCliente);

        $sqlVenta = "INSERT INTO tb_ventas (id_cliente, id_usuario, total_venta, cantidad_total, efectivo) VALUES (?, ?, ?, ?, ?)";
        $stmtVenta = $con->prepare($sqlVenta);
        $stmtVenta->bind_param("iidii", $idCliente, $idUsuario, $totalVenta, $cantidadTotal, $efectivo);
        $stmtVenta->execute();
        $idVenta = $stmtVenta->insert_id;
        $stmtVenta->close();

        // 5. Insertar detalles de la venta en `tb_detalle_venta`
        $sqlDetalle = "INSERT INTO tb_detalle_venta (id_venta, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
        $stmtDetalle = $con->prepare($sqlDetalle);

        foreach ($cart as $item) {
            $idProducto = $item["product_id"];
            $cantidad = $item["q"];
            $precioUnitario = ProductData::getById($idProducto)->precio_venta;

            // Registrar detalle de venta
            $stmtDetalle->bind_param("iiid", $idVenta, $idProducto, $cantidad, $precioUnitario);
            $stmtDetalle->execute();

            // Registrar salida en `tb_almacen`
            $sqlAlmacen = "INSERT INTO tb_almacen (id_producto, stock_actual, tipo_operacion) VALUES ($idProducto, $cantidad, 'salida')";
            Executor::doit($sqlAlmacen);

            // Actualizar el stock en `tb_productos`
            ProductData::actualizarStockProducto($idProducto);
        }

        $stmtDetalle->close();

        // 6. Limpia el carrito de la sesión y redirige a la página de confirmación
        unset($_SESSION["cart"]);
        setcookie("selled", "selled", time() + 3600, "/");
        echo "<script>window.location = 'index.php?view=onesell&id=$idVenta';</script>";
        // echo "ladkfjasdlkfjasdfjasdf";s
        
    } else {
        echo "<script>alert('El carrito está vacío o faltan datos.'); window.location = 'index.php?view=sell';</script>";
    }
}
