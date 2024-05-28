<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil</title>
    <link rel="stylesheet" href="css/perfil_cliente.css">
</head>

<body>
<?php include 'modooscuro.php'; ?>
    
    <div class="contenedor">
        <h1 class="titulo-perfil">Mi perfil</h1>
        <?php
        session_start();
        include 'conexion.php';

        // Verifico si hay una sesión activa
        if (!isset($_SESSION['id_cliente'])) {
            header("location: index.php");
            exit();
        }

        $id_cliente = $_SESSION['id_cliente'];
        
        // Si hay una sesión de cliente iniciada, se comparará el id del cliente en las tablas y se mostrará su información
        $info = "SELECT * FROM clientes WHERE id_cliente = '$id_cliente'";
        $resultado = $conn->query($info);

        if ($resultado->num_rows == 1) {
            $fila = $resultado->fetch_assoc();
            echo '<div class="info-perfil">';
                echo '<label for="nombre">ID:</label>';
                echo '<span>' . $fila['id_cliente'] . '</span><br>';

                echo '<label for="nombre">Nombre:</label>';
                echo '<span>' . $fila['nombre'] . '</span><br>';

                echo '<label for="apellidos">Apellidos:</label>';
                echo '<span>' . $fila['apellidos'] . '</span><br>';

                echo '<label for="dni">DNI:</label>';
                echo '<span>' . $fila['dni'] . '</span><br>';

                echo '<label for="n_telf">Teléfono:</label>';
                echo '<span>' . $fila['n_telf'] . '</span><br>';
                
                echo '<label for="email">Email:</label>';
                echo '<span>' . $fila['email'] . '</span><br>';
            echo '</div>';

            echo '<a href="cliente_tickets.php" class="boton-cerrar-sesion">Volver a mis tickets</a>
                    <a href="cerrar_sesion.php" class="boton-cerrar-sesion">Cerrar sesión</a> ';

        } else {
            echo "Error al cargar el perfil.";
        }
        ?>
    </div>
</body>

</html>
