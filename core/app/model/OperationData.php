<?php
class OperationData
{
	public static $tablename = "operation";

	public $product_id;
	public $operation_type_id; // 1 - entrada, 2 - salida
	public $sell_id;
	public $q; // Cantidad
	public $is_oficial; // Indicador si es oficial

	public function __construct()
	{
		// $this->name = "culo";
		// $this->product_id = "";
		// $this->q = "";
		// $this->cut_id = "";
		// $this->operation_type_id = "";
		// $this->created_at = "NOW()";



	}

	private function updateInventory()
	{
		// Aquí debes implementar la lógica para actualizar el inventario
		$query = "UPDATE tb_productos SET cantidad = cantidad + $this->q WHERE id_producto = '$this->product_id'";
		Executor::doit($query);
	}

	public function add($product_id, $sell_id, $quantity)
	{
		echo "<pre><b>Product ID:</b> $product_id</pre>";
		echo "<pre><b>Sell ID:</b> $sell_id</pre>";
		echo "<pre><b>Quantity:</b> $quantity</pre>";

		$query = "INSERT INTO tb_detalle_compra (id_compra, id_producto, cantidad, precio_unitario) 
              VALUES ('$sell_id', '$product_id', '$quantity', 
              (SELECT precio_compra FROM tb_productos WHERE id_producto = '$product_id'))";

		echo "<pre><b>SQL Query for Detail:</b> $query</pre>";

		$result = Executor::doit($query);

		if ($result && $result[0]) {
			return [true, "Detalle de compra agregado"];
		} else {
			echo "<pre><b>SQL Error:</b> " . $result[1] . "</pre>";
			return [false, "Producto no válido o precio no definido"];
		}
	}




	public static function delById($id)
	{
		$sql = "delete from " . self::$tablename . " where id=$id";
		Executor::doit($sql);
	}
	public function del()
	{
		$sql = "delete from " . self::$tablename . " where id=$this->id";
		Executor::doit($sql);
	}

	// partiendo de que ya tenemos creado un objecto OperationData previamente utilizamos el contexto
	public function update()
	{
		$sql = "update " . self::$tablename . " set product_id=\"$this->product_id\",q=\"$this->q\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "select * from " . self::$tablename . " where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}




	public static function getAll()
	{
		$sql = "select * from " . self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}



	public static function getAllByDateOfficial($start, $end)
	{
		$sql = "select * from " . self::$tablename . " where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" order by created_at desc";
		if ($start == $end) {
			$sql = "select * from " . self::$tablename . " where date(created_at) = \"$start\" order by created_at desc";
		}
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getAllByDateOfficialBP($product, $start, $end)
	{
		$sql = "select * from " . self::$tablename . " where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and product_id=$product order by created_at desc";
		if ($start == $end) {
			$sql = "select * from " . self::$tablename . " where date(created_at) = \"$start\" order by created_at desc";
		}
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}



	// public static function getProductAlmacenVenta($sellID)
	// {
	// 	$sql = "SELECT 
	// dv.id_detalle_venta,
	// dv.id_producto,
	// p.nombre_producto AS product_name,
	// a.stock_minimo,
	// a.stock_actual,
	// dv.cantidad,2
	// dv.precio_unitario FROM tb_detalle_venta dv JOIN tb_productos p ON dv.id_producto = p.id_producto 
	// JOIN tb_almacen a ON p.id_producto = a.id_producto 
	// WHERE dv.id_venta = $sellID";
	// 	$query = Executor::doit($sql);
	// 	return Model::many($query[0], new OperationData());
	// }
	public static function getProductAlmacenVenta($sellID)
	{
		$sql = "SELECT 
    * FROM tb_detalle_venta dv JOIN tb_productos p ON dv.id_producto = p.id_producto 
	JOIN tb_almacen a ON p.id_producto = a.id_producto 
	WHERE dv.id_venta = $sellID";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getProduct($id)
	{
		// echo "idddddddddddddddddd: " . $id;
		return ProductData::getById($id);
	}


	// public static function getProduct($id)
	// {
	// 	return ProductData::getById($id);
	// }


	public function getOperationtype()
	{
		return OperationTypeData::getById($this->operation_type_id);
	}





	// public static function getQYesF($product_id)
	// {
	// 	$q = 0;
	// 	$operations = self::getAllByProductId($product_id);
	// 	$input_id = OperationTypeData::getByName("entrada")->id;
	// 	$output_id = OperationTypeData::getByName("salida")->id;
	// 	foreach ($operations as $operation) {
	// 		if ($operation->operation_type_id == $input_id) {
	// 			$q += $operation->q;
	// 		} else if ($operation->operation_type_id == $output_id) {
	// 			$q += (-$operation->q);
	// 		}
	// 	}
	// 	// print_r($data);
	// 	return $q;
	// }




	public static function getAllByProductIdCutId($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}




	public static function getAllByProductIdCutIdOficial($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		return Model::many($query[0], new OperationData());
	}





	public static function getAllByProductIdCutIdYesF($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		return Model::many($query[0], new OperationData());
		return $array;
	}

	////////////////////////////////////////////////////////////////////
	public static function getOutputQ($product_id, $cut_id)
	{
		$q = 0;
		$operations = self::getOutputByProductIdCutId($product_id, $cut_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$q += (-$operation->q);
			}
		}
		// print_r($data);
		return $q;
	}

	public static function getOutputQYesF($product_id)
	{
		$q = 0;
		$operations = self::getOutputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$q += (-$operation->q);
			}
		}
		// print_r($data);
		return $q;
	}

	public static function getInputQYesF($product_id)
	{
		$q = 0;
		$operations = self::getInputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			}
		}
		// print_r($data);
		return $q;
	}



	public static function getOutputByProductIdCutId($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id and operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}


	public static function getOutputByProductId($product_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	////////////////////////////////////////////////////////////////////
	public static function getInputQ($product_id, $cut_id)
	{
		$q = 0;
		return Model::many($query[0], new OperationData());
		$operations = self::getInputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$q += (-$operation->q);
			}
		}
		// print_r($data);
		return $q;
	}


	public static function getInputByProductIdCutId($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getInputByProductId($product_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getInputByProductIdCutIdYesF($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	////////////////////////////////////////////////////////////////////////////

	public static function getAllByProductId($product_id)
	{
		// Nos aseguramos de obtener los datos desde tb_almacen
		$sql = "SELECT * FROM tb_almacen WHERE id_producto = $product_id ORDER BY fyh_creacion DESC";
		$query = Executor::doit($sql);

		// Si `OperationData` es la clase que representa datos de `tb_almacen`, la usamos aquí
		return Model::many($query[0], new OperationData());
	}



	public static function getQYesF($product_id)
	{
		$q = 0;
		$operations = self::getAllByProductId($product_id); // Asegúrate de que esta función devuelve todas las operaciones para el producto
		$input_type = "entrada";
		$output_type = "salida";
		// 
		foreach ($operations as $operation) {
			if ($operation->tipo_operacion === $input_type) {
				$q += $operation->stock_actual;  // Sumar stock para entradas
			} elseif ($operation->tipo_operacion === $output_type) {
				$q -= $operation->stock_actual;  // Restar stock para salidas
			}
		}

		return $q;
	}


	public static function getAllProductsBySellId($sell_id)
	{
		$sql = "SELECT * FROM tb_detalle_venta WHERE id_venta = $sell_id";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}
}
