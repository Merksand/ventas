<div class="row">
	<div class="col-md-12">
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Reabastecimientos</h1>
		<div class="clearfix"></div>


		<?php
		$products = SellData::getRes();

		// echo "<pre>";
		// print_r($products);
		// echo "</pre>";

		if (count($products) > 0) {
		?>
			<br>
			<table class="table table-bordered table-hover	">
				<thead>
					<th></th>
					<th>Cantidad de Productos</th>
					<th>Total</th>
					<th>Fecha</th>
					<th></th>
				</thead>
				<?php foreach ($products as $sell): ?>

					<tr>
						<td style="width:30px;"><a href="index.php?view=onere&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td>

						<td>

							<?php
							// echo "Id de sell ". $sell->id. "<br>";
							$operations = OperationData::getAllProductsByBuyId($sell->id);
							echo count($operations);
							?>
						<td>

							<?php
							$total = 0;
							foreach ($operations as $operation) {
								// echo "<pre>";
								// print_r($operation);
								// echo "</pre>";
								// echo "Total ". $operation->cantidad * $operation->precio_unitario . "<br>";

								$product  = $operation->getProduct($operation->id_producto);
								$total += $operation->cantidad * $operation->precio_unitario;
							}
							echo "<b>Bs " . number_format($total) . "</b>";

							?>

						</td>
						<td><?php echo $sell->created_at; ?></td>
						<td style="width:30px;"><a href="index.php?view=delre&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a></td>
					</tr>

				<?php endforeach; ?>

			</table>


		<?php
		} else {
		?>
			<div class="jumbotron">
				<h2>No hay datos</h2>
				<p>No se ha realizado ninguna operacion.</p>
			</div>
		<?php
		}

		?>
		<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>