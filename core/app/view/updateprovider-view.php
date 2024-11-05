<?php

// Verificar si se ha enviado el formulario con datos
if (count($_POST) > 0) {
    // Obtener el ID del proveedor (usuario) desde el formulario
    $user = PersonData::getProviderById($_POST["user_id"]); // Asumiendo que 'getById' obtiene los datos del proveedor
	echo "<pre>";
	print_r($user);
	echo "</pre>";
    // Actualizar los datos del proveedor con los valores recibidos del formulario
    $user->name = $_POST["name"];
    $user->lastname = $_POST["lastname"];
    $user->lastname2 = $_POST["lastname2"]; // Si es necesario recibir el apellido materno
    $user->address = $_POST["address"]; // Ajuste para coincidir con la propiedad correcta
    $user->email = $_POST["email"]; // Ajuste para coincidir con la propiedad correcta
    $user->phone = $_POST["phone"]; // Ajuste para coincidir con la propiedad correcta
    $user->NIT = $_POST["NIT"]; // Asegúrate de tener este campo en el formulario
    $user->empresa = $_POST["empresa"]; // Asegúrate de tener este campo en el formulario

    // Llamar al método para actualizar los datos del proveedor
    $user->update_provider();

    // Redirigir a la página de proveedores después de la actualización
    print "<script>window.location='index.php?view=providers';</script>";
}

?>
