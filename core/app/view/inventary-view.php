<div class="row">
	<div class="col-md-12">
		<!-- Botón de descarga -->
		<div class="btn-group pull-right">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-download"></i> Descargar <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li><a href="report/inventary-word.php">Word 2007 (.docx)</a></li>
			</ul>
		</div>

		<h1><i class="glyphicon glyphicon-stats"></i> Inventario de Productos</h1>
		<div class="clearfix"></div>

		<?php
		$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
		$limit = isset($_GET["limit"]) && $_GET["limit"] != "" ? (int)$_GET["limit"] : 10;
		$products = ProductData::getAll();

		if (count($products) > 0) {
			$npaginas = ceil(count($products) / $limit);
			$start_index = ($page - 1) * $limit;
			$curr_products = array_slice($products, $start_index, $limit);
		?>

			<h3>Página <?php echo $page . " de " . $npaginas; ?></h3>
			<div class="btn-group pull-right">
				<?php if ($page > 1): ?>
					<a class="btn btn-sm btn-default" href="<?php echo "index.php?view=inventary&limit=$limit&page=" . ($page - 1); ?>"><i class="glyphicon glyphicon-chevron-left"></i> Atrás</a>
				<?php endif; ?>

				<?php if ($page < $npaginas): ?>
					<a class="btn btn-sm btn-default" href="<?php echo "index.php?view=inventary&limit=$limit&page=" . ($page + 1); ?>">Adelante <i class="glyphicon glyphicon-chevron-right"></i></a>
				<?php endif; ?>
			</div>

			<div class="clearfix"></div>
			<br>
			<table class="table table-bordered table-hover">
				<thead>
					<th>Código</th>
					<th>Nombre</th>
					<th>Disponible</th>
					<th></th>
				</thead>
				<?php foreach ($curr_products as $product):
					// $q = OperationData::getQYesF($product->id_producto);

					// echo "<pre>";
					// print_r($q);
					// echo "</pre>";


				?>
					<tr class="<?php echo ($product->stock <= $product->stock_minimo / 2) ? 'danger' : (($product->stock <= $product->stock_minimo) ? 'warning' : ''); ?>">
						<td><?php echo $product->id_producto; ?></td>
						<td><?php echo $product->nombre_producto; ?></td>
						<td><?php echo $product->stock; ?></td>
						<td style="width:93px;">
							<a href="index.php?view=history&product_id=<?php echo $product->id_producto; ?>" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-time"></i> Historial</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>

			<!-- Botones de paginación -->
			<div class="btn-group pull-right">
				<?php for ($i = 1; $i <= $npaginas; $i++): ?>
					<a href="index.php?view=inventary&limit=<?php echo $limit; ?>&page=<?php echo $i; ?>" class="btn btn-default btn-sm"><?php echo $i; ?></a>
				<?php endfor; ?>
			</div>

			<!-- Formulario de selección de límite de productos por página -->
			<form class="form-inline" method="get" action="index.php">
				<input type="hidden" name="view" value="inventary">
				<label for="limit">Límite</label>
				<input type="number" value="<?php echo $limit; ?>" name="limit" style="width:60px;" class="form-control">
				<button type="submit" class="btn btn-primary">Aplicar</button>
			</form>

			<div class="clearfix"></div>

		<?php
		} else {
		?>
			<div class="jumbotron">
				<h2>No hay productos</h2>
				<p>No se han agregado productos a la base de datos, puedes agregar uno dando click en el botón <b>"Agregar Producto"</b>.</p>
			</div>
		<?php
		}
		?>
		<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>