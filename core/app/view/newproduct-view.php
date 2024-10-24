<?php
$categories = CategoryData::getAll();
?>
<div class="row">
    <div class="col-md-12">
        <h1>Nuevo Producto</h1>
        <br>
        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="addproduct" action="index.php?view=addproduct" role="form">

            <div class="form-group">
                <label for="inputEmail1" class="col-lg-2 control-label">Imagen</label>
                <div class="col-md-6">
                    <input type="file" name="imagen" id="image" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail1" class="col-lg-2 control-label">Código de Producto*</label>
                <div class="col-md-6">
                    <input type="text" name="codigo_producto" required class="form-control" id="product_code" placeholder="Código del Producto">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
                <div class="col-md-6">
                    <input type="text" name="nombre_producto" required class="form-control" id="name" placeholder="Nombre del Producto">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail1" class="col-lg-2 control-label">Categoría</label>
                <div class="col-md-6">
                    <select name="id_categoria" class="form-control">
                        <option value="">-- NINGUNA --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail1" class="col-lg-2 control-label">Descripción</label>
                <div class="col-md-6">
                    <textarea name="descripcion" class="form-control" id="description" placeholder="Descripción del Producto"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail1" class="col-lg-2 control-label">Precio de Compra*</label>
                <div class="col-md-6">
                    <input type="text" name="precio_compra" required class="form-control" id="price_in" placeholder="Precio de Compra">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail1" class="col-lg-2 control-label">Precio de Venta*</label>
                <div class="col-md-6">
                    <input type="text" name="precio_venta" required class="form-control" id="price_out" placeholder="Precio de Venta">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail1" class="col-lg-2 control-label">Stock*</label>
                <div class="col-md-6">
                    <input type="text" name="stock" required class="form-control" id="stock" placeholder="Cantidad en Stock">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail1" class="col-lg-2 control-label">Stock Mínimo:</label>
                <div class="col-md-6">
                    <input type="text" name="stock_minimo" class="form-control" id="stock_minimo" placeholder="Stock Mínimo">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <button type="submit" class="btn btn-primary">Agregar Producto</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#product_code").keydown(function(e) {
            if (e.which == 17 || e.which == 74) {
                e.preventDefault();
            } else {
                console.log(e.which);
            }
        });
    });
</script>
