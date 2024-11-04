<?php

if(count($_POST)>0){
	$user = new PersonData();
	// $user->name = $_POST["name"];
	// $user->lastname = $_POST["lastname"];
	// $user->address1 = $_POST["address1"];
	// $user->email1 = $_POST["email1"];
	// $user->phone1 = $_POST["phone1"];


	$user->name = $_POST["name"];
	$user->lastname = $_POST["lastname1"];
	$user->lastname2 = $_POST["lastname2"];
	$user->address = $_POST["address1"];
	$user->email = $_POST["email1"];
	$user->phone = $_POST["phone1"];
	$user->CI = $_POST["CI"];

	$user->add_client();

	// echo "Nombre: ".$user->name. "<br>";
	// echo "Apellido: ".$user->lastname. "<br>";
	// echo "Apellido2: ".$user->lastname2. "<br>";
	// echo "CI: ".$user->CI. "<br>";
	// echo "Email: ".$user->email. "<br>";
	// echo "Direccion: ".$user->address. "<br>";
	// echo "Telefono: ".$user->phone. "<br>";	

print "<script>window.location='index.php?view=clients';</script>";


}


?>