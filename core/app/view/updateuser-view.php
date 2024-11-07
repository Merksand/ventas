<?php

if (count($_POST) > 0) {
    // Verificar si el campo de actividad está marcado
    $is_active = isset($_POST["is_active"]) ? 1 : 0;

    // Llamar al método update con los datos del formulario
    UserData::update(
        $_POST["id_persona"],             // ID de la persona
        $_POST["user_id"],                // ID del usuario (para tb_usuarios)
        $_POST["name"],                   // Nombre
        $_POST["lastname"],               // Apellido Paterno
        $_POST["lastname2"],              // Apellido Materno
        $_POST["email"],                  // Email
        $_POST["telefono"],               // Teléfono
        $_POST["direccion"],              // Dirección
        $_POST["rol"],                    // Rol
        // !empty($_POST["password"]) ? sha1(md5($_POST["password"])) : null, 
        !empty($_POST["password"]) ? $_POST["password"] : null, 
        $is_active                        // Estado activo o inactivo
    );

    // Redirigir o mostrar mensaje de éxito
    print "<script>window.location='index.php?view=users';</script>";
}
