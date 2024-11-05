<?php $user = PersonData::getProviderById($_GET["id"]); ?>

<?php
// echo $_GET["id"];
// echo "<pre>";
// print_r($user);
// echo "</pre>";
?>
<div class="row">
  <div class="col-md-12">
    <h1>Editar Proveedor</h1>
    <br>
    <form class="form-horizontal" method="post" id="addproduct" action="index.php?view=updateprovider" role="form">


      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
        <div class="col-md-6">
          <input type="text" name="name" value="<?php echo $user->name; ?>" class="form-control" id="name" placeholder="Nombre">
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Apellido Paterno</label>
        <div class="col-md-6">
          <input type="text" name="lastname" value="<?php echo $user->lastname; ?>" required class="form-control" id="lastname" placeholder="Apellido Paterno">
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Apellido Materno</label>
        <div class="col-md-6">
          <input type="text" name="lastname2" value="<?php echo $user->lastname2; ?>" required class="form-control" id="lastname" placeholder="Apellido Materno">
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Direccion*</label>
        <div class="col-md-6">
          <input type="text" name="address" value="<?php echo $user->address; ?>" class="form-control" required id="username" placeholder="Direccion">
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Email*</label>
        <div class="col-md-6">
          <input type="text" name="email" value="<?php echo $user->email; ?>" class="form-control" id="email" placeholder="Email">
        </div>
      </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Telefono</label>
        <div class="col-md-6">
          <input type="text" name="phone" value="<?php echo $user->phone; ?>" class="form-control" id="inputEmail1" placeholder="Telefono">
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Empresa</label>
        <div class="col-md-6">
          <input type="text" name="empresa" value="<?php echo $user->empresa; ?>" class="form-control" id="inputEmail1" placeholder="Nombre de la empresa">
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">NIT</label>
        <div class="col-md-6">
          <input type="text" name="NIT" value="<?php echo $user->NIT; ?>" class="form-control" id="inputEmail1" placeholder="NIT">
        </div>
      </div>


      <p class="alert alert-info">* Campos obligatorios</p>

      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
          <button type="submit" class="btn btn-primary">Actualizar Proveedor</button>
        </div>
      </div>
    </form>
  </div>
</div>