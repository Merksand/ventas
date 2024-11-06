<?php
if(count($_POST)>0){
    // Crear una nueva instancia de PersonData
    $user = new PersonData();

    // Asignar los valores de los campos del formulario a los atributos del objeto
    $user->name = $_POST["name"];
    $user->lastname = $_POST["lastname"];
    $user->lastname2 = $_POST["lastname2"]; // Apellido Materno
    $user->address = $_POST["address"];
    $user->email = $_POST["email"];
    $user->phone = $_POST["phone"];
    $user->empresa = $_POST["empresa"];
    $user->NIT = $_POST["NIT"];

    // Llamar al método para agregar el proveedor
    $user->add_provider();

    // Redirigir a la lista de proveedores
    print "<script>window.location='index.php?view=providers';</script>";
}
?>
