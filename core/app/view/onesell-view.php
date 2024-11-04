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
	// Obtiene la venta y los detalles de la venta
	$sellId = $_GET["id"];
	$sell = SellData::getById($sellId); // Asume que esta función obtiene datos de `tb_ventas`
	$operations = OperationData::getAllProductsBySellId($sellId); // Asume que esta función obtiene datos de `tb_detalle_venta`
	$total = 0;

	echo "<pre>";
	print_r($sell);
	print_r($operations);
	echo "</pre>";
	?>

	<?php
	if (isset($_GET["id"]) && $_GET["id"] != "") {
		$sellId = $_GET["id"];
		echo "Sell ID: " . $sellId; // Agrega esta línea para ver el ID de la venta


		$operations = OperationData::getAllProductsBySellId($sellId);
		$stockActual = OperationData::getQYesF(94);
		echo "<pre>";
		// print_r($operations[0]->getProduct(93)); // Verifica los datos de las operaciones de la venta
		// $producto = ProductData::getById(94);
		$product = OperationData::getProduct(94);

		print_r($stockActual);
		echo "</pre>";
	} else {
		echo "Error: ID de venta no proporcionado o es inválido.";
	}

	?>

	<?php if (!isset($_COOKIE["selled"])): ?>
		<?php
		// Obtiene todos los productos relacionados con la venta
		$products = OperationData::getProductAlmacenVenta($sellId); // Asume que devuelve un array de productos

		$user = $sell; // Asume que esta función obtiene datos del usuario relacionado
		echo "hofff";
		echo "<pre>";
		print_r($user);
		echo "</pre>";

		foreach ($products as $product) {
			echo "Putoss";
			echo "<pre>";
			print_r($product); // Muestra la información del producto
			echo "</pre>";

			// Verifica la cantidad disponible en el inventario
			$stockActual = $product->stock_actual; // Utiliza el stock actual del producto obtenido de la consulta
			if ($stockActual == 0) {
				echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $product->product_name</b> no tiene existencias en inventario.</p>";
			} elseif ($stockActual <= $product->stock_minimo / 2) {
				echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $product->product_name</b> tiene muy pocas existencias en inventario.</p>";
			} elseif ($stockActual <= $product->stock_minimo) {
				echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $product->product_name</b> tiene pocas existencias en inventario.</p>";
			} else {
				echo "<p>El producto <b style='text-transform:uppercase;'> $product->product_name</b> tiene suficiente stock.</p>";
			}
		}
		setcookie("selled", "", time() - 18600);
		?>
	<?php endif; ?>
	<?php

	?>

	<table class="table table-bordered">
		<?php if ($sell->id_cliente): ?>
			<?php
			$client = $sell->getClient(); // Asume que esta función obtiene datos del cliente relacionado
			?>
			<tr>
				<td style="width:150px;">Cliente</td>
				<td><?php echo $client->name . " " . $client->lastname; ?></td>
			</tr>
		<?php endif; ?>

		<?php if ($sell->id_usuario): ?>
			<?php
			$user = $sell->getUser(); // Asume que esta función obtiene datos del usuario relacionado

			echo "<pre>";
			print_r($user);
			echo "</pre>";
			?>
			<tr>
				<td>Atendido por</td>
				<td><?php echo $user->name . " " . $user->lastname; ?></td>
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
		<?php foreach ($operations as $operation): ?>
			<?php
			$product = $operation->getProduct(); // Obtiene el producto relacionado con el detalle de la venta
			$precioUnitario = $operation->precio_unitario; // Precio unitario guardado en `tb_detalle_venta`
			$cantidad = $operation->cantidad; // Cantidad de producto en el detalle de la venta
			$subtotal = $cantidad * $precioUnitario;
			$total += $subtotal;
			?>
			<tr>
				<td><?php echo $product->id; ?></td>
				<td><?php echo $cantidad; ?></td>
				<td><?php echo $product->name; ?></td>
				<td>$ <?php echo number_format($precioUnitario, 2, ".", ","); ?></td>
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
						<h4>Subtotal:</h4>
					</td>
					<td>
						<h4>$ <?php echo number_format($total, 2, '.', ','); ?></h4>
					</td>
				</tr>
				<tr>
					<td>
						<h4>Total:</h4>
					</td>
					<td>
						<h4>$ <?php echo number_format($total, 2, '.', ','); ?></h4>
					</td>
				</tr>
			</table>
		</div>
	</div>

<?php else: ?>
	501 Internal Error
<?php endif; ?>