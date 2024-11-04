<?php

if (count($_POST) > 0) {
	// Obtener el cliente por ID
	$user = PersonData::getById($_POST["user_id"]);

	// Asignar los datos del formulario a las propiedades correspondientes
	$user->name = $_POST["name"];
	$user->lastname = $_POST["lastname"];
	$user->lastname2 = $_POST["lastname2"]; // Si tienes un campo para el segundo apellido
	$user->address = $_POST["address"];
	$user->email = $_POST["email"];
	$user->phone = $_POST["phone"];
	$user->CI = $_POST["CI"]; // Asignación para el campo de identificación si aplica
	$user->update_client();

	// Redirigir a la vista de clientes
	print "<script>window.location='index.php?view=clients';</script>";
}
