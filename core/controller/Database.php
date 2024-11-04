<?php
class Database
{
	public static $db;

	public static $con;
	function __construct()
	{
		$this->user = "root";
		$this->pass = "Miguelangelomy1";
		$this->host = "localhost";
		$this->ddbb = "inventiolite";
	}

	function connect()
	{
		$con = new mysqli($this->host, $this->user, $this->pass, $this->ddbb);
		$con->query("set sql_mode=''");
		return $con;
	}


	public static function getCon()
	{
		if (self::$con == null && self::$db == null) {
			self::$db = new Database();
			self::$con = self::$db->connect();
		}
		return self::$con;
	}

	public static function getLastId() {
        $con = Database::getCon(); // Obtener la conexión a la base de datos
        return $con->insert_id; // Retorna el último ID insertado
    }
}
