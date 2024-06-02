<?php

session_start();
include 'conexion.php';

//verificamos los datos introducidos, comparandolos con la tabla de clientes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST['id_cliente'];
    $dni = $_POST['dni'];

// recogemos la informacion de la tabla de clientes 
    $info = "SELECT * FROM clientes WHERE id_cliente = ? AND dni = ?";
    $declaracion = $conn->prepare($info);
    $declaracion->bind_param("is", $id_cliente, $dni);
    $declaracion->execute();
    $result = $declaracion->get_result();

    if ($result->num_rows == 1) {

        $_SESSION['id_cliente'] = $id_cliente;
        

        $_SESSION['nombre'] = $nombre_usuario;
        $_SESSION['rol'] = $rol_usuario;

        header("location: cliente_tickets.php");
        exit;
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
    <title>Iniciar Sesión Cliente</title>
    <link rel="stylesheet" href="css/login_clientes.css">
</head>

<body>

<?php include 'modooscuro.php'; ?>
    
    <h1>Clientes<img src="img/login_cliente2.png" alt="logincliente" width="50" height="50"></h1>
    <p>Bienvenido a nuestra plataforma de tickets.</p>


    <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
<!-- formulario para iniciar sesion -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="id_cliente">ID:</label>
        <input type="text" id="id_cliente" name="id_cliente" required><br><br>
        <label for="dni">Contraseña:</label>
        <input type="text" id="dni" name="dni" required><br><br>
        <button type="submit">Iniciar Sesión</button>
    </form>
    <br>
    <?php
        $fechaHoraActual = date('Y-m-d H:i:s');
        echo $fechaHoraActual;
    ?>
    <br>
    <a href="index.html" class="button">Volver a la página principal</a>    

    
    <footer>
        <div class="copyright">
            &copy; Sara Lamas · ASIR 2
        </div>
    </footer>
    
</body>

</html>