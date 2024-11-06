<div class="row">
	<div class="col-md-12">
		<a href="index.php?view=newuser" class="btn btn-default pull-right"><i class='glyphicon glyphicon-user'></i> Nuevo Usuario</a>
		<h1>Lista de Usuarios</h1>
		<br>

		<?php

		$users = UserData::getAll();

		if (count($users) > 0) {
			// si hay usuarios
		?>
			<table class="table table-bordered table-hover">
				<thead>
					<th>Nombre completo</th>
					<th>Rol</th>
					<th>Email</th>
					<th>Activo</th>
					<!-- <th>Admin</th> -->
					<th></th>
				</thead>
				<?php
				foreach ($users as $user) {
				?>
					<tr>
						<td><?php echo $user->nombre . " " . $user->apellido_paterno . " " . $user->apellido_materno; ?></td>
						<td><?php echo $user->nombre_rol; ?></td>
						<td><?php echo $user->email; ?></td>
						<td>
							<?php if ($user->{13}): ?>
								<i class="glyphicon glyphicon-ok"></i>
							<?php endif; ?>
						</td>
						<!-- <td>
					<?php if ($user->is_admin): ?>
						<i class="glyphicon glyphicon-ok"></i>
					<?php endif; ?>
				</td> -->
						<td style="width:30px;"><a href="index.php?view=edituser&id=<?php echo $user->id_persona; ?>" class="btn btn-warning btn-xs">Editar</a></td>
					</tr>
			<?php

				}
				echo "</table>";
			} else {
				// no hay usuarios
			}


			?>


	</div>
</div>