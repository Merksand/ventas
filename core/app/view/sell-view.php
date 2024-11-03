<div class="row">
    <div class="col-md-12">
        <h1>Venta</h1>
        <p><b>Buscar producto por nombre o por código:</b></p>
        <form id="searchp">
            <div class="row">
                <div class="col-md-6">
                    <input type="hidden" name="view" value="sell">
                    <input type="text" id="product_code" name="product" class="form-control" placeholder="Nombre o código de producto">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                </div>
            </div>
        </form>
    </div>
    <div id="show_search_results"></div>

    <script>
        $(document).ready(function() {
            $("#searchp").on("submit", function(e) {
                e.preventDefault();
                $.get("./?action=searchproduct", $("#searchp").serialize(), function(data) {
                    $("#show_search_results").html(data);
                });
                $("#product_code").val("");
            });

            $("#product_code").keydown(function(e) {
                if (e.which == 17 || e.which == 74) {
                    e.preventDefault();
                } else {
                    console.log(e.which);
                }
            });
        });
    </script>

    <?php if (isset($_SESSION["errors"])): ?>
        <h2>Errores</h2>
        <table class="table table-bordered table-hover">
            <tr class="danger">
                <th>Código</th>
                <th>Producto</th>
                <th>Mensaje</th>
            </tr>
            <?php foreach ($_SESSION["errors"] as $error):
                $product = ProductData::getById($error["product_id"]);
            ?>

			
                <tr class="danger">
                    <td><?php echo $product->codigo_producto; ?></td>
                    <td><?php echo $product->nombre_producto; ?></td>
                    <td><b><?php echo $error["message"]; ?></b></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php unset($_SESSION["errors"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["cart"])): 
        $total = 0;
    ?>
        <h2>
            
        </h2>
        <table class="table table-bordered table-hover">
            <thead>
                <th style="width:30px;">Código</th>
                <th style="width:30px;">Imagen</th>
                <th style="width:30px;">Cantidad</th>
                <th>Producto</th>
                <th style="width:30px;">Precio Unitario</th>
                <th style="width:30px;">Precio Total</th>
                <th></th>
            </thead>
            <?php
            $idUsuario= UserData::getById($_SESSION["user_id"])->id;
            ?>
            <?php foreach ($_SESSION["cart"] as $p): 
                $product = ProductData::getById($p["product_id"]);
               
                $idUsuario = UserData::getById($_SESSION["user_id"])->id;

                // $agregarAlmacen = ProductData::setVenta($p["product_id"],0,$idUsuario, $p["stock"], 0);
            ?>
			<?php 
				// echo "<pre>";
				// print_r($product);
				// echo "</pre>";
			?>
                <tr>
                    <td><?php echo $product->codigo_producto; ?></td>
					<td><img src="storage/products/<?php echo $product->imagen; ?>" width="40px" height="40px"></td>
                    <td><?php echo $p["stock"]; ?></td>
                    <td><?php echo $product->nombre_producto; ?></td>
                    <td><b>$ <?php echo number_format($product->precio_venta, 2); ?></b></td>
                    <td><b>$ <?php $pt = $product->precio_venta * $p["stock"]; $total += $pt; echo number_format($pt, 2); ?></b></td>
                    <td style="width:30px;"><a href="index.php?view=clearcart&product_id=<?php echo $product->id_producto; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <form method="post" class="form-horizontal" id="processsell" action="index.php?view=processsell">
            <h2>Resumen</h2>
            <div class="form-group">
                <label for="client_id" class="col-lg-2 control-label">Cliente</label>
                <div class="col-lg-10">
                    <select name="client_id" class="form-control">
                        <option value="">-- NINGUNO --</option>
                        <?php foreach (PersonData::getClients() as $client): ?>
                            <option value="<?php echo $client->id; ?>"><?php echo $client->nombre . " " . $client->apellido; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="discount" class="col-lg-2 control-label">Descuento</label>
                <div class="col-lg-10">
                    <input type="text" name="discount" class="form-control" value="0" id="discount" placeholder="Descuento">
                </div>
            </div>
            <div class="form-group">
                <label for="money" class="col-lg-2 control-label">Efectivo</label>
                <div class="col-lg-10">
                    <input type="text" name="money" class="form-control" id="money" placeholder="Efectivo">
                </div>
            </div>
            <input type="hidden" name="total" value="<?php echo $total; ?>">

            <table class="table table-bordered">
                <tr>
                    <td>Subtotal</td>
                    <td><b>$ <?php echo number_format($total * 0.84, 2); ?></b></td>
                </tr>
                <tr>
                    <td>IVA</td>
                    <td><b>$ <?php echo number_format($total * 0.16, 2); ?></b></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td><b>$ <?php echo number_format($total, 2); ?></b></td>
                </tr>
            </table>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <a href="index.php?view=clearcart" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
                    <button class="btn btn-lg btn-primary"><i class="glyphicon glyphicon-usd"></i> Finalizar Venta</button>
                </div>
            </div>
        </form>

        <script>
            $("#processsell").submit(function(e) {
                const discount = parseFloat($("#discount").val());
                const money = parseFloat($("#money").val());
                if (money < (<?php echo $total; ?> - discount)) {
                    alert("No se puede efectuar la operación");
                    e.preventDefault();
                } else {
                    const cambio = money - (<?php echo $total; ?> - discount);
                    if (!confirm("Cambio: $" + cambio.toFixed(2))) {
                        e.preventDefault();
                    }
                }
            });
        </script>
    <?php endif; ?>
</div>
