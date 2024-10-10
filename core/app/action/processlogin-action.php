<?php

// Verificar si la sesión no está iniciada
if (!isset($_SESSION["username_id"])) {
    // $user = "admin@gmail.com";
    // $pass = 'admin'; 
    $user = $_POST['username'];
    $pass = $_POST["password"]; 

    // Conexión a la base de datos
    $base = new Database();
    $con = $base->connect();


    // Consulta para verificar el usuario en la base de datos
    $sql = "SELECT *
            FROM tb_persona p 
            JOIN tb_usuarios u ON p.id_persona = u.id_persona 
            WHERE p.email = '$user'
            AND u.password = '$pass'";

    // Ejecutar la consulta
    $query = $con->query($sql);
    $found = false;
    $userid = null;

    // echo $query;

    if ($query && $query->num_rows > 0) {
        $found = true;
        while ($row = $query->fetch_assoc()) {
            $userid = $row['id_persona'];
        }
    } else {
        $found = false;
    }








    // Si se encontró el usuario, iniciar sesión
    if ($found == true) {
        $_SESSION['user_id'] = $userid;
        // print "Cargando ... $user";


        print "<script>window.location='index.php?view=home';</script>";
    } else {
        // echo $sql;


        // Si no se encontró el usuario, redirigir al login
        print "<script>window.location='index.php?view=login';</script>";
    }
} else {
    echo "ya existe una sesión iniciada";
    // Si ya existe una sesión iniciada, redirigir a la página de inicio
    // print "<script>window.location='index.php?view=home';</script>";
}
