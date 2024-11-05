<?php
class SellData
{
	public static $tablename = "sell";

	public function __construct()
	{
		$this->created_at = "NOW()";
		$this->person_id = 0;
	}

	// public function getPerson()
	// {
	// 	return PersonData::getById($this->person_id);
	// }
	// public function getUser($idUsuario)
	// {
	// 	return UserData::getById($idUsuario);
	// }

	public static function getUser($id)
	{
		// Definir la consulta SQL para obtener toda la información del usuario, incluyendo el nombre del rol
		$sql = "SELECT u.*, p.*, r.nombre_rol AS rol_nombre FROM tb_usuarios u
				INNER JOIN tb_persona p ON u.id_persona = p.id_persona
				INNER JOIN tb_roles r ON u.id_rol = r.id_rol
				WHERE u.id_usuario = $id";

		// Ejecutar la consulta
		$query = Executor::doit($sql);

		// Retornar el resultado como un objeto UserData
		return Model::one($query[0], new UserData());
	}

	public function add()
	{
		$sql = "insert into " . self::$tablename . " (total,discount,user_id,created_at) ";
		$sql .= "value ($this->total,$this->discount,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public function add_re()
	{
		$sql = "insert into " . self::$tablename . " (user_id,operation_type_id,created_at) ";
		$sql .= "value ($this->user_id,1,$this->created_at)";
		return Executor::doit($sql);
	}


	public function add_with_client()
	{
		$sql = "insert into " . self::$tablename . " (total,discount,person_id,user_id,created_at) ";
		$sql .= "value ($this->total,$this->discount,$this->person_id,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public function add_re_with_client()
	{
		$sql = "insert into " . self::$tablename . " (person_id,operation_type_id,user_id,created_at) ";
		$sql .= "value ($this->person_id,1,$this->user_id,$this->created_at)";
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
