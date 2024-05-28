<?php
//archivo de conexión con la bbdd. Por defecto, podemos conectarnos al usuario 'root' sin necesidad de contraseña:

$servername = "localhost";
$username = "root";
$password = "";
$database = "resolvesphere";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
