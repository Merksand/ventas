<?php
class UserData
{
	public static $tablename = "user";



	public function __construct()
	{
		// $this->name = "";
		// $this->lastname = "";
		// $this->email = "";
		// $this->image = "";
		// $this->password = "";
		// $this->created_at = "NOW()";
	}

	public static function add($nombre, $apellido_paterno, $apellido_materno, $celular, $email, $direccion, $password, $rol)
	{
		// Inserción en tb_persona
		$sql_persona = "INSERT INTO tb_persona (nombre, apellido_paterno, apellido_materno, celular, email, direccion, fyh_creacion)
                    VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$celular', '$email', '$direccion', NOW())";

		// Ejecutar inserción en tb_persona
		Executor::doit($sql_persona);

		// Obtener el id_persona recién generado
		$id_persona = Database::getCon()->insert_id;

		// Inserción en tb_usuarios
		$sql_usuario = "INSERT INTO tb_usuarios (id_persona, password, id_rol)
                    VALUES ('$id_persona', '$password', '$rol')";

		// Ejecutar inserción en tb_usuarios
		Executor::doit($sql_usuario);
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
	public static function update($id_persona, $user_id, $nombre, $apellido_paterno, $apellido_materno, $email, $telefono, $direccion, $rol, $password = null, $is_active)
	{
		// Actualizar datos en tb_persona
		$sql_persona = "UPDATE tb_persona SET 
							nombre = '$nombre', 
							apellido_paterno = '$apellido_paterno', 
							apellido_materno = '$apellido_materno', 
							celular = '$telefono', 
							email = '$email', 
							direccion = '$direccion' 
							WHERE id_persona = '$id_persona'";

		Executor::doit($sql_persona);

		// Actualizar datos en tb_usuarios, incluyendo la contraseña solo si se proporciona
		if ($password) {
			$sql_usuario = "UPDATE tb_usuarios SET 
								password = '$password', 
								id_rol = '$rol', 
								is_active = '$is_active'
								WHERE id_persona = '$id_persona'";
		} else {
			$sql_usuario = "UPDATE tb_usuarios SET 
								id_rol = '$rol', 
								is_active = '$is_active' 
								WHERE id_persona = '$id_persona'";
		}

		Executor::doit($sql_usuario);
	}



	public function update_passwd()
	{
		$sql = "update " . self::$tablename . " set password=\"$this->password\" where id=$this->id";
		Executor::doit($sql);
	}


	public static function getById($id)
	{
		// Definir la consulta SQL con alias para evitar conflictos de nombres de columnas
		$sql = "SELECT 
                u.id_usuario AS usuario_id,
                u.id_persona AS usuario_id_persona,
                u.password AS usuario_password,
                u.id_rol AS usuario_id_rol,
                u.is_active AS is_active,
                p.nombre AS persona_nombre,
                p.apellido_paterno AS persona_apellido_paterno,
                p.apellido_materno AS persona_apellido_materno,
                p.celular AS persona_celular,
                p.email AS persona_email,
                p.direccion AS persona_direccion,
                p.fyh_creacion AS persona_fyh_creacion,
                p.fyh_actualizacion AS persona_fyh_actualizacion,
                r.nombre_rol AS rol_nombre,
                r.fyh_creacion AS rol_fyh_creacion,
                r.fyh_actualizacion AS rol_fyh_actualizacion
            FROM tb_usuarios u
            INNER JOIN tb_persona p ON u.id_persona = p.id_persona
            INNER JOIN tb_roles r ON u.id_rol = r.id_rol
            WHERE u.id_persona = $id";

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
