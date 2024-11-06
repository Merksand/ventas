<?php

class Executor
{

	// public static function doit($sql)
	// {
	// 	$con = Database::getCon();
	// 	if (Core::$debug_sql) {
	// 		print "<pre>" . $sql . "</pre>";
	// 	}
	// 	return array($con->query($sql), $con->insert_id);
	// }



	public static function doit($sql)
	{
		$con = Database::getCon();

		if (Core::$debug_sql) {
			print "<pre>" . $sql . "</pre>";
		}

		// Ejecutar la consulta
		$result = $con->query($sql);

		if ($result === false) {
			// Capturar y mostrar el error SQL
			echo "<pre><b>SQL Error:</b> " . $con->error . "</pre>";
			return [false, null];
		}

		// Verificar si es una consulta SELECT o no
		if (is_object($result)) {
			// Retornar resultado para SELECT
			return [$result, null];
		} else {
			// Retornar el ID de la última inserción para INSERT/UPDATE
			return [true, $con->insert_id];
		}
	}
}
