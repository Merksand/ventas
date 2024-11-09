<div class="btn-group pull-right">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		<i class="fa fa-download"></i> Descargar <span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		<li><a href="report/onesell-word.php?id=<?php echo $_GET["id"]; ?>">Word 2007 (.docx)</a></li>
	</ul>
</div>
<h1>Resumen de Venta</h1>

<?php if (isset($_GET["id"]) && $_GET["id"] != ""): ?>
	<?php
	
	$sellId = $_GET["id"];
	$sell = SellData::getById($sellId); 
	$operations = OperationData::getAllProductsBySellId($sellId); 
	$total = 0;

	?>

	<?php
	if (isset($_GET["id"]) && $_GET["id"] != "") {
		$sellId = $_GET["id"];
		// echo "Sell ID: " . $sellId; // Agrega esta línea para ver el ID de la venta
		$operations = OperationData::getAllProductsBySellId($sellId);
		$stockActual = OperationData::getQYesF(94);
		$product = OperationData::getProduct(94);
	} else {
		echo "Error: ID de venta no proporcionado o es inválido.";
	}

	?>

	<?php if (isset($_COOKIE["selled"])): ?>
		<?php
		$products = OperationData::getProductAlmacenVenta($sellId);  

		$user = $sell;  
		// echo "Este es el sell ID . " . $sellId;
		// echo "<pre>";
		// print_r($products);
		// echo "</pre>";
// 

		foreach ($products as $product) {
			// echo "<pre>";
			// print_r($products); // Muestra la información del producto
			// echo "</pre>";

			$stockActual = $product->producto_stock;
			// echo "<pre>";
			// print_r($stockActual);
			// echo "</pre>";
			if ($stockActual == 0) {
				echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $product->producto_nombre</b> no tiene existencias en inventario.</p>";
			} elseif ($stockActual <= $product->almacen_stock_minimo / 2) {
				echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $product->producto_nombre</b> tiene muy pocas existencias en inventario.</p>";
			} elseif ($stockActual <= $product->almacen_stock_minimo) {
				echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'>$product->producto_nombre</b> tiene pocas existencias en inventario.</p>";
			} else {
				echo "<p>El producto <b style='text-transform:uppercase;'> $product->producto_nombre</b> tiene suficiente stock.</p>";
			}
		}
		setcookie("selled", "", time() - 18600);
		?>
	<?php endif; ?>
	<?php
	?>
	<table class="table table-bordered">
		<?php 
			// echo "<pre>";
			// print_r($sell) ;
			// echo "</pre>";

		?>
		<?php if ($sell->id_cliente): ?>
			<?php

			// echo "ID del cliente: " . $sell->id_cliente;
			// echo "<pre>";
			// print_r($sell);
			// echo "</pre>";
			$client = $sell->getClient($sell->id_cliente); // Asume que esta función obtiene datos del cliente relacionado
			// echo "<pre>";
			// print_r($client);
			// echo "</pre>";
			?>
			<tr>
				<td style="width:150px;">Cliente</td>
				<td><?php echo $client->nombre . " " . $client->apellido_paterno . " " . $client->apellido_materno; ?></td>
			</tr>
		<?php endif; ?>

		<?php if ($sell->id_usuario): ?>
			<?php

			// echo "ID del usuario: " . $sell->id_usuario;
			$user = $sell->getBuyUser($sell->id_usuario);

			// echo "<pre>";
			// print_r($user);
			// echo "</pre>";

			?>
			<tr>
				<td>Atendido por</td>
				<td><?php echo $user->nombre . " " . $user->apellido_paterno . " " . $user->apellido_materno; ?></td>
			</tr>
		<?php endif; ?>
	</table>

	<br>
	<table class="table table-bordered table-hover">

		<thead>
			<th>Código</th>
			<th>Cantidad</th>
			<th>Nombre del Producto</th>
			<th>Precio Unitario</th>
			<th>Total</th>
		</thead>

		<?php
		$operationss = OperationData::getAllProductsBySellId($sellId); // Asume que esta función obtiene datos de `tb_detalle_venta`

		$total = 0;
		// echo $sellId;
		// echo "<pre>";
		// print_r($sell);
		// echo $operations[0]->id_producto;
		// // print_r($operations[0]->getProduct($idPro));
		// print_r($operations);
		// echo "</pre>";
		?>
		<?php foreach ($operationss as $producto): ?>
			<?php
			// $idPro = $operations[0]->id_producto;
			$idProducto = $producto->id_producto;
			// echo "<pre>";
			// // print_r($producto->id_producto);
			// print_r($producto);
			// echo "</pre>";
			?>


			<?php
			// echo $producto;
			$product = $producto->getProduct($idProducto);


			// echo "<pre>";
			// print_r($producto->getProduct($producto->id_producto));
			// echo "</pre>";
			$precioUnitario = $producto->precio_unitario; 
			$cantidad = $producto->cantidad; 
			$subtotal = $cantidad * $precioUnitario;
			$total += $subtotal;
			?>
			<tr>
				<td><?php echo $product->codigo_producto; ?></td>
				<td><?php echo $cantidad; ?></td>
				<td><?php echo $product->nombre_producto; ?></td>
				<td>Bs <?php echo number_format($product->precio_venta, 2, ".", ","); ?></td>
				<td><b>$ <?php echo number_format($subtotal, 2, ".", ","); ?></b></td>
			</tr>
		<?php endforeach; ?>
	</table>

	<br><br>
	<div class="row">
		<div class="col-md-4">
			<table class="table table-bordered">
				<tr>
					<td>
						<h4>Total:</h4>
					</td>
					<td>
						<h4>Bs <?php echo number_format($total, 2, '.', ','); ?></h4>
					</td>
				</tr>
			</table>
		</div>
	</div>

<?php else: ?>
	501 Internal Error
<?php endif; ?>