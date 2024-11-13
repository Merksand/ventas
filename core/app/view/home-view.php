	<?php
  $found = true;
  $products = ProductData::getAll();
  foreach ($products as $product) {
    // echo "<pre>";
    // print_r($products);
    // echo "</pre>";
    $q = OperationData::getQYesF($product->id_producto);
    if ($q <= $product->stock_minimo) {
      $found = true;
      break;
    }
  }
  ?>
	<div class="row">
	  <div class="col-md-12">
	    <h1>Bienvenido a Electro Lite</h1>
	  </div>
	</div>
	<div class="row">
	  <div class="col-lg-3 col-xs-6">
	    <!-- small box -->
	    <div class="small-box bg-aqua">
	      <div class="inner">
	        <h3><?php echo count(ProductData::getAll()); ?></h3>

	        <p>Productos</p>
	      </div>
	      <div class="icon">
	        <i class="ion ion-bag"></i>
	      </div>
	      <a href="./?view=products" class="small-box-footer">Ver mas <i class="fa fa-arrow-circle-right"></i></a>
	    </div>
	  </div>
	  <!-- ./col -->
	  <div class="col-lg-3 col-xs-6">
	    <!-- small box -->
	    <div class="small-box bg-purple">
	      <div class="inner">
	        <h3><?php echo count(PersonData::getClients()); ?></h3>

	        <p>Clientes</p>
	      </div>
	      <div class="icon">
	        <i class="ion ion-stats-bars"></i>
	      </div>
	      <a href="./?view=clients" class="small-box-footer">Ver mas <i class="fa fa-arrow-circle-right"></i></a>
	    </div>
	  </div>
	  <!-- ./col -->
	  <div class="col-lg-3 col-xs-6">
	    <!-- small box -->
	    <div class="small-box bg-yellow">
	      <div class="inner">
	        <h3><?php echo count(PersonData::getProviders()); ?></h3>

	        <p>Proveedores</p>
	      </div>
	      <div class="icon">
	        <i class="ion ion-person-add"></i>
	      </div>
	      <a href="./?view=providers" class="small-box-footer">Ver mas <i class="fa fa-arrow-circle-right"></i></a>
	    </div>
	  </div>
	  <!-- ./col -->
	  <div class="col-lg-3 col-xs-6">
	    <!-- small box -->
	    <div class="small-box bg-red">
	      <div class="inner">
	        <h3><?php echo count(CategoryData::getAll()); ?></h3>

	        <p>Categorias</p>
	      </div>
	      <div class="icon">
	        <i class="ion ion-pie-graph"></i>
	      </div>
	      <a href="./?view=categories" class="small-box-footer">Ver mas <i class="fa fa-arrow-circle-right"></i></a>
	    </div>
	  </div>
	  <!-- ./col -->
	</div>
	<!-- /.row -->

	<div class="row">
	  <!-- <div class="col-md-12">
	    <?php if ($found): ?>
	      <div class="btn-group pull-right">
	        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
	          <i class="fa fa-download"></i> Descargar <span class="caret"></span>
	        </button>
	        <ul class="dropdown-menu" role="menu">
	          <li><a href="report/alerts-word.php">Pdf</a></li>
	        </ul>
	      </div>
	    <?php endif; ?>

	  </div> -->
	  <div class="clearfix"></div>
	  <?php if (count($products) > 0) { ?>
	    <br>
	    <table class="table table-bordered table-hover">
	      <thead>
	        <th>Codigo</th>
	        <th>Nombre del producto</th>
	        <th>En Stock</th>
	        <th></th>
	      </thead>
	      <?php
        foreach ($products as $product):
        //   $q = OperationData::getQYesF($product->id_producto);
		//   echo "<pre>";
		//   print_r($product);
		//   echo "</pre>";
        ?>
	        <?php if ($product->stock <= $product->stock_minimo): ?>
	          <tr class="<?php if ($q == 0) {
                          echo "danger";
                        } else if ($q <= $product->stock_minimo / 2) {
                          echo "danger";
                        } else if ($q <= $product->stock_minimo) {
                          echo "warning";
                        } ?>">
	            <td><?php echo $product->id_producto; ?></td>
	            <td><?php echo $product->nombre_producto; ?></td>
	            <td><?php echo $product->stock; ?></td>
	            <td>
	              <?php if ($product->stock == 0) {
                  echo "<span class='label label-danger'>No hay existencias.</span>";
                } else if ($product->stock <= $product->stock_minimo / 2) {
                  echo "<span class='label label-danger'>Quedan muy pocas existencias.</span>";
                } else if ($product->stock <= $product->stock_minimo) {
                  echo "<span class='label label-warning'>Quedan pocas existencias.</span>";
                } ?>
	            </td>
	          </tr>
	        <?php endif; ?>
	      <?php
        endforeach;
        ?>
	    </table>

	    <div class="clearfix"></div>

	  <?php
    } else {
    ?>
	    <div class="jumbotron">
	      <h2>No hay alertas</h2>
	      <p>Por el momento no hay alertas de inventario, estas se muestran cuando el inventario ha alcanzado el nivel minimo.</p>
	    </div>
	  <?php
    }

    ?>
	  <br><br><br><br><br><br><br><br><br><br>
	</div>
	</div>