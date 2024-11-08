<?php
class SellData
{
	public static $tablename = "sell";
	public $user_id;
	public $person_id;
	public function __construct()
	{
		$this->created_at = "NOW()";
		$this->person_id = 0;
		$this->user_id = 0;
		$this->id = 0;

		$this->id_usuario = 0;
		$this->id_cliente = 0;
		$this->id_venta = 0;
		$this->apellido = null;
		$this->nombre = null;
		$this->id_proveedor = 0;
	}

	public function add_re()
	{
		// Inserción en tb_compras con NULL en id_proveedor cuando no hay proveedor
		$query = "INSERT INTO tb_compras (id_usuario, id_proveedor, total_compra) 
				  VALUES ('" . $this->user_id . "', NULL, 0)";

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
		$sql = "SELECT  r.nombre_rol AS rol_nombre,p.nombre as nombre,p.apellido_materno, p.apellido_paterno FROM tb_usuarios u
				INNER JOIN tb_persona p ON u.id_persona = p.id_persona
				INNER JOIN tb_roles r ON u.id_rol = r.id_rol
				WHERE p.id_persona = $id";

		// Ejecutar la consulta
		$query = Executor::doit($sql);

		// Retornar el resultado como un objeto UserData
		return Model::one($query[0], new UserData());
	}


	public static function getBuyUser($id)
	{
		$sql = "SELECT  r.nombre_rol AS rol_nombre,p.nombre as nombre,p.apellido_materno, p.apellido_paterno FROM tb_usuarios u
				INNER JOIN tb_persona p ON u.id_persona = p.id_persona
				INNER JOIN tb_roles r ON u.id_rol = r.id_rol
				WHERE u.id_usuario = $id";
		// Ejecutar la consulta
		$query = Executor::doit($sql);

		// Retornar el resultado como un objeto UserData
		return Model::one($query[0], new UserData());
	}

	public function getPerson()
	{
		// Verifica si hay un proveedor asociado a la venta
		if ($this->person_id != null) {
			// Consulta para obtener los datos del proveedor/persona
			// $sql = "SELECT * FROM tb_proveedores WHERE id_proveedor = $this->person_id";
			$sql = "select * from tb_persona tp inner join tb_proveedores tpo ON  tp.id_persona = tpo.id_persona WHERE tpo.id_proveedor = $this->person_id";
			$query = Executor::doit($sql);

			return Model::one($query[0], new PersonData());
		}

		// Retorna null si no hay proveedor asociado
		return null;
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

	public static function getByIdReabastecimiento($id)
	{
		$sql = "SELECT * FROM tb_compras WHERE id_compra = $id";
		$query = Executor::doit($sql);
		$found = null;

		if ($r = $query[0]->fetch_array()) {
			$found = new SellData();
			$found->id = $r['id_compra'];
			$found->person_id = $r['id_proveedor'];
			$found->user_id = $r['id_usuario'];
			// Asigna otras propiedades según tus columnas
		}

		return $found;
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
		// Realizamos el JOIN entre tb_ventas y tb_clientes, también se usa el filtro de la operación.
		$sql = "SELECT v.*, c.*, v.created_at, v.operation_type_id
            FROM " . self::$tablename . " v
            JOIN tb_clientes c ON v.id_cliente = c.id_cliente
            WHERE date(v.created_at) >= \"$start\" 
              AND date(v.created_at) <= \"$end\" 
              AND c.id_cliente = $clientid
              AND v.operation_type_id = $op
            ORDER BY v.created_at DESC";

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
