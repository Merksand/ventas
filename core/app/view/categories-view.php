<div class="row">
    <div class="col-md-12">
        <div class="btn-group pull-right">
            <a href="index.php?view=newcategory" class="btn btn-default"><i class='fa fa-th-list'></i> Nueva Categoría</a>
            
            <!-- Botón para filtrar categorías activas e inactivas -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-filter"></i> Filtrar Categorías <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="index.php?view=categories&status=active">Mostrar Activas</a></li>
                    <li><a href="index.php?view=categories&status=inactive">Mostrar Inactivas</a></li>
                </ul>
            </div>
        </div>
        <h1>Categorías</h1>
        <br>
        
        <?php
        // Obtener el estado de visualización de la categoría (activa o inactiva)
        $status = isset($_GET["status"]) ? $_GET["status"] : 'active';

        // Consultar categorías basándose en el filtro de estado
        $categories = ($status === 'inactive') ? CategoryData::getInactiveCategories() : CategoryData::getActiveCategories();
        
        // echo "<pre>";
        // print_r($categories);
        // echo "</pre>";

        if (count($categories) > 0) {
            ?>

            <table class="table table-bordered table-hover">
                <thead>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </thead>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category->name); ?></td>
                        <td style="width:130px;">
                            <a href="index.php?view=editcategory&id=<?php echo $category->id; ?>" class="btn btn-warning btn-xs">Editar</a>
                            <a href="index.php?view=delcategory&id=<?php echo $category->id; ?>" class="btn btn-danger btn-xs">
                                <?php echo $category->is_active ? 'Desactivar' : 'Activar'; ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php
        } else {
            echo "<p class='alert alert-danger'>No hay categorías disponibles</p>";
        }
        ?>
    </div>
</div>
