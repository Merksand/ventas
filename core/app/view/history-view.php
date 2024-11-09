<?php
if (isset($_GET["product_id"])):
	$product = ProductData::getById($_GET["product_id"]);

	// echo "<pre>";
	// print_r($product);
	// echo "</pre>";

	$operations = OperationData::getAllInventaryByProductId($product->id_producto);
?>
	<div class="row">
		<div class="col-md-12">
			<div class="btn-group pull-right">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-download"></i> Descargar <span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li><a href="report/history-word.php?id=<?php echo $product->id_producto; ?>">Word 2007 (.docx)</a></li>

				</ul>
			</div>
			<h1><?php echo $product->nombre_producto; ?> <small>Historial</small></h1>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?php
			$itotal = OperationData::GetInputQProduct($product->id_producto);
			?>
			<div class="jumbotron">
				<center>
					<h2>Entradas</h2>
					<h1><?php echo $itotal; ?></h1>
				</center>
			</div>
			<br>
			<?php
			?>
		</div>
		<div class="col-md-4">
			<?php
			// $total = OperationData::GetQYesF($product->id_producto);
			$total = ProductData::getAllProductById($product->id_producto);

			// echo "<pre>";
			// print_r($total);
			// echo "</pre>";
			?>
			<div class="jumbotron">
				<center>
					<h2>Disponibles</h2>
					<h1><?php echo $total->stock; ?></h1>
				</center>
			</div>
			<div class="clearfix"></div>
			<br>
			<?php
			?>
		</div>
		<div class="col-md-4">
			<?php
			$ototal =  OperationData::GetOutputQProduct($product->id_producto);
			?>
			<div class="jumbotron">
				<center>
					<h2>Salidas</h2>
					<h1><?php echo $ototal; ?></h1>
				</center>
			</div>
			<div class="clearfix"></div>
			<br>
			<?php
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<?php if (count($operations) > 0): ?>
				<table class="table table-bordered table-hover">
					<thead>
						<th></th>
						<th>Cantidad</th>
						<th>Tipo</th>
						<th>Fecha</th>
						<th></th>
					</thead>
					<?php foreach ($operations as $operation): ?>
						<?php
						// echo "<pre>";
						// print_r($operation);
						// echo "</pre>";
						?>
						<tr>
							<td></td>
							<td><?php echo $operation->stock_actual; ?></td>
							<td><?php echo $operation->tipo_operacion; ?></td>
							<td><?php echo $operation->fyh_creacion; ?></td>
							<td style="width:40px;">
								<a href="#" id="oper-<?php echo $operation->id_almacen; ?>" class="btn tip btn-xs btn-danger" title="Eliminar">
									<i class="glyphicon glyphicon-trash"></i>
								</a>
							</td>
							<script>
								$("#oper-<?php echo $operation->id_almacen; ?>").click(function() {
									const confirmDelete = confirm("¿Estás seguro de que quieres eliminar esto?");
									if (confirmDelete) {
										window.location = "index.php?view=deleteoperation&ref=history&pid=<?php echo $operation->id_producto; ?>&opid=<?php echo $operation->id_almacen; ?>";
									}
								});
							</script>

						</tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>