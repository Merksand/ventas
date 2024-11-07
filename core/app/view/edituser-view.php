<?php $user = UserData::getById($_GET["id"]); ?>

<?php 

  // echo "<pre>";
  // print_r($user);
  // echo "</pre>";
?>
<div class="row">
  <div class="col-md-12">
    <h1>Editar Usuario</h1>
    <br>
    <form class="form-horizontal" method="post" id="addproduct" action="index.php?view=updateuser" role="form">

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
        <div class="col-md-6">
          <input type="hidden" name="id_persona" value="<?php echo $user->usuario_id_persona; ?>">
          <input type="text" name="name" value="<?php echo $user->persona_nombre; ?>" class="form-control" id="name" placeholder="Nombre">
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Apellido Paterno</label>
        <div class="col-md-6">
          <input type="text" name="lastname" value="<?php echo $user->persona_apellido_paterno; ?>" required class="form-control" id="lastname" placeholder="Apellido">
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Apellido Materno</label>
        <div class="col-md-6">
          <input type="text" name="lastname2" value="<?php echo $user->persona_apellido_materno; ?>" required class="form-control" id="lastname" placeholder="Apellido">
        </div>
      </div>


      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Rol*</label>
        <div class="col-md-6">
          <select name="rol" class="form-control" id="rol" required>
            <?php
            // Obtener los roles desde la base de datos
            $roles = PersonData::getRoles();
            foreach ($roles as $rol) {
              // Si el rol del usuario es el mismo que el del iterador, seleccionarlo
              $selected = ($user->rol_id == $rol->id) ? "selected" : "";
              // echo "<option value='" . $rol->id . "' $selected>" . $rol->nombre_rol . "</option>";
              echo "<option value='{$rol['id_rol']}'>{$rol['nombre_rol']}</option>";
            }
            ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Email*</label>
        <div class="col-md-6">
          <input type="text" name="email" value="<?php echo $user->persona_email; ?>" class="form-control" id="email" placeholder="Email">
        </div>
      </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Telefono*</label>
        <div class="col-md-6">
          <input type="text" name="telefono" value="<?php echo $user->persona_celular; ?>" class="form-control" id="email" placeholder="Email">
        </div>
      </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Dirección*</label>
        <div class="col-md-6">
          <input type="text" name="direccion" value="<?php echo $user->persona_direccion; ?>" class="form-control" id="email" placeholder="Email">
        </div>
      </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Contrase&ntilde;a</label>
        <div class="col-md-6">
          <input type="password" name="password" class="form-control" id="inputEmail1" placeholder="Contrase&ntilde;a">
          <p class="help-block">La contrase&ntilde;a solo se modificara si escribes algo, en caso contrario no se modifica.</p>
        </div>
      </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Esta activo</label>
        <div class="col-md-6">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="is_active" <?php if ($user->is_active) {
                                                        echo "checked";
                                                      } ?>>
            </label>
          </div>
        </div>
      </div>


      <!-- <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Es administrador</label>
        <div class="col-md-6">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="is_admin" <?php if ($user->is_admin) {
                                                        echo "checked";
                                                      } ?>>
            </label>
          </div>
        </div>
      </div> -->

      <p class="alert alert-info">* Campos obligatorios</p>

      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <input type="hidden" name="user_id" value="<?php echo $user->usuario_id_persona; ?>">
          <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
        </div>
      </div>
    </form>
  </div>
</div>