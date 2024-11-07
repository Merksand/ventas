<?php
$product = ProductData::getById($_GET["id"]);
$categories = CategoryData::getAll();

// echo "<pre>";
// print_r($product);
// echo "</pre>";

if ($product != null):
?>
  <div class="row">
    <div class="col-md-8">
      <h1><?php echo htmlspecialchars($product->nombre_producto); ?> <small>Editar Producto</small></h1>

      <?php if (isset($_COOKIE["prdupd"])): ?>
        <p class="alert alert-info">La información del producto se ha actualizado exitosamente.</p>
        <?php setcookie("prdupd", "", time() - 18600); ?>
      <?php endif; ?>

      <br><br>
      <form class="form-horizontal" method="post" id="editproduct" enctype="multipart/form-data" action="index.php?view=updateproduct" role="form">

        <!-- Imagen -->
        <div class="form-group">
          <label class="col-lg-3 control-label">Imagen*</label>
          <div class="col-md-8">
            <input type="file" name="image" id="image">
            <?php if ($product->imagen): ?>
              <br>
              <img src="storage/products/<?php echo htmlspecialchars($product->imagen); ?>" class="img-responsive" alt="Imagen del producto">
            <?php endif; ?>
          </div>
        </div>

        <!-- Código de Producto -->
        <div class="form-group">
          <label class="col-lg-3 control-label">Código de producto*</label>
          <div class="col-md-8">
            <input type="text" name="codigo_producto" class="form-control" id="codigo_producto" value="<?php echo htmlspecialchars($product->codigo_producto); ?>" placeholder="Código del producto">
          </div>
        </div>

        <!-- Nombre -->
        <div class="form-group">
          <label class="col-lg-3 control-label">Nombre*</label>
          <div class="col-md-8">
            <input type="text" name="nombre_producto" class="form-control" id="nombre_producto" value="<?php echo htmlspecialchars($product->nombre_producto); ?>" placeholder="Nombre del producto">
          </div>
        </div>

        <!-- Categoría -->
        <div class="form-group">
          <label class="col-lg-3 control-label">Categoría</label>
          <div class="col-md-8">
            <select name="id_categoria" class="form-control">
              <option value="">-- NINGUNA --</option>
              <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category->id; ?>" <?php if ($product->id_categoria == $category->id) echo "selected"; ?>>
                  <?php echo htmlspecialchars($category->name); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Descripción -->
        <div class="form-group">
          <label class="col-lg-3 control-label">Descripción</label>
          <div class="col-md-8">
            <textarea name="descripcion" class="form-control" id="descripcion" placeholder="Descripción del producto"><?php echo htmlspecialchars($product->descripcion); ?></textarea>
          </div>
        </div>

        <!-- Precio de Compra -->
        <div class="form-group">
          <label class="col-lg-3 control-label">Precio de Compra*</label>
          <div class="col-md-8">
            <input type="text" name="precio_compra" class="form-control" value="<?php echo htmlspecialchars($product->precio_compra); ?>" placeholder="Precio de compra">
          </div>
        </div>

        <!-- Precio de Venta -->
        <div class="form-group">
          <label class="col-lg-3 control-label">Precio de Venta*</label>
          <div class="col-md-8">
            <input type="text" name="precio_venta" class="form-control" value="<?php echo htmlspecialchars($product->precio_venta); ?>" placeholder="Precio de venta">
          </div>
        </div>

        <!-- Stock -->
        <div class="form-group">
          <label class="col-lg-3 control-label">Stock*</label>
          <div class="col-md-8">
            <input type="text" name="stock" class="form-control" value="<?php echo htmlspecialchars($product->stock); ?>" placeholder="Cantidad en inventario">
          </div>
        </div>

        <!-- Stock Mínimo -->
        <div class="form-group">
          <label class="col-lg-3 control-label">Stock Mínimo</label>
          <div class="col-md-8">
            <input type="text" name="stock_minimo" class="form-control" value="<?php echo htmlspecialchars($product->{16}); ?>" placeholder="Mínimo en inventario">
          </div>
        </div>

        <!-- Estado Activo -->
        <div class="form-group">
          <label class="col-lg-3 control-label">Está activo</label>
          <div class="col-md-8">
            <!-- Campo oculto que envía 0 si el checkbox está desmarcado -->
            <input type="hidden" name="is_active" value="0">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="is_active" value="1" <?php if ($product->is_active) echo "checked"; ?>>
              </label>
            </div>
          </div>
        </div>


        <!-- Botón de Actualización -->
        <div class="form-group">
          <div class="col-lg-offset-3 col-lg-8">
            <input type="hidden" name="product_id" value="<?php echo $product->id_producto; ?>">
            <button type="submit" class="btn btn-success">Actualizar Producto</button>
          </div>
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>