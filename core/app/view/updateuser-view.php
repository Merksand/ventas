<?php

if (count($_POST) > 0) {
	$is_active = isset($_POST["is_active"]) ? 1 : 0;
	
	// Llamar al método para actualizar el usuario y su información personal
	UserData::update(
		$_POST["id_persona"],
		$_POST["user_id"],
		$_POST["name"],
		$_POST["lastname"],
		$_POST["lastname2"],
		$_POST["email"],
		$is_active,
		$_POST["rol"],
		!empty($_POST["password"]) ? $_POST["password"] : null // Contraseña si se proporciona
    );

	

	// Redirigir o mostrar mensaje de éxito
	print "<script>window.location='index.php?view=users';</script>";
}
