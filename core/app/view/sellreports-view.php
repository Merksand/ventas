<?php
$clients = PersonData::getClients();
?>

<section class="content">
	<div class="row">
		<div class="row">
			<div class="col-md-12">
				<a href="report/reporte-ventas-word.php?client_id=<?php echo $_GET["client_id"] ?? ''; ?>&sd=<?php echo $_GET["sd"] ?? ''; ?>&ed=<?php echo $_GET["ed"] ?? ''; ?>" class="btn btn-primary">
					<i class="fa fa-download"></i> Descargar en Pdf
				</a>
			</div>
		</div>
		<div class="col-md-12">
			<h1>Reportes de Ventas</h1>



			<form>
				<input type="hidden" name="view" value="sellreports">
				<div class="row">
					<div class="col-md-3">
						<select name="client_id" class="form-control">
							<option value="">-- TODOS --</option>
							<?php foreach ($clients as $p): ?>
								<?php $idCliente = SellData::getPersonClientById($p->id); ?>
								<option value="<?php echo $idCliente->id_cliente; ?>" <?php echo (isset($_GET["client_id"]) && $_GET["client_id"] == $idCliente->id_cliente) ? "selected" : ""; ?>>
									<?php echo $p->name. " " . $p->lastname; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3">
						<input type="date" name="sd" value="<?php echo $_GET["sd"] ?? ''; ?>" class="form-control">
					</div>
					<div class="col-md-3">
						<input type="date" name="ed" value="<?php echo $_GET["ed"] ?? ''; ?>" class="form-control">
					</div>
					<div class="col-md-3">
						<input type="submit" class="btn btn-success btn-block" value="Procesar">
					</div>
				</div>
			</form>
		</div>
	</div>

	<br>
	<div class="row">
		<div class="col-md-12">
			<?php if (isset($_GET["sd"], $_GET["ed"])): ?>
				<?php if ($_GET["sd"] && $_GET["ed"]): ?>
					<?php
					$operations = $_GET["client_id"] == ""
						? SellData::getAllByDateOp($_GET["sd"], $_GET["ed"])
						: SellData::getAllByDateBCOp($_GET["client_id"], $_GET["sd"], $_GET["ed"]);
					?>

					<?php if (count($operations) > 0): ?>
						<?php $supertotal = 0; ?>
						<table class="table table-bordered">
							<thead>
								<th>Id</th>
								<th>Total</th>
								<th>Fecha</th>
							</thead>
							<?php foreach ($operations as $operation): ?>
								<tr>
									<td><?php echo $operation->id_venta; ?></td>
									<td>Bs <?php echo number_format($operation->total_venta, 2, '.', ','); ?></td>
									<td><?php echo $operation->fecha_venta; ?></td>
								</tr>
								<?php $supertotal += $operation->total_venta; ?>
							<?php endforeach; ?>
						</table>
						<h1>Total de ventas: Bs <?php echo number_format($supertotal, 2, '.', ','); ?></h1>

					<?php else: ?>
						<div class="jumbotron">
							<h2>No hay operaciones</h2>
							<p>El rango de fechas seleccionado no proporcionó ningún resultado de operaciones.</p>
						</div>
					<?php endif; ?>
				<?php else: ?>
					<div class="jumbotron">
						<h2>Fechas Incorrectas</h2>
						<p>Puede ser que no seleccionaste un rango de fechas o el rango seleccionado es incorrecto.</p>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>

	<br><br><br><br>
</section>