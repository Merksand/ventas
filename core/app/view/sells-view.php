<div class="row">
    <div class="col-md-12">
        <h1><i class='glyphicon glyphicon-shopping-cart'></i> Lista de Ventas</h1>
        <div class="clearfix"></div>

        <?php
        $products = SellData::getSells();

        if (count($products) > 0) {
        ?>
            <br>
            <table class="table table-bordered table-hover">
                <thead>
                    <th></th>
                    <th>Venta ID</th>
                    <th>Cantidad Total</th>
                    <th>Total Venta</th>
                    <th>Fecha</th>
                    <th></th>
                </thead>
                <?php foreach ($products as $sell): ?>
                    <tr>
                        <td style="width:30px;">
                            <a href="index.php?view=onesell&id=<?php echo $sell->id_venta; ?>" class="btn btn-xs btn-default">
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </a>
                        </td>

                        <td><?php echo $sell->id_venta; ?></td>

                        <td>
                            <?php
                            // Mostrar la cantidad total de productos vendidos en esta venta
                            echo "<b>" . number_format($sell->cantidad_total) . "</b>";
                            ?>
                        </td>

                        <td>
                            <?php
                            // Mostrar el total de la venta
                            echo "<b>Bs " . number_format($sell->total_venta, 2) . "</b>";
                            ?>
                        </td>
                        <td><?php echo $sell->fecha_venta; ?></td>
                        <td style="width:30px;">
                            <a href="index.php?view=delsell&id=<?php echo $sell->id_venta; ?>" class="btn btn-xs btn-danger">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="clearfix"></div>

        <?php
        } else {
        ?>
            <div class="jumbotron">
                <h2>No hay ventas</h2>
                <p>No se ha realizado ninguna venta.</p>
            </div>
        <?php
        }
        ?>
        <br><br><br><br><br><br><br><br><br><br>
    </div>
</div>
