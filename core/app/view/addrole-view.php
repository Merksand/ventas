<?php 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el nombre del rol desde el formulario
    $roleName = $_POST['role_name'];


    // Llamar al método estático para agregar el rol
    $person = new PersonData();
    $person->addRole($roleName);

    // Redirigir a la página de roles o mostrar un mensaje
    // header("Location: index.php?view=roles");
    print "<script>window.location='index.php?view=addrol';</script>";
    exit;
}
