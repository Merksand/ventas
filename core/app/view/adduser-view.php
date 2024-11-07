<?php

if (count($_POST) > 0) {
    // Preparar los datos para pasarlos como argumentos al método add
    $nombre = $_POST["nombre"];
    $apellido_paterno = $_POST["apellido_paterno"];
    $apellido_materno = $_POST["apellido_materno"] ?? null; // Opcional
    $celular = $_POST["celular"];
    $email = $_POST["email"];
    $direccion = $_POST["direccion"];
    // $password = sha1(md5($_POST["password"])); 
    $password = !empty($_POST["password"]) ? $_POST["password"] : null; 
    $rol = $_POST["rol"];

	// Llamar al método para agregar el usuarioi
    // Llamar al método add pasando los datos como argumentos
    UserData::add($nombre, $apellido_paterno, $apellido_materno, $celular, $email, $direccion, $password, $rol);

    // Redirigir después de agregar el usuario
    print "<script>window.location='index.php?view=users';</script>";
}
?>
