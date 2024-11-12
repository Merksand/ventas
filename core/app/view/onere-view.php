<div class="btn-group pull-right">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		<i class="fa fa-download"></i> Descargar <span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		<li><a href="report/onere-word.php?id=<?php echo $_GET["id"]; ?>">Pdf</a></li>
	</ul>
</div>
<h1>Resumen de Reabastecimiento</h1>
<?php if (isset($_GET["id"]) && $_GET["id"] != ""): ?>
	
	<?php

	$sell = SellData::getByIdReabastecimiento($_GET["id"]);
	$operations = OperationData::getAllProductsByBuyId($_GET["id"]);

	// echo "<pre>";
	// print_r($operations);
	// echo "</pre>";
	$total = 0;
			
	?>
	<?php
	// $comprobar = ($_COOKIE["selled"]) ? $_COOKIE["selled"] : "No existe selled we";
	// echo "cookie de selled ".$comprobar."<br>";
	if (isset($_COOKIE["selled"])) {
		foreach ($operations as $operation) {

			// echo "<p class='alert alert-info'>El producto <b style='text-transform:uppercase;'> $operation->name</b> tiene pocas existencias en inventario.</p>";
			// echo "<pre>";
			// 		print_r($operation);
			// echo "</pre>";
			// echo "Id producto de getQYesF: " . $operation->id_producto;

			$qx = OperationData::getAvasQYesF($operation->id_producto);
			// print "qx=$qx";
			$p = $operation->getProduct($operation->id_producto);

			// echo "<pre>";
			// echo "<>";
			// print_r($p);
			// echo "<br>";
			// echo "</pre>";
			// print_r($p->{16});
			// echo "</pre>";
			if ($p->{16} == 0) {
				echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->nombre_producto</b> no tiene existencias en inventario.</p>";
			} else if ($p->stock <= $p->{16}  / 2) {
				echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->nombre_producto</b> tiene muy pocas existencias en inventario.</p>";
			} else if ($p->stock <= $p->{16}) {
				echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $p->nombre_producto</b> tiene pocas existencias en inventario.</p>";
			}
		}
		setcookie("selled", "", time() - 18600);
	}

	?>
	<table class="table table-bordered">
		<?php if (!$sell): ?>
			<p class="alert alert-danger">No se encontró la información del reabastecimiento con el ID proporcionado.</p>
		<?php endif; ?>

		<?php
		// echo "<pre>";
		// print_r($sell);
		// echo "</pre>";
		?>
		<?php if ($sell->person_id != ""):
			$client = $sell->getPerson();

			// echo "<pre>";
			// print_r($client);
			// echo "</pre>";
		?>
			<tr>
				<td style="width:150px;">Proveedor</td>
				<td><?php echo $client->nombre . " " . $client->apellido_paterno. " " . $client->apellido_materno; ?></td>
			</tr>

		<?php endif; ?>

		<!-- <?php 
			echo "Usuario id ". $_SESSION['user_id'];
		?> -->
		
		<?php if ($sell->user_id != ""):
			$user = $sell->getUser($_SESSION['user_id']);

			// echo "<pre>";
			// print_r($user);
			// echo "</pre>";
		?>
			<tr>
				<td>Atendido por</td>
				<td><?php echo $user->nombre . " " . $user->apellido_paterno. " " . $user->apellido_materno; ?></td>
			</tr>
		<?php endif; ?>
	</table>
	<br>
	<table class="table table-bordered table-hover">
		<thead>
			<th>Codigo</th>
			<th>Cantidad</th>
			<th>Nombre del Producto</th>
			<th>Precio Unitario</th>
			<th>Total</th>

		</thead>

		<?php 
			// echo "culo";
			// echo "<pre>";
			// print_r($operations[0]);
			// echo "</pre>";
		?>
		<?php
		foreach ($operations as $operation) {
			// echo "ID que quiero ". $operation->product_id. "<BR>";

			$product  = $operation->getProduct($operation->id_producto);

			// echo "<pre>";
			// print_r($product);
			// echo "</pre>";
			// echo "Pitazo ". $operation->cantidad;

			
		?>
			<tr>
				<td><?php echo $product->id_producto; ?></td>
				<td><?php echo $operation->cantidad; ?></td>
				<td><?php echo $product->nombre_producto; ?></td>
				<td>Bs <?php echo number_format($product->precio_compra, 2, ".", ","); ?></td>
				<td><b>Bs <?php echo number_format($operation->cantidad * $product->precio_compra, 2, ".", ",");
							$total += $operation->cantidad * $product->precio_compra; ?></b></td>
			</tr>
		<?php
		}
		?>
	</table>
	<br><br>
	<h1>Total: Bs <?php echo number_format($total, 2, '.', ','); ?></h1>
	<?php

	?>
<?php else: ?>
	501 Internal Error
<?php endif; ?>