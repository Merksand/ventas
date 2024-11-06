<?php if (isset($_GET["product"]) && $_GET["product"] != ""): ?>
    <?php
    echo "Productazon: " . $_GET["product"];
    $products = ProductData::getLike($_GET["product"]);

    echo "<pre>";
    print_r($products);
    echo "</pre>";
    if (count($products) > 0) {
    ?>
        <h3>Resultados de la Búsquedssa</h3>
        <table class="table table-bordered table-hover">
            <thead>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio unitarioz</th>
                <th>En inventario</th>
                <th>CantidadP</th>
            </thead>
            <?php
            $products_in_cero = 0;
            foreach ($products as $product):
                $q = OperationData::getQYesF($product->id_producto);
              
            ?>
                <?php if ($q > 0): ?>
                    <tr class="<?php echo ($q <= $product->stock_minimo) ? "danger" : ""; ?>">
                        <td style="width:80px;"><?php echo $product->codigo_producto; ?></td>
                        <td><?php echo $product->nombre_producto; ?></td>
                        <td><b>Bs<?php echo $product->precio_venta; ?></b></td>
                        <td><?php echo $product->stock_actual; ?></td>
                        <td style="width:250px;">
                            <form method="post" action="index.php?view=addtocart">
                                <input type="hidden" name="product_id" value="<?php echo $product->id_producto; ?>">
                                <div class="input-group">
                                    <input type="number" class="form-control" required name="q" placeholder="Cantidad ...">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="glyphicon glyphicon-plus-sign"></i> Agregar
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php else: $products_in_cero++; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
        <?php if ($products_in_cero > 0) {
            echo "<p class='alert alert-warning'>Se omitieron <b>$products_in_cero productos</b> que no tienen existencias en el inventario. <a href='index.php?module=inventary'>Ir al Inventario</a></p>";
        } ?>
    <?php
    } else {
        echo "<br><p class='alert alert-danger'>No se encontró el producto</p>";
    }
    ?>
    <hr><br>
<?php endif; ?>
