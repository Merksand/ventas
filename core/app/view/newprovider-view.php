<div class="row">
  <div class="col-md-12">
    <h1>Nuevo Proveedor</h1>
    <br>
    <form class="form-horizontal" method="post" id="addprovider" action="index.php?view=addprovider" role="form">
      
      <!-- Campo Nombre -->
      <div class="form-group">
        <label for="name" class="col-lg-2 control-label">Nombre*</label>
        <div class="col-md-6">
          <input type="text" name="name" class="form-control" id="name" placeholder="Nombre" required>
        </div>
      </div>
      
      <!-- Campo Apellido Paterno -->
      <div class="form-group">
        <label for="lastname" class="col-lg-2 control-label">Apellido Paterno</label>
        <div class="col-md-6">
          <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Apellido" required>
        </div>
      </div>
      
      <!-- Campo Apellido Materno -->
      <div class="form-group">
        <label for="lastname2" class="col-lg-2 control-label">Apellido Materno</label>
        <div class="col-md-6">
          <input type="text" name="lastname2" class="form-control" id="lastname2" placeholder="Apellido Materno">
        </div>
      </div>
      
      <!-- Campo Dirección -->
      <div class="form-group">
        <label for="address1" class="col-lg-2 control-label">Dirección*</label>
        <div class="col-md-6">
          <input type="text" name="address" class="form-control" id="address1" placeholder="Dirección" required>
        </div>
      </div>
      
      <!-- Campo Email -->
      <div class="form-group">
        <label for="email1" class="col-lg-2 control-label">Email*</label>
        <div class="col-md-6">
          <input type="email" name="email" class="form-control" id="email1" placeholder="Email" required>
        </div>
      </div>
      
      <!-- Campo Teléfono -->
      <div class="form-group">
        <label for="phone1" class="col-lg-2 control-label">Teléfono*</label>
        <div class="col-md-6">
          <input type="tel" name="phone" class="form-control" id="phone1" placeholder="Teléfono" required>
        </div>
      </div>

      <!-- Campo Nombre de la Empresa -->
      <div class="form-group">
        <label for="empresa" class="col-lg-2 control-label">Nombre de la Empresa*</label>
        <div class="col-md-6">
          <input type="text" name="empresa" class="form-control" id="empresa" placeholder="Nombre de la Empresa" required>
        </div>
      </div>
      
      <!-- Campo NIT -->
      <div class="form-group">
        <label for="NIT" class="col-lg-2 control-label">NIT*</label>
        <div class="col-md-6">
          <input type="text" name="NIT" class="form-control" id="NIT" placeholder="NIT" required>
        </div>
      </div>

      <p class="alert alert-info">* Campos obligatorios</p>

      <!-- Botón para enviar el formulario -->
      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <button type="submit" class="btn btn-primary">Agregar Proveedor</button>
        </div>
      </div>

    </form>
  </div>
</div>
