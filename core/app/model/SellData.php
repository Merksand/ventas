<?php
class SellData
{
	public $user_id;
	public $person_id;
	public function __construct()
	{
		$this->created_at = "NOW()";
		$this->person_id = 0;
		$this->user_id = 0;
	}

	public function add_re()
	{
		// Inserción directa en tb_compras
		$query = "INSERT INTO tb_compras (id_usuario, id_proveedor, total_compra, fecha_compra) 
              VALUES ('" . $this->user_id . "', 0, 0, NOW())";

		// Ejecutar la consulta
		$result = Executor::doit($query);

		// Verificar el resultado de la inserción
		if ($result && $result[0]) {
			return [$result[1], "Compra registrada sin proveedor"]; // Éxito, devuelve ID y mensaje
		} else {
			return [null, "Error al registrar la compra sin proveedor"];
		}
	}


	public function add_re_with_client()
	{
		echo "Entrado al metodo add re with client";
		// Consulta para obtener el id_proveedor
		$query_proveedor = "SELECT id_proveedor FROM tb_proveedores WHERE id_persona = '" . $this->person_id . "'";

		// Ejecutar la consulta
		$result_proveedor = Executor::doit($query_proveedor);

		// Verificar el resultado y obtener el id_proveedor
		if ($result_proveedor && $result_proveedor[0] instanceof mysqli_result) {
			$row = $result_proveedor[0]->fetch_assoc();
			if ($row) {
				$id_proveedor = $row['id_proveedor'];
				echo "<pre><b>Proveedor ID:</b> $id_proveedor</pre>";
			} else {
				echo "<pre><b>Error:</b> No se encontró un proveedor con id_persona = {$this->person_id}</pre>";
				return [null, "Error: Proveedor no encontrado"];
			}
		} else {
			echo "<pre><b>Error en la consulta SQL:</b> " . $result_proveedor[1] . "</pre>";
			return [null, "Error en la consulta para obtener el proveedor"];
		}

		// Inserción en tb_compras
		$query = "INSERT INTO tb_compras (id_usuario, id_proveedor, total_compra) 
              VALUES ('" . $this->user_id . "', '" . $id_proveedor . "', 0)";
		echo "<pre><b>SQL Query for Insertion:</b> $query</pre>";

		// Ejecutar la inserción
		$result = Executor::doit($query);

		if ($result && $result[0]) {
			return [$result[1], "Compra registrada con proveedor"];
		} else {
			echo "<pre><b>Error en la inserción SQL:</b> " . $result[1] . "</pre>";
			return [null, "Error al registrar la compra con proveedor"];
		}
	}





	public function add_detail($compra_id, $product_id, $quantity, $unit_price)
	{
		// Inserción directa en tb_detalle_compra
		$query = "INSERT INTO tb_detalle_compra (id_compra, id_producto, cantidad, precio_unitario) 
				  VALUES ('" . $compra_id . "', '" . $product_id . "', '" . $quantity . "', '" . $unit_price . "')";

		// Ejecutar la consulta
		$result = Executor::doit($query);

		// Verificar el resultado de la inserción
		if ($result && $result[0]) {
			return [$result[1], "Detalle de compra agregado"];
		} else {
			return [null, "Error al agregar el detalle de compra"];
		}
	}




	public function add()
	{
		$sql = "insert into " . self::$tablename . " (total,discount,user_id,created_at) ";
		$sql .= "value ($this->total,$this->discount,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}


	public static function getUser($id)
	{
		$sql = "SELECT u.*, p.*, r.nombre_rol AS rol_nombre FROM tb_usuarios u
				INNER JOIN tb_persona p ON u.id_persona = p.id_persona
				INNER JOIN tb_roles r ON u.id_rol = r.id_rol
				WHERE u.id_usuario = $id";

		// Ejecutar la consulta
		$query = Executor::doit($sql);

		// Retornar el resultado como un objeto UserData
		return Model::one($query[0], new UserData());
	}

	public function add_with_client()
	{
		$sql = "insert into " . self::$tablename . " (total,discount,person_id,user_id,created_at) ";
		$sql .= "value ($this->total,$this->discount,$this->person_id,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
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

	public function update_box()
	{
		$sql = "update " . self::$tablename . " set box_id=$this->box_id where id=$this->id";
		Executor::doit($sql);
	}

	// public static function getById($id)
	// {
	// 	$sql = "select * from " . self::$tablename . " where id=$id";
	// 	$query = Executor::doit($sql);
	// 	return Model::one($query[0], new SellData());
	// }

	public static function getById($id)
	{
		$sql = "SELECT * FROM tb_ventas WHERE id_venta = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new SellData());
	}






	public static function getSells()
	{
		$sql = "select * from " . self::$tablename . " where operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new SellData());
	}

	public static function getSellsUnBoxed()
	{
		$sql = "select * from " . self::$tablename . " where operation_type_id=2 and box_id is NULL order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new SellData());
	}

	public static function getByBoxId($id)
	{
		$sql = "select * from " . self::$tablename . " where operation_type_id=2 and box_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new SellData());
	}

	public static function getRes()
	{
		$sql = "select * from " . self::$tablename . " where operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new SellData());
	}

	public static function getAllByPage($start_from, $limit)
	{
		$sql = "select * from " . self::$tablename . " where id<=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0], new SellData());
	}

	public static function getAllByDateOp($start, $end, $op)
	{
		$sql = "select * from " . self::$tablename . " where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new SellData());
	}
	public static function getAllByDateBCOp($clientid, $start, $end, $op)
	{
		$sql = "select * from " . self::$tablename . " where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and client_id=$clientid  and operation_type_id=$op order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new SellData());
	}




	public static function getClient($id)
	{
		// Consulta SQL para obtener toda la información del cliente basada en el id_cliente
		$sql = "SELECT 
                c.id_cliente AS client_id, 
                p.id_persona AS person_id, 
                p.nombre AS name, 
                p.apellido_paterno AS lastname, 
                p.apellido_materno AS lastname2, 
                p.direccion AS address, 
                p.celular AS phone, 
                p.email AS email, 
                p.fyh_creacion AS created_at, 
                c.nit_ci AS CI
            FROM tb_clientes c
            LEFT JOIN tb_persona p ON c.id_persona = p.id_persona
            WHERE c.id_cliente = $id";

		// Ejecutar la consulta
		$query = Executor::doit($sql);

		// Retornar el resultado como un objeto UserData (ajustar según la clase que uses para manejar los datos)
		return Model::one($query[0], new UserData());
	}
}
