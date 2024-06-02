<?php
session_start();

// compruebo si un cliente tiene sesión iniciada
if (isset($_SESSION['id_cliente'])) {
    // cierro la sesión
    session_unset();
    session_destroy();
    header("Location: index.html"); // al cerrarse, redirijo a la página de inicio
    exit();
    
} elseif (isset($_SESSION['id_usuario'])) {
    // compruebo si un administrador tiene sesión iniciada
    session_unset();
    session_destroy();
    header("Location: index.html"); // al cerrarse, redirijo a la página de inicio
    exit();

} else {
    // si no hay sesión activa, redirijo a la página de inicio
    header("Location: index.html");
    exit();
}
?>
