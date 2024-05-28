<?php
session_start();
include 'conexion.php';

//verificamos los datos introducidos, comparandolos con la tabla de usuarios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $contrasena = $_POST['contrasena'];

    $sql = "SELECT * FROM usuarios WHERE id_usuario = ? AND contrasena = ?";
    $declaracion = $conn->prepare($sql);
    $declaracion->bind_param("is", $id_usuario, $contrasena);
    $declaracion->execute();
    $resultado = $declaracion->get_result();

    if ($resultado->num_rows == 1) {
        $fila = $resultado->fetch_assoc();
        $_SESSION['id_usuario'] = $fila['id_usuario'];
        $_SESSION['rol'] = $fila['rol'];
        
        $_SESSION['nombre'] = $nombre_usuario;
        
        if ($fila['rol'] === 'administrador') {
            header("location: admin_tickets.php");
            exit;
        } else {
            $error = "Acceso denegado. No eres un administrador.";
        }
    } else {
        $error = "Credenciales incorrectas. Inténtelo de nuevo.";
    }

    $declaracion->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión Administrador</title>
    <link rel="stylesheet" href="css/login_admin.css">
</head>

<body>
<?php include 'modooscuro.php'; ?>
    
    <h1>Administrador<img src="img/login_admin2.png" alt="loginadmin" width="50" height="50"></h1>
    <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="id_usuario">ID:</label>
        <input type="text" id="id_usuario" name="id_usuario" required><br><br>
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required><br><br>
        <button type="submit">Iniciar Sesión</button>
    </form>
    <br>
    <?php
        $fechaHoraActual = date('Y-m-d H:i:s');
        echo $fechaHoraActual;
    ?>
    <br><br>
    <a href="index.html" class="button">Volver a la página principal</a>    

    <footer>
        <div class="copyright">
            &copy; Sara Lamas · ASIR 2
        </div>
    </footer>
    
</body>
</html>