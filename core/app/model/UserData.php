<?php
class UserData
{
	public static $tablename = "user";



	public function __construct()
	{
		$this->name = "";
		$this->lastname = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->created_at = "NOW()";
	}

	public function add()
	{
		$sql = "insert into user (name,lastname,username,email,is_admin,password,created_at) ";
		$sql .= "value (\"$this->name\",\"$this->lastname\",\"$this->username\",\"$this->email\",\"$this->is_admin\",\"$this->password\",$this->created_at)";
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

	// partiendo de que ya tenemos creado un objecto UserData previamente utilizamos el contexto
	public static function update($persona_id, $user_id, $nombre, $apellido_paterno, $apellido_materno, $email, $is_active, $rol_id, $password)
	{
		// Actualizar en tb_persona
		$sql1 = "UPDATE tb_persona 
            SET nombre = \"$nombre\",
                apellido_paterno = \"$apellido_paterno\",
                apellido_materno = \"$apellido_materno\",
                email = \"$email\"
				
            WHERE id_persona = \"$persona_id\"";
		Executor::doit($sql1);

		// Actualizar en tb_usuarios incluyendo el rol
		$sql2 = "UPDATE tb_usuarios 
            SET 
				password = \"$password\",
                is_active = \"$is_active\",
                id_rol = \"$rol_id\"
            WHERE id_usuario = \"$user_id\"";
		Executor::doit($sql2);
	}



	public function update_passwd()
	{
		$sql = "update " . self::$tablename . " set password=\"$this->password\" where id=$this->id";
		Executor::doit($sql);
	}


	public static function getById($id)
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



	public static function getByMail($mail)
	{
		$sql = "select * from " . self::$tablename . " where email=\"$mail\"";
		$query = Executor::doit($sql);
		return Model::one($query[0], new UserData());
	}


	public static function getAll()
	{
		// Consulta para obtener todos los usuarios, incluyendo datos de la tabla tb_persona si es necesario
		$sql = "    
SELECT * FROM tb_persona tp
inner join tb_usuarios tc on tc.id_persona = tp.id_persona
inner join tb_roles r on r.id_rol = tc.id_rol
";

		// Ejecutar la consulta
		$query = Executor::doit($sql);

		// Retornar los resultados usando el modelo adecuado para mapear los datos
		return Model::many($query[0], new UserData());
	}



	public static function getLike($q)
	{
		$sql = "select * from " . self::$tablename . " where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new UserData());
	}
}
