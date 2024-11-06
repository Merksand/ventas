<div class="row">
    <div class="col-md-12">
        <h1>Agregar Rol</h1>
        <br>
        <form class="form-horizontal" method="post" id="addrole" action="index.php?view=addrole" role="form">
            <div class="form-group">
                <label for="roleName" class="col-lg-2 control-label">Nombre del Rol*</label>
                <div class="col-md-6">
                    <input type="text" name="role_name" class="form-control" id="roleName" placeholder="Nombre del Rol" required>
                </div>
            </div>

            <p class="alert alert-info">* Campos obligatorios</p>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <button type="submit" class="btn btn-primary">Agregar Rol</button>
                </div>
            </div>
        </form>
    </div>
</div>