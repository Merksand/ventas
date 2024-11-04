<?php

require_once __DIR__ . '/../../controller/Database.php';


class PersonData
{
	public static $tablename = "person";


	public function __construct()
	{
		$this->name = "";
		$this->lastname = "";
		$this->lastname2 = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->CI = "";
		$this->phone = "";
		$this->address = "";
		$this->created_at = "NOW()";
	}

	// public function add_client(){
	// 	$sql = "insert into person (name,lastname,address1,email1,phone1,kind,created_at) ";
	// 	$sql .= "value (\"$this->name\",\"$this->lastname\",\"$this->address1\",\"$this->email1\",\"$this->phone1\",1,$this->created_at)";
	// 	Executor::doit($sql);
	// }

	public function add_client()
	{
		// Primero, insertemos la persona en la tabla tb_persona
		$sql_persona = "INSERT INTO tb_persona (nombre, apellido_paterno, apellido_materno, direccion, email, celular) 
                    VALUES ('$this->name', '$this->lastname', '$this->lastname2', '$this->address', '$this->email', '$this->phone')";

		// Ejecutar la inserción de la persona
		Executor::doit($sql_persona);

		// Obtenemos el último ID insertado para usarlo al insertar el cliente
		$last_id = self::getLastId(); // Llamamos al método getLastId directamente

		// Ahora insertamos el cliente en tb_clientes usando el id_persona obtenido
		$sql_cliente = "INSERT INTO tb_clientes (id_persona, nit_ci) 
                    VALUES ($last_id, '$this->CI')"; // Asegúrate de que $this->CI esté definido

		// Ejecutar la inserción del cliente
		Executor::doit($sql_cliente);
	}


	public static function getLastId()
	{
		$sql = "SELECT LAST_INSERT_ID() AS id";
		$query = Executor::doit($sql); // Asegúrate de que esto sea correcto
		$result = $query[0]->fetch_assoc();
		return $result['id'];
	}



	public function add_provider()
	{
		$sql = "insert into person (name,lastname,address1,email1,phone1,kind,created_at) ";
		$sql .= "value ($this->name\",\"$this->lastname\",\"$this->address1\",\"$this->email1\",\"$this->phone1\",2,$this->created_at)";
		Executor::doit($sql);
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

	// partiendo de que ya tenemos creado un objecto PersonData previamente utilizamos el contexto
	public function update()
	{
		$sql = "update " . self::$tablename . " set name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_client()
	{
		$sql = "update " . self::$tablename . " set name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_provider()
	{
		$sql = "update " . self::$tablename . " set name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_passwd()
	{
		$sql = "update " . self::$tablename . " set password=\"$this->password\" where id=$this->id";
		Executor::doit($sql);
	}


	public static function getById($id)
	{
		$sql = "select * from " . self::$tablename . " where id=$id";
		$query = Executor::doit($sql);
		$found = null;
		$data = new PersonData();
		while ($r = $query[0]->fetch_array()) {
			$data->id = $r['id'];
			$data->name = $r['name'];
			$data->lastname = $r['lastname'];
			$data->address1 = $r['address1'];
			$data->phone1 = $r['phone1'];
			$data->email1 = $r['email1'];
			$data->created_at = $r['created_at'];
			$found = $data;
			break;
		}
		return $found;
	}



	public static function getAll()
	{
		$sql = "select * from " . self::$tablename;
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while ($r = $query[0]->fetch_array()) {
			$array[$cnt] = new PersonData();
			$array[$cnt]->id = $r['id'];
			$array[$cnt]->name = $r['name'];
			$array[$cnt]->lastname = $r['lastname'];
			$array[$cnt]->email = $r['email1'];
			$array[$cnt]->username = $r['username'];
			$array[$cnt]->phone1 = $r['phone1'];
			$array[$cnt]->address1 = $r['address1'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getClients()
	{
		// Definir la consulta SQL para obtener los datos de los clientes
		$sql = "SELECT 
                p.id_persona AS id, 
                p.nombre AS name, 
                p.apellido_paterno AS lastname, 
                p.apellido_materno AS lastname2, 
                p.email AS email, 
                p.celular AS phone, 
				c.nit_ci AS CI,
                p.direccion AS address, 
                p.fyh_creacion AS created_at
            FROM tb_persona p
            JOIN tb_clientes c ON p.id_persona = c.id_persona
            ORDER BY p.nombre, p.apellido_paterno";

		// Ejecutar la consulta SQL
		$query = Executor::doit($sql);

		// Crear un arreglo para almacenar los resultados
		$array = array();
		$cnt = 0;

		// Recorrer los resultados de la consulta
		while ($r = $query[0]->fetch_array()) {
			$array[$cnt] = new PersonData();
			$array[$cnt]->id = $r['id'];
			$array[$cnt]->name = $r['name'];
			$array[$cnt]->lastname = $r['lastname'];
			$array[$cnt]->lastname2 = $r['lastname2'];
			$array[$cnt]->CI = $r['CI'];
			$array[$cnt]->email = $r['email'];
			$array[$cnt]->phone = $r['phone'];
			$array[$cnt]->address = $r['address'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}

		// Devolver el arreglo con los datos de los clientes
		return $array;
	}



	public static function getProviders()
	{
		$sql = "select * from " . self::$tablename . " where kind=2 order by name,lastname";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while ($r = $query[0]->fetch_array()) {
			$array[$cnt] = new PersonData();
			$array[$cnt]->id = $r['id'];
			$array[$cnt]->name = $r['name'];
			$array[$cnt]->lastname = $r['lastname'];
			$array[$cnt]->email1 = $r['email1'];
			$array[$cnt]->phone1 = $r['phone1'];
			$array[$cnt]->address1 = $r['address1'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getLike($q)
	{
		$sql = "select * from " . self::$tablename . " where name like '%$q%'";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while ($r = $query[0]->fetch_array()) {
			$array[$cnt] = new PersonData();
			$array[$cnt]->id = $r['id'];
			$array[$cnt]->name = $r['name'];
			$array[$cnt]->mail = $r['mail'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}
}
