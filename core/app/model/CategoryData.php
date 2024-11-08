<?php
class CategoryData
{
	public static $tablename = "tb_categoria";

	public $is_active;
	public function __construct()
	{
		$this->id = "";
		$this->name = "";
		$this->lastname = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->created_at = "NOW()";
		$this->is_active = 1;
	}
	public function updateActive($is_active)
	{
		$sql = "UPDATE tb_categoria SET is_active = " . (int)$is_active . " WHERE id = " . (int)$this->id;
		Executor::doit($sql);
	}



	public function add()
	{
		$sql = "insert into tb_categoria (name,created_at) ";
		$sql .= "value (\"$this->name\",$this->created_at)";
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

	public static function getActiveCategories()
	{
		$sql = "SELECT * FROM tb_categoria WHERE is_active = 1";
		$query = Executor::doit($sql);
		return Model::many($query[0], new CategoryData());
	}

	public static function getInactiveCategories()
	{
		$sql = "SELECT * FROM tb_categoria WHERE is_active = 0";
		$query = Executor::doit($sql);
		return Model::many($query[0], new CategoryData());
	}


	// partiendo de que ya tenemos creado un objecto CategoryData previamente utilizamos el contexto
	public function update()
	{
		$sql = "UPDATE " . self::$tablename . " SET name=\"$this->name\", is_active=$this->is_active WHERE id=$this->id";
		Executor::doit($sql);
	}





	public static function getById($id)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE id = $id";
		$query = Executor::doit($sql);
		$found = null;

		if ($r = $query[0]->fetch_array()) {
			$data = new CategoryData();
			$data->id = $r['id'];
			$data->name = $r['name'];
			$data->created_at = $r['created_at'];
			$data->is_active = $r['is_active']; // Asigna is_active desde la base de datos
			$found = $data;
		}
		return $found;
	}


	public static function getAll()
	{
		$sql = "select * from " . self::$tablename. " where is_active = 1 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while ($r = $query[0]->fetch_array()) {
			$array[$cnt] = new CategoryData();
			$array[$cnt]->id = $r['id'];
			$array[$cnt]->name = $r['name'];
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
			$array[$cnt] = new CategoryData();
			$array[$cnt]->id = $r['id'];
			$array[$cnt]->name = $r['name'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}
}
