<?php
session_start();
include 'conexion.php';

// verificar si hay una sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $departamento = $_POST['departamento'];

    // consulta para actualizar la informacion en la bbdd
    $info_actualizacion = "UPDATE usuarios SET nombre = '$nombre', email = '$email', departamento = '$departamento' WHERE id_usuario = '$id_usuario'";
    if ($conn->query($info_actualizacion) === TRUE) {
        echo "Datos actualizados correctamente.";
    } else {
        echo "Error al actualizar los datos: " . $conn->error;
    }
}

// consulta para obtener los datos actuales del usuario
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
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/editar_perfil.css">
</head>
<body>
<?php include 'modooscuro.php'; ?>
    
    <div class="contenedor-editar-perfil">
        <h1 class="titulo-editar-perfil">Mi perfil</h1>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $fila['nombre']; ?>" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $fila['email']; ?>" required>
            <br>
            <label for="departamento">Departamento:</label>
            <input type="text" id="departamento" name="departamento" value="<?php echo $fila['departamento']; ?>" required>
            <br>
            <br>
            <input type="submit" value="Guardar Cambios" class="boton-enviar">
            <br>
            <a href="perfil_admin.php" class="boton-volver">Página anterior</a>

        </form>
    </div>
</body>
</html>
<?php
} else {
    echo "Error al cargar el perfil.";
}
?>
