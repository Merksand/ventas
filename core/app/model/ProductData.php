<?php
class ProductData
{
    public static $tablename = "tb_productos";

    public function __construct()
    {
        $this->id_producto = 0;
        $this->codigo_producto = 0;
        $this->nombre_producto = "";
        $this->descripcion = "";
        $this->precio_compra = 0;
        $this->precio_venta = 0;
        $this->stock = 0;
        $this->stock_minimo = 0;
        $this->imagen = "";

        $this->id_categoria = null;
        $this->is_active = 1;
        $this->unidad = 0;
        $this->presentacion = "";
        $this->fyh_creacion = null;
        $this->fyh_actualizacion = null;
        $this->user_id = null;

        $this->cantidad = 0;
        $this->id_detalle_venta = 0;
        $this->id = 0;
        // $this->fyh_actualizacion = "NOW()";
        // $this->fyh_creacion = "NOW()";
    }

    // Obtener todos los productos
    public static function getAll()
    {
        $sql = "SELECT tp.*, MAX(ta.stock_actual) as stock_actual, MAX(ta.stock_minimo) as stock_minimo
            FROM tb_productos tp
            INNER JOIN tb_almacen ta ON tp.id_producto = ta.id_producto
            WHERE tp.is_active = 1 AND ta.tipo_operacion = 'entrada'
            GROUP BY tp.id_producto";
        $query = Executor::doit($sql);
        return Model::many($query[0], new ProductData());
    }

    // Obtener productos por página con límite




    public static function getAllByPage($id, $limit)
    {
        // Consulta SQL que calcula el stock actual como la diferencia entre entradas y salidas
        $sql = "
            SELECT p.*,stock_minimo, 
                (IFNULL(SUM(CASE WHEN a.tipo_operacion = 'entrada' THEN a.stock_actual ELSE 0 END), 0) -
                IFNULL(SUM(CASE WHEN a.tipo_operacion = 'salida' THEN a.stock_actual ELSE 0 END), 0)) AS stock_actual
            FROM " . self::$tablename . " p
            LEFT JOIN tb_almacen a ON p.id_producto = a.id_producto
            WHERE p.id_producto >= $id and p.is_active = 1
            GROUP BY p.id_producto
            ORDER BY p.id_producto ASC
            LIMIT $limit
        ";

        $query = Executor::doit($sql);

        // Retorna los resultados como objetos de ProductData
        return Model::many($query[0], new ProductData());
    }

    public static function actualizarStockProducto($id_producto)
    {
        // Consulta para calcular el stock actual como diferencia entre entradas y salidas
        $sql = "
            SELECT 
                IFNULL(SUM(CASE WHEN tipo_operacion = 'entrada' THEN stock_actual ELSE 0 END), 0) -
                IFNULL(SUM(CASE WHEN tipo_operacion = 'salida' THEN stock_actual ELSE 0 END), 0) AS stock_total
            FROM tb_almacen
            WHERE id_producto = $id_producto
        ";

        // Ejecutar la consulta y obtener el stock calculado
        $query = Executor::doit($sql);
        $result = $query[0]->fetch_assoc();
        $stock_total = $result["stock_total"];

        // Actualizar el stock en la tabla tb_productos
        $sql_update = "UPDATE tb_productos SET stock = $stock_total WHERE id_producto = $id_producto";
        Executor::doit($sql_update);
    }

    public static function updateStock($product_id, $cantidad)
    {
        $sql = "UPDATE tb_productos SET stock = stock + $cantidad WHERE id_producto = $product_id";
        return Executor::doit($sql);
    }

    public static function updateStockInventary($product_id, $new_stock)
{
    $sql = "UPDATE tb_productos SET stock = $new_stock WHERE id_producto = $product_id";
    Executor::doit($sql);
}

    // En la clase ProductData
    public static function updateStockRevert($product_id, $new_stock)
    {
        $sql = "UPDATE tb_productos SET stock = $new_stock WHERE id_producto = $product_id";
        Executor::doit($sql);
    }





    // Obtener categoría asociada al producto
    public function getCategory()
    {
        return CategoryData::getById($this->id_categoria);
    }

    // Guardar un nuevo producto
    public function add()
    {
        $sql = "INSERT INTO " . self::$tablename . " (codigo_producto, nombre_producto, descripcion, precio_compra, precio_venta, stock, imagen, id_categoria, fyh_creacion, fyh_actualizacion) ";
        $sql .= "VALUES (\"$this->codigo_producto\", \"$this->nombre_producto\", \"$this->descripcion\", $this->precio_compra, $this->precio_venta, $this->stock, \"$this->imagen\", $this->id_categoria, $this->fyh_creacion, $this->fyh_actualizacion)";
        Executor::doit($sql);
    }

    // Actualizar un producto
    public function update()
    {
        $sql = "UPDATE " . self::$tablename . " SET 
                codigo_producto = \"$this->codigo_producto\", 
                nombre_producto = \"$this->nombre_producto\", 
                descripcion = \"$this->descripcion\", 
                precio_compra = $this->precio_compra, 
                precio_venta = $this->precio_venta, 
                stock = $this->stock, 
                imagen = \"$this->imagen\", 
                id_categoria = " . ($this->id_categoria ? $this->id_categoria : "NULL") . ", 
                is_active = $this->is_active, 
                fyh_actualizacion = NOW() 
            WHERE id_producto = $this->id_producto";

        Executor::doit($sql);
    }



    // Eliminar un producto
    public static function delete($id)
    {
        $sql = "UPDATE " . self::$tablename . " SET is_active = 0 WHERE id_producto =$id";
        Executor::doit($sql);
    }

    public static function getAllProductById($id)
    {
        $sql = "SELECT * FROM tb_productos WHERE id_producto = $id";
        $query = Executor::doit($sql);
        return Model::one($query[0], new ProductData());
    }

    // Obtener un producto por ID
    public static function getById($id)
    {

        if (empty($id)) {
            die("Error: ID proporcionado está vacío.");
        }
        $sql = "SELECT * FROM tb_productos tp
            INNER JOIN tb_almacen ta ON tp.id_producto = ta.id_producto
            WHERE tp.id_producto = $id AND ta.tipo_operacion = 'entrada'
            LIMIT 1";

        $query = Executor::doit($sql);
        return Model::one($query[0], new ProductData());
    }


    public function add_with_image()
    {
        $sql = "INSERT INTO tb_productos (codigo_producto, imagen, nombre_producto, descripcion, precio_compra, precio_venta , stock, id_categoria) ";
        $sql .= "VALUES (\"$this->codigo_producto\", \"$this->imagen\", \"$this->nombre_producto\", \"$this->descripcion\", \"$this->precio_compra\", \"$this->precio_venta\", \"$this->stock\", \"$this->id_categoria\")";
        return Executor::doit($sql);
    }


    public static function getAllWithStockMin()
    {
        // Consulta para obtener todos los productos junto con su stock mínimo desde la tabla almacen
        $sql = "SELECT p.*, a.stock_minimo, a.stock_actual 
                FROM tb_productos p
                JOIN tb_almacen a ON p.id_producto = a.id_producto";;
        $query = Executor::doit($sql);

        // Cargar los resultados en objetos ProductData
        $products = Model::many($query[0], new ProductData());

        // Asignar los valores adicionales que se necesiten de la tabla almacen
        foreach ($products as $product) {
            // Esto ya se carga en el objeto ProductData desde el JOIN
            $product->stock_minimo = $product->stock_minimo;  // Asegura que se almacene correctamente
            $product->stock_actual = $product->stock_actual;  // Asegura que el stock actual también se almacene

        }

        return $products;
    }


    public static function getLastId()
    {
        $sql = "SELECT LAST_INSERT_ID() AS id";
        $query = Executor::doit($sql); // Asegúrate de que esto sea correcto
        $result = $query[0]->fetch_assoc();
        return $result['id'];
    }


    public function addAlmacenEntry($id_producto, $tipo_operacion, $stock_minimo, $stock_actual)
    {
        $sql = "INSERT INTO tb_almacen (id_producto, tipo_operacion, stock_minimo, stock_actual) ";
        $sql .= "VALUES ($id_producto, '$tipo_operacion', $stock_minimo, $stock_actual)";
        return Executor::doit($sql);
    }

    public static function getLikes($p)
    {
        $sql = "select * from " . self::$tablename . " where barcode like '%$p%' or name like '%$p%' or id like '%$p%'";
        $query = Executor::doit($sql);
        return Model::many($query[0], new ProductData());
    }


    // FIXME Agregado tipo de entrada, aviso por error futuro
    public static function getLike($p)
    {
        $sql = "SELECT p.*, a.stock_minimo, a.stock_actual, a.tipo_operacion
            FROM " . self::$tablename . " p
            JOIN tb_almacen a ON p.id_producto = a.id_producto
            WHERE p.is_active = 1 AND a.tipo_operacion = 'entrada'
              AND (p.codigo_producto LIKE '%$p%' 
                   OR p.nombre_producto LIKE '%$p%' 
                   OR p.id_producto LIKE '%$p%')
            GROUP BY p.id_producto";  // Agrupar por id_producto para evitar duplicados

        $query = Executor::doit($sql);

        return Model::many($query[0], new ProductData());
    }



    public static function getActiveProducts()
    {
        $sql = "SELECT * FROM tb_productos WHERE is_active = 1";
        $query = Executor::doit($sql);
        return Model::many($query[0], new ProductData());
    }

    public static function getInactiveProducts()
    {
        $sql = "SELECT * FROM tb_productos WHERE is_active = 0";
        $query = Executor::doit($sql);
        return Model::many($query[0], new ProductData());
    }


    // En la clase OperationData
    // En la clase OperationData
    public function del()
    {
        // Usamos `id_detalle_venta` en lugar de `id`
        if (!empty($this->id_detalle_venta)) {
            $sql = "DELETE FROM tb_detalle_venta WHERE id_detalle_venta = $this->id_detalle_venta";
            Executor::doit($sql);
        } else {
            die("Error: No se puede eliminar porque el `id_detalle_venta` no está definido.");
        }
    }




    public static function setVenta($id_cliente, $id_usuario, $total_venta, $cantidad_total, $efectivo)
    {
        $sql = "INSERT INTO tb_ventas (id_cliente, id_usuario, total_venta, cantidad_total, efectivo) ";
        $sql .= "VALUES ($id_cliente, $id_usuario, $total_venta, $cantidad_total, $efectivo)";
        return Executor::doit($sql);
    }


    // public function setCantidad( $cantidadProducto)
    // {
    //     $this -> cantidad = $cantidadProducto;
    // }
    // public function getCantidad()
    // {
    //     return $this -> cantidad;
    // }

}
