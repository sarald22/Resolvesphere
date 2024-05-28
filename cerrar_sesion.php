<?php
session_start();

// Compruebo si un cliente tiene sesión iniciada
if (isset($_SESSION['id_cliente'])) {
    // Cierro la sesión
    session_unset();
    session_destroy();
    header("Location: index.html"); // Al cerrarse, redirijo a la página de inicio
    exit();
} elseif (isset($_SESSION['id_usuario'])) {
    // Compruebo si un administrador tiene sesión iniciada
    session_unset();
    session_destroy();
    header("Location: index.html"); // Al cerrarse, redirijo a la página de inicio
    exit();
} else {
    // Si no hay sesión activa, redirijo a la página de inicio
    header("Location: index.html");
    exit();
}
?>
