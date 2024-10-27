<div class="row">
    <div class="col-md-12">
        <h1>Venta</h1>
        <p><b>Buscar producto por nombre o por código:</b></p>
        <form id="searchp">
            <div class="row">
                <div class="col-md-6">
                    <input type="hidden" name="view" value="sell">
                    <input type="text" id="product_code" name="product" class="form-control" placeholder="Ingrese nombre o código">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                </div>
            </div>
        </form>
    </div>
    <div id="show_search_results"></div>

    <script>
        $(document).ready(function () {
            // Enviar formulario de búsqueda de productos
            $("#searchp").on("submit", function (e) {
                e.preventDefault();
                $.get("./?action=searchproduct", $("#searchp").serialize(), function (data) {
                    $("#show_search_results").html(data);
                });
                $("#product_code").val("");
            });

            // Evitar teclas no deseadas en el campo de producto
            $("#product_code").keydown(function (e) {
                if (e.which === 17 || e.which === 74) {
                    e.preventDefault();
                }
            });
        });
    </script>

    <?php if (isset($_SESSION["errors"])): ?>
        <h2>Errores</h2>
        <table class="table table-bordered table-hover">
            <tr class="danger">
                <th>Codigo</th>
                <th>Producto</th>
                <th>Mensaje</th>
            </tr>
            <?php foreach ($_SESSION["errors"] as $error): 
                $product = ProductData::getById($error["product_id"]);
            ?>
                <tr class="danger">
                    <td><?php echo $product->id; ?></td>
                    <td><?php echo $product->name; ?></td>
                    <td><b><?php echo $error["message"]; ?></b></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php unset($_SESSION["errors"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["cart"])): ?>
        <h2>Lista de venta</h2>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Precio Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($_SESSION["cart"] as $p):
                    $product = ProductData::getById($p["product_id"]);
                    $pt = $product->price_out * $p["q"];
                    $total += $pt;
                ?>
                    <tr>
                        <td><?php echo $product->id; ?></td>
                        <td><?php echo $p["q"]; ?></td>
                        <td><?php echo $product->unit; ?></td>
                        <td><?php echo $product->name; ?></td>
                        <td><b>$ <?php echo number_format($product->price_out, 2); ?></b></td>
                        <td><b>$ <?php echo number_format($pt, 2); ?></b></td>
                        <td><a href="index.php?view=clearcart&product_id=<?php echo $product->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form method="post" class="form-horizontal" id="processsell" action="index.php?view=processsell">
            <h2>Resumen</h2>
            <div class="form-group">
                <label class="col-lg-2 control-label">Cliente</label>
                <div class="col-lg-10">
                    <select name="client_id" class="form-control">
                        <option value="">-- NINGUNO --</option>
                        <?php foreach (PersonData::getClients() as $client): ?>
                            <option value="<?php echo $client->id; ?>"><?php echo $client->name . " " . $client->lastname; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label">Descuento</label>
                <div class="col-lg-10">
                    <input type="number" name="discount" class="form-control" required value="0" id="discount" placeholder="Descuento">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label">Efectivo</label>
                <div class="col-lg-10">
                    <input type="number" name="money" required class="form-control" id="money" placeholder="Efectivo">
                </div>
            </div>
            <input type="hidden" name="total" value="<?php echo $total; ?>">

            <div class="col-md-6 col-md-offset-6">
                <table class="table table-bordered">
                    <tr><td>Subtotal</td><td><b>$ <?php echo number_format($total * 0.84, 2); ?></b></td></tr>
                    <tr><td>IVA</td><td><b>$ <?php echo number_format($total * 0.16, 2); ?></b></td></tr>
                    <tr><td>Total</td><td><b>$ <?php echo number_format($total, 2); ?></b></td></tr>
                </table>
                <button type="submit" class="btn btn-lg btn-primary"><i class="glyphicon glyphicon-usd"></i> Finalizar Venta</button>
                <a href="index.php?view=clearcart" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
            </div>
        </form>

        <script>
            $("#processsell").submit(function(e) {
                let discount = parseFloat($("#discount").val()) || 0;
                let money = parseFloat($("#money").val()) || 0;
                let total = <?php echo $total; ?>;
                if (money < (total - discount)) {
                    alert("No se puede efectuar la operación: efectivo insuficiente.");
                    e.preventDefault();
                } else {
                    let change = money - (total - discount);
                    if (!confirm("Cambio: $" + change.toFixed(2))) {
                        e.preventDefault();
                    }
                }
            });
        </script>
    <?php endif; ?>
</div>
