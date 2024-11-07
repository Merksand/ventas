<div class="row">
  <div class="col-md-12">
    <h1>Agregar Usuarios</h1>
    <br>
    <form class="form-horizontal" method="post" id="adduser" action="index.php?view=adduser" role="form">

      <!-- Campo para Nombre (tb_persona) -->
      <div class="form-group">
        <label for="nombre" class="col-lg-2 control-label">Nombre*</label>
        <div class="col-md-6">
          <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required>
        </div>
      </div>

      <!-- Campo para Apellido Paterno (tb_persona) -->
      <div class="form-group">
        <label for="apellido_paterno" class="col-lg-2 control-label">Apellido Paterno*</label>
        <div class="col-md-6">
          <input type="text" name="apellido_paterno" class="form-control" id="apellido_paterno" placeholder="Apellido Paterno" required>
        </div>
      </div>

      <!-- Campo para Apellido Materno (tb_persona) -->
      <div class="form-group">
        <label for="apellido_materno" class="col-lg-2 control-label">Apellido Materno</label>
        <div class="col-md-6">
          <input type="text" name="apellido_materno" class="form-control" id="apellido_materno" placeholder="Apellido Materno">
        </div>
      </div>

      <!-- Campo para Celular (tb_persona) -->
      <div class="form-group">
        <label for="celular" class="col-lg-2 control-label">Celular*</label>
        <div class="col-md-6">
          <input type="text" name="celular" class="form-control" id="celular" placeholder="Celular" required>
        </div>
      </div>

      <!-- Campo para Email (tb_persona) -->
      <div class="form-group">
        <label for="email" class="col-lg-2 control-label">Email*</label>
        <div class="col-md-6">
          <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
        </div>
      </div>

      <!-- Campo para Dirección (tb_persona) -->
      <div class="form-group">
        <label for="direccion" class="col-lg-2 control-label">Dirección*</label>
        <div class="col-md-6">
          <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Dirección" required>
        </div>
      </div>

      <!-- Nombre de usuario (tb_usuarios) -->
      <!-- <div class="form-group">
        <label for="username" class="col-lg-2 control-label">Nombre de usuario*</label>
        <div class="col-md-6">
          <input type="text" name="username" class="form-control" id="username" placeholder="Nombre de usuario" required>
        </div>
      </div> -->

      <!-- Campo para Contraseña (tb_usuarios) -->
      <div class="form-group">
        <label for="password" class="col-lg-2 control-label">Contraseña*</label>
        <div class="col-md-6">
          <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña" required>
        </div>
      </div>

 

      <!-- Select para Rol (tb_usuarios) -->
      <div class="form-group">
        <label for="rol" class="col-lg-2 control-label">Rol*</label>
        <div class="col-md-6">
          <select name="rol" class="form-control" required>
            <option value="">Seleccione un Rol</option>
            <?php
            // Obtener los roles
            $roles = PersonData::getRoles();
            
            foreach ($roles as $role) {
              echo "<option value='{$role['id_rol']}'>{$role['nombre_rol']}</option>";
            }
            ?>
          </select>
        </div>
      </div>

      <!-- Es Administrador (tb_usuarios) -->
      <!-- <div class="form-group">
        <label for="is_admin" class="col-lg-2 control-label">Es Administrador</label>
        <div class="col-md-6">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="is_admin">
            </label>
          </div>
        </div>
      </div> -->

      <p class="alert alert-info">* Campos obligatorios</p>

      <!-- Botón para agregar el usuario -->
      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <button type="submit" class="btn btn-primary">Agregar Usuario</button>
        </div>
      </div>
    </form>
  </div>
</div>