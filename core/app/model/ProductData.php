<?php
class ProductData {
	public static $tablename = "tb_productos";

	public function __construct(){
		$this->codigo_producto = "";
		$this->nombre_producto = "";
		$this->descripcion = "";
		$this->precio_compra = "";
		$this->precio_venta = "";
		$this->stock = "";
		$this->imagen = "";
		$this->id_categoria = "";
		$this->fyh_creacion = "NOW()";
		$this->fyh_actualizacion = "NOW()";
	}

	public function getCategory() {
		return CategoryData::getById($this->id_categoria);
	}

	public function add() {
		$sql = "INSERT INTO ".self::$tablename." (codigo_producto, nombre_producto, descripcion, precio_compra, precio_venta, stock, imagen, id_categoria, fyh_creacion) ";
		$sql .= "VALUES (\"$this->codigo_producto\", \"$this->nombre_producto\", \"$this->descripcion\", \"$this->precio_compra\", \"$this->precio_venta\", \"$this->stock\", \"$this->imagen\", $this->id_categoria, NOW())";
		return Executor::doit($sql);
	}

	public function add_with_image() {
		$sql = "INSERT INTO ".self::$tablename." (codigo_producto, imagen, nombre_producto, descripcion, precio_compra, precio_venta, stock, id_categoria, fyh_creacion) ";
		$sql .= "VALUES (\"$this->codigo_producto\", \"$this->imagen\", \"$this->nombre_producto\", \"$this->descripcion\", \"$this->precio_compra\", \"$this->precio_venta\", \"$this->stock\", $this->id_categoria, NOW())";
		return Executor::doit($sql);
	}

	public static function delById($id) {
		$sql = "DELETE FROM ".self::$tablename." WHERE id_producto = $id";
		Executor::doit($sql);
	}

	public function del() {
		$sql = "DELETE FROM ".self::$tablename." WHERE id_producto = $this->id_producto";
		Executor::doit($sql);
	}

	public function update() {
		$sql = "UPDATE ".self::$tablename." SET codigo_producto=\"$this->codigo_producto\", nombre_producto=\"$this->nombre_producto\", precio_compra=\"$this->precio_compra\", precio_venta=\"$this->precio_venta\", stock=\"$this->stock\", imagen=\"$this->imagen\", id_categoria=$this->id_categoria, descripcion=\"$this->descripcion\" WHERE id_producto=$this->id_producto";
		Executor::doit($sql);
	}

	public function update_image() {
		$sql = "UPDATE ".self::$tablename." SET imagen=\"$this->imagen\" WHERE id_producto=$this->id_producto";
		Executor::doit($sql);
	}

	public static function getById($id) {
		$sql = "SELECT * FROM ".self::$tablename." WHERE id_producto = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ProductData());
	}

	public static function getAll() {
		$sql = "SELECT * FROM ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0], new ProductData());
	}

	public static function getAllByCategoryId($category_id) {
		$sql = "SELECT * FROM ".self::$tablename." WHERE id_categoria = $category_id ORDER BY fyh_creacion DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ProductData());
	}
}
?>
