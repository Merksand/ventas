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
		// $this->image = "";
		$this->password = "";
		$this->CI = "";
		$this->phone = "";
		$this->address = "";
		$this->NIT = "";
		$this->empresa = "";
		$this->created_at = "NOW()";
	}

	// public function add_client(){
	// 	$sql = "insert into person (name,lastname,address1,email1,phone1,kind,created_at) ";
	// 	$sql .= "value (\"$this->name\",\"$this->lastname\",\"$this->address1\",\"$this->email1\",\"$this->phone1\",1,$this->created_at)";
	// 	Executor::doit($sql);
	// }
	// public static function getLastId()
	// {
	// 	$sql = "SELECT LAST_INSERT_ID() AS id";
	// 	$query = Executor::doit($sql); // Asegúrate de que esto sea correcto
	// 	$result = $query[0]->fetch_assoc();
	// 	return $result['id'];
	// }




	public static function getLastId()
	{
		$sql = "SELECT LAST_INSERT_ID() AS id";
		$query = Executor::doit($sql); // Asegúrate de que esto sea correcto
		$result = $query[0]->fetch_assoc();
		return $result['id'];
	}
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






	public function add_provider()
	{
		// Insertar en la tabla tb_persona (datos generales de la persona)
		$sql = "INSERT INTO tb_persona (nombre, apellido_paterno, apellido_materno, direccion, celular, email)
				VALUES ('$this->name', '$this->lastname', '$this->lastname2', '$this->address', '$this->phone', '$this->email')";

		// Ejecutar la consulta para agregar a la persona
		Executor::doit($sql);

		// Obtener el último ID insertado para la persona (id_persona)
		$person_id = self::getLastId();

		// Insertar en la tabla tb_proveedores (datos específicos del proveedor)
		$sql_provider = "INSERT INTO tb_proveedores (id_persona, nit_empresa, nombre_empresa)
						 VALUES ('$person_id', '$this->NIT', '$this->empresa')";

		// Ejecutar la consulta para agregar al proveedor
		Executor::doit($sql_provider);
	}

	// public static function delById($id)
	// {
	// 	$sql = "delete from " . self::$tablename . " where id=$id";
	// 	Executor::doit($sql);
	// }

	public static function addRole($roleName)
	{
		// Consulta SQL para insertar el nuevo rol
		$sql = "INSERT INTO tb_roles (nombre_rol) VALUES ('$roleName')";

		// Ejecutar la consulta
		Executor::doit($sql);
	}

	public static function getRoles()
	{
		$sql = "SELECT * FROM tb_roles";
		$query = Executor::doit($sql);
		return $query[0];
	}

	public static function deleteById($id)
	{
		// Eliminar el registro en tb_clientes
		$sql1 = "DELETE FROM tb_clientes WHERE id_persona = $id";
		Executor::doit($sql1);

		// Eliminar el registro en tb_persona
		$sql2 = "DELETE FROM tb_persona WHERE id_persona = $id";
		Executor::doit($sql2);
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
		$con = Database::getCon(); // Obtener la conexión

		// Iniciar una transacción
		$con->begin_transaction();
		try {
			// Actualizar la tabla tb_persona
			$sql_persona = "UPDATE tb_persona 
                        SET nombre = '$this->name', 
                            apellido_paterno = '$this->lastname', 
                            apellido_materno = '$this->lastname2', 
                            direccion = '$this->address', 
                            celular = '$this->phone', 
                            email = '$this->email' 
                        WHERE id_persona = $this->id";

			Executor::doit($sql_persona);

			// Actualizar la tabla tb_clientes
			$sql_cliente = "UPDATE tb_clientes 
                        SET nit_ci = '$this->CI' 
                        WHERE id_persona = $this->id";

			Executor::doit($sql_cliente);

			// Confirmar la transacción
			$con->commit();
		} catch (Exception $e) {
			// Si hay un error, revertir la transacción
			$con->rollback();
			throw $e; // O maneja el error de otra forma
		}
	}


	// public function update_provider()
	// {
	// 	$sql = "update " . self::$tablename . " set name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\" where id=$this->id";
	// 	Executor::doit($sql);
	// }

	public function update_passwd()
	{
		$sql = "update " . self::$tablename . " set password=\"$this->password\" where id=$this->id";
		Executor::doit($sql);
	}


	public static function getById($id)
	{
		// Definir la consulta SQL para obtener los datos de la persona y cliente con el ID proporcionado
		$sql = "SELECT 
                p.id_persona AS id, 
                p.nombre AS name, 
                p.apellido_paterno AS lastname, 
                p.apellido_materno AS lastname2, 
                p.direccion AS address, 
                p.celular AS phone, 
                p.email AS email, 
                p.fyh_creacion AS created_at, 
                c.nit_ci AS CI 
            FROM tb_persona p
            JOIN tb_clientes c ON p.id_persona = c.id_persona
            WHERE p.id_persona = $id";

		// Ejecutar la consulta
		$query = Executor::doit($sql);
		$found = null;
		$data = new PersonData();

		// Recorrer los resultados de la consulta (se espera un solo registro)
		if ($r = $query[0]->fetch_array()) {
			$data->id = $r['id'];
			$data->name = $r['name'];
			$data->lastname = $r['lastname'];
			$data->lastname2 = $r['lastname2'];
			$data->CI = $r['CI'];
			$data->address = $r['address'];
			$data->phone = $r['phone'];
			$data->email = $r['email'];
			$data->created_at = $r['created_at'];
			$found = $data;
		}

		return $found;
	}

	public static function getProviderById($id)
	{
		// Definir la consulta SQL para obtener los datos de la persona y proveedor con el ID proporcionado
		$sql = "SELECT 
                p.id_persona AS id, 
                p.nombre AS name, 
                p.apellido_paterno AS lastname, 
                p.apellido_materno AS lastname2, 
                p.direccion AS address, 
                p.celular AS phone, 
                p.email AS email, 
                p.fyh_creacion AS created_at, 
                prov.nit_empresa AS NIT ,
				prov.nombre_empresa AS empresa
            FROM tb_persona p
            JOIN tb_proveedores prov ON p.id_persona = prov.id_persona
            WHERE p.id_persona  = $id";

		// Ejecutar la consulta
		$query = Executor::doit($sql);
		$found = null;
		$data = new PersonData();

		// Recorrer los resultados de la consulta (se espera un solo registro)
		if ($r = $query[0]->fetch_array()) {
			$data->id = $r['id'];
			$data->name = $r['name'];
			$data->lastname = $r['lastname'];
			$data->lastname2 = $r['lastname2'];
			$data->NIT = $r['NIT'];  // Aquí se puede usar 'nit_ci' si está en la tabla de proveedores
			$data->address = $r['address'];
			$data->phone = $r['phone'];
			$data->email = $r['email'];
			$data->empresa = $r['empresa'];
			$data->created_at = $r['created_at'];
			$found = $data;
		}

		return $found;
	}


	// En PersonData (suponiendo que esta es la clase donde se realiza la actualización)
	// En PersonData (donde se realiza la actualización)
	public function update_provider()
	{
		// Consulta SQL simplificada para actualizar los datos del proveedor en tb_persona
		$sql = "UPDATE tb_persona 
            SET nombre = '$this->name', 
                apellido_paterno = '$this->lastname', 
                apellido_materno = '$this->lastname2', 
                direccion = '$this->address', 
                celular = '$this->phone', 
                email = '$this->email' 
            WHERE id_persona = $this->id";

		// Ejecutar la consulta para actualizar la tabla tb_persona
		$query = Executor::doit($sql);

		// Actualizar también la tabla tb_proveedores si hay datos adicionales que quieras actualizar
		$sqlProv = "UPDATE tb_proveedores 
                SET nit_empresa = '$this->NIT', 
                    nombre_empresa = '$this->empresa' 
                WHERE id_persona = $this->id";

		// Ejecutar la consulta para actualizar la tabla tb_proveedores
		$queryProv = Executor::doit($sqlProv);

		// Retornar el resultado de las dos consultas
		return $query && $queryProv;
	}



	public static function getClientIdByPersonId($id_persona)
	{
		if (empty($id_persona)) {
			return null;
		}
		// echo "id_persona: " . $id_persona;
		// Definir la consulta SQL para obtener el id_cliente con base en el id_persona
		$sql = "SELECT c.id_cliente 
				FROM tb_clientes c
				JOIN tb_persona p ON c.id_persona = p.id_persona
				WHERE p.id_persona = $id_persona";

		// Ejecutar la consulta
		$query = Executor::doit($sql);
		$client_id = null;

		// Obtener el resultado
		if ($r = $query[0]->fetch_array()) {
			$client_id = $r['id_cliente'];
		}
		// echo "ID del cliente: " . $client_id;
		return $client_id; // Retorna el id_cliente si existe, de lo contrario, retorna null
	}


	public static function getProviderIdByPersonId($id_persona)
	{
		if (empty($id_persona)) {
			return null;
		}
		echo "id_persona: " . $id_persona;
		// Definir la consulta SQL para obtener el id_cliente con base en el id_persona
		$sql = "SELECT c.id_proveedor
				FROM tb_proveedores c
				JOIN tb_persona p ON c.id_persona = p.id_persona
				WHERE p.id_persona = $id_persona";

		// Ejecutar la consulta
		$query = Executor::doit($sql);
		$client_id = null;

		// Obtener el resultado
		if ($r = $query[0]->fetch_array()) {
			$client_id = $r['id_proveedor'];
		}
		echo "ID del proveedor: " . $client_id;
		return $client_id; // Retorna el id_cliente si existe, de lo contrario, retorna null


	}



	public static function getUsuarioIdByPersonId($id_persona)
	{
		if (empty($id_persona)) {
			return null;
		}
		// echo "id_persona: " . $id_persona;
		// Definir la consulta SQL para obtener el id_cliente con base en el id_persona
		$sql = "SELECT c.id_usuario
				FROM tb_usuarios c
				JOIN tb_persona p ON c.id_persona = p.id_persona
				WHERE p.id_persona = $id_persona";

		// Ejecutar la consulta
		$query = Executor::doit($sql);
		$client_id = null;

		// Obtener el resultado
		if ($r = $query[0]->fetch_array()) {
			$client_id = $r['id_usuario'];
		}
		// echo "ID del cliente: " . $client_id;
		return $client_id; // Retorna el id_cliente si existe, de lo contrario, retorna null
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
		// Definir la consulta SQL para obtener los datos de los proveedores y sus respectivas personas
		$sql = "SELECT 
                p.id_persona AS id, 
                p.nombre AS name, 
                p.apellido_paterno AS lastname, 
				p.apellido_materno AS lastname2,
                p.email AS email1, 
                p.celular AS phone1, 

                p.direccion AS address1, 
                p.fyh_creacion AS created_at,
				prov.nit_empresa AS NIT,
				prov.nombre_empresa AS empresa
            FROM tb_persona p
            JOIN tb_proveedores prov ON p.id_persona = prov.id_persona
            
            ORDER BY p.nombre, p.apellido_paterno";

		// Ejecutar la consulta
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;

		// Recorrer los resultados de la consulta
		while ($r = $query[0]->fetch_array()) {
			$array[$cnt] = new PersonData();
			$array[$cnt]->id = $r['id'];
			$array[$cnt]->name = $r['name'];
			$array[$cnt]->lastname = $r['lastname'];
			$array[$cnt]->lastname2 = $r['lastname2'];
			$array[$cnt]->email1 = $r['email1'];
			$array[$cnt]->phone1 = $r['phone1'];
			$array[$cnt]->address1 = $r['address1'];
			$array[$cnt]->NIT = $r['NIT'];
			$array[$cnt]->empresa = $r['empresa'];
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
