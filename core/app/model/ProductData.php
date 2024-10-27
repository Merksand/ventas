<?php
class ProductData
{
    public static $tablename = "tb_productos";

    public function __construct()
    {
        $this->id_producto = "";
        $this->codigo_producto = "";
        $this->nombre_producto = "";
        $this->descripcion = "";
        $this->precio_compra = 0;
        $this->precio_venta = 0;
        $this->stock = 0;
        $this->stock_minimo = 0;
        $this->imagen = "";
        $this->id_categoria = null;
        $this->fyh_creacion = "NOW()";
        $this->fyh_actualizacion = "NOW()";
    }

    // Obtener todos los productos
    public static function getAll()
    {
        $sql = "SELECT * FROM " . self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], new ProductData());
    }

    // Obtener productos por página con límite



    public static function getAllByPage($id, $limit)
    {
        // Consulta SQL con JOIN para obtener datos de productos y almacen
        $sql = "SELECT p.*, a.stock_minimo, a.stock_actual
            FROM " . self::$tablename . " p
            JOIN tb_almacen a ON p.id_producto = a.id_producto
            WHERE p.id_producto >= $id
            ORDER BY p.id_producto ASC
            LIMIT $limit";

        $query = Executor::doit($sql);

        // Retorna los resultados como objetos de ProductData
        return Model::many($query[0], new ProductData());
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
        $sql = "UPDATE " . self::$tablename . " SET codigo_producto=\"$this->codigo_producto\", nombre_producto=\"$this->nombre_producto\", descripcion=\"$this->descripcion\", precio_compra=$this->precio_compra, precio_venta=$this->precio_venta, stock=$this->stock, imagen=\"$this->imagen\", id_categoria=$this->id_categoria, fyh_actualizacion=NOW() WHERE id_producto=$this->id_producto";
        Executor::doit($sql);
    }

    // Eliminar un producto
    public static function delete($id)
    {
        $sql = "DELETE FROM " . self::$tablename . " WHERE id_producto=$id";
        Executor::doit($sql);
    }

    // Obtener un producto por ID
    public static function getById($id)
    {
        $sql = "SELECT * FROM " . self::$tablename . " WHERE id_producto=$id";
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
                JOIN tb_almacen a ON p.id_producto = a.id_producto";
;
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

    public static function getLikes($p){
		$sql = "select * from ".self::$tablename." where barcode like '%$p%' or name like '%$p%' or id like '%$p%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

    public static function getLike($p)
{
    $sql = "SELECT p.*, a.stock_minimo, a.stock_actual
            FROM " . self::$tablename . " p
            JOIN tb_almacen a ON p.id_producto = a.id_producto
            WHERE p.codigo_producto LIKE '%$p%' 
               OR p.nombre_producto LIKE '%$p%' 
               OR p.id_producto LIKE '%$p%'";

    $query = Executor::doit($sql);

    return Model::many($query[0], new ProductData());
}

}
