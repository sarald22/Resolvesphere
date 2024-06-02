<?php
    session_start();
    include 'conexion.php';
    // verifico si hay una sesión activa
    if (!isset($_SESSION['id_usuario'])) {
        header("location: index.php");
        exit();
    }

    $id_usuario = $_SESSION['id_usuario'];
    // si hay una sesión de cliente iniciada, se comparará el id del usuario en las tablas y se mostrará su información
    $info = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
    $resultado = $conn->query($info);

    if ($resultado->num_rows == 1) {
        $fila = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil</title>
    <link rel="stylesheet" href="css/perfil_admin.css">

</head>
<body>
<?php include 'modooscuro.php'; ?>
    
    <div class="contenedor-perfil">
        <h1 class="titulo-perfil">Mi perfil</h1>
        <div class="info-perfil">
            <!-- mostramos la info del administrador -->
            <p><strong>Nombre:</strong> <?php echo $fila['nombre']; ?></p>
            <p><strong>Email:</strong> <?php echo $fila['email']; ?></p>
            <p><strong>Rol:</strong> <?php echo $fila['rol']; ?></p>
            <p><strong>Departamento:</strong> <?php echo $fila['departamento']; ?></p>
        </div>

        <div class="enlaces-perfil">
            <a href="editar_perfil.php">Editar Perfil</a>
            <a href="cerrar_sesion.php">Cerrar Sesión</a>
            <?php
                if ($fila['rol'] == 'administrador') {
                    echo "<a href='admin_tickets.php'>Gestionar Tickets</a>";
                }
            ?>
        </div>
    </div>
</body>
</html>

<?php
} else {
    echo "Error al cargar el perfil.";
}
?>