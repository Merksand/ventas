<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Formulario para descargar el reporte en PDF -->
			<form method="GET" action="report/inventaryTime-word.php">
				<input type="hidden" name="product_id" value="<?php echo isset($_GET['product_id']) ? $_GET['product_id'] : ''; ?>">
				<input type="hidden" name="sd" value="<?php echo isset($_GET['sd']) ? $_GET['sd'] : ''; ?>">
				<input type="hidden" name="ed" value="<?php echo isset($_GET['ed']) ? $_GET['ed'] : ''; ?>">
				<button type="submit" class="btn btn-primary pull-right" 
				        <?php echo (isset($_GET['sd']) && isset($_GET['ed']) && $_GET['sd'] != "" && $_GET['ed'] != "") ? '' : 'disabled'; ?>>
					<i class="fa fa-download"></i> Descargar Reporte en Pdf
				</button>
			</form>

			<h1>Reportes</h1>

			<!-- Formulario para procesar el rango de fechas -->
			<form method="GET">
				<input type="hidden" name="view" value="reports">
				<div class="row">
					<div class="col-md-3">
						<select name="product_id" class="form-control">
							<option value="">-- TODOS --</option>
							<?php foreach ($products as $p): ?>
								<option value="<?php echo $p->id_producto; ?>" 
								        <?php echo (isset($_GET["product_id"]) && $_GET["product_id"] == $p->id_producto) ? "selected" : ""; ?>>
									<?php echo $p->nombre_producto; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3">
						<input type="date" name="sd" value="<?php if (isset($_GET["sd"])) echo $_GET["sd"]; ?>" class="form-control">
					</div>
					<div class="col-md-3">
						<input type="date" name="ed" value="<?php if (isset($_GET["ed"])) echo $_GET["ed"]; ?>" class="form-control">
					</div>
					<div class="col-md-3">
						<input type="submit" class="btn btn-success btn-block" value="Procesar">
					</div>
				</div>
			</form>
		</div>
	</div>
	<br>

	<!-- Mostrar operaciones si existen -->
	<div class="row">
		<div class="col-md-12">
			<?php if (isset($_GET["sd"]) && isset($_GET["ed"])): ?>
				<?php if ($_GET["sd"] != "" && $_GET["ed"] != ""): ?>
					<?php
					$operations = array();

					if ($_GET["product_id"] == "") {
						$operations = OperationData::getProductsByDateAndOperation($_GET["sd"], $_GET["ed"]);
					} else {
						$operations = OperationData::getAllByDateOfficialBP($_GET["product_id"], $_GET["sd"], $_GET["ed"]);
					}
					?>

					<?php if (count($operations) > 0): ?>
						<table class="table table-bordered">
							<thead>
								<th>Producto</th>
								<th>Cantidad</th>
								<th>Operacion</th>
								<th>Fecha</th>
							</thead>
							<?php foreach ($operations as $operation): ?>
								<tr>
									<td><?php echo $operation->nombre_producto; ?></td>
									<td><?php echo $operation->stock_actual; ?></td>
									<td><?php echo ($operation->tipo_operacion == 'entrada') ? 'Compra' : 'Venta'; ?></td>
									<td><?php echo $operation->fyh_creacion; ?></td>
								</tr>
							<?php endforeach; ?>
						</table>
					<?php else: ?>
						<div class="jumbotron">
							<h2>No hay operaciones</h2>
							<p>El rango de fechas seleccionado no proporcionó ningún resultado de operaciones.</p>
						</div>
					<?php endif; ?>
				<?php else: ?>
					<div class="jumbotron">
						<h2>Fecha Incorrecta</h2>
						<p>Puede ser que no seleccionó un rango de fechas, o el rango seleccionado es incorrecto.</p>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
	<br><br><br><br>
</section>
