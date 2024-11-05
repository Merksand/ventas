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
	public function update()
	{
		$sql = "update " . self::$tablename . " set name=\"$this->name\",email=\"$this->email\",username=\"$this->username\",lastname=\"$this->lastname\",is_active=\"$this->is_active\",is_admin=\"$this->is_admin\" where id=$this->id";
		Executor::doit($sql);
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
		$sql = "select * from " . self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0], new UserData());
	}


	public static function getLike($q)
	{
		$sql = "select * from " . self::$tablename . " where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new UserData());
	}
}
