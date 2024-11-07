<div class="row">
    <div class="col-md-12">
        <div class="btn-group pull-right">
            <a href="index.php?view=newproduct" class="btn btn-default">Agregar Producto</a>
            
            <!-- Menú desplegable para seleccionar productos activos o inactivos -->
            <div class="btn-group pull-right">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-filter"></i> Filtrar Productos <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="index.php?view=products&status=active">Mostrar Activos</a></li>
                    <li><a href="index.php?view=products&status=inactive">Mostrar Inactivos</a></li>
                </ul>
            </div>

            <div class="btn-group pull-right">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="report/products-word.php">Word 2007 (.docx)</a></li>
                </ul>
            </div>
        </div>

        <h1>Lista de Productos</h1>
        <div class="clearfix"></div>

        <?php
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $limit = isset($_GET["limit"]) ? $_GET["limit"] : 10;
        $status = isset($_GET["status"]) ? $_GET["status"] : 'active';

        // Obtener productos en función del filtro de estado
        $products = ($status === 'inactive') ? ProductData::getInactiveProducts() : ProductData::getActiveProducts();

        if (count($products) > 0) {
            $npaginas = ceil(count($products) / $limit);
            $offset = ($page - 1) * $limit;

            $curr_products = array_slice($products, $offset, $limit);

            echo "<h3>Página $page de $npaginas</h3>";
            ?>
            
            <table class="table table-bordered table-hover">
                <thead>
                    <th>Código</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Precio Entrada</th>
                    <th>Precio Salida</th>
                    <th>Categoría</th>
                    <th>Stock Actual</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </thead>
                <?php foreach ($curr_products as $product): ?>
                    <tr>
                        <td><?php echo $product->codigo_producto; ?></td>
                        <td>
                            <?php if ($product->imagen): ?>
                                <img src="storage/products/<?php echo $product->imagen; ?>" style="width:64px;">
                            <?php endif; ?>
                        </td>
                        <td><?php echo $product->nombre_producto; ?></td>
                        <td>Bs <?php echo number_format($product->precio_compra, 2); ?></td>
                        <td>Bs <?php echo number_format($product->precio_venta, 2); ?></td>
                        <td><?php echo $product->getCategory()->name ?? "<center>----</center>"; ?></td>
                        <td><?php echo $product->stock; ?></td>
                        <td><?php echo $product->is_active ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?></td>
                        <td>
                            <a href="index.php?view=editproduct&id=<?php echo $product->id_producto; ?>" class="btn btn-xs btn-warning">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </a>
                            <a href="index.php?view=delproduct&id=<?php echo $product->id_producto; ?>" class="btn btn-xs btn-danger">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="btn-group pull-right">
                <?php
                for ($i = 1; $i <= $npaginas; $i++) {
                    $activeClass = ($i == $page) ? 'btn-primary' : 'btn-default';
                    echo "<a href='index.php?view=products&status=$status&limit=$limit&page=$i' class='btn btn-sm $activeClass'>$i</a> ";
                }
                ?>
            </div>
            <form class="form-inline" method="get" action="index.php">
                <input type="hidden" name="view" value="products">
                <label for="limit">Límite:</label>
                <input type="number" value="<?php echo $limit; ?>" name="limit" style="width:60px;" class="form-control" min="1">
                <input type="hidden" name="status" value="<?php echo $status; ?>">
                <button type="submit" class="btn btn-default">Actualizar</button>
            </form>
            <div class="clearfix"></div>
        <?php
        } else {
            ?>
            <div class="jumbotron">
                <h2>No hay productos</h2>
                <p>No se han agregado productos a la base de datos.</p>
            </div>
        <?php
        }
        ?>
        <br><br><br><br><br><br><br><br><br><br>
    </div>
</div>
