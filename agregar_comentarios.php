<?php
session_start();
include 'conexion.php';

// verifico si el usuario tiene sesión iniciada
if (!isset($_SESSION['id_usuario'])) {
    header("location: index.php");
    exit();
}

// verifico si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_ticket = $_GET['id_ticket'];
    $id_usuario = $_SESSION['id_usuario'];
    $comentario = $_POST['comentario'];

    try {
        // consulta para insertar en la base de datos el comentario añadido
        $sql_insert_comentario = "INSERT INTO comentarios_tickets (id_ticket, id_usuario, comentario, fecha_creacion)
                                  VALUES ('$id_ticket', '$id_usuario', '$comentario', NOW())";

        if ($conn->query($sql_insert_comentario) === TRUE) {
            echo '<div class="mensaje-exito">Comentario agregado correctamente.</div>';
        } else {
            throw new Exception('Error al agregar el comentario: ' . $conn->error);
        }
    } catch (Exception $e) {
        echo '<div class="mensaje-error">' . $e->getMessage() . '</div>';
    }
}

// consulta para recoger información del ticket de la base de datos
$id_ticket = $_GET['id_ticket'];

$info_ticket = "SELECT tickets.id_ticket, tickets.titulo, tickets.descripcion_ticket,
                    estado_tickets.estado AS nombre_estado, prioridades.prioridad AS nombre_prioridad,
                    categorias_ticket.categoria AS nombre_categoria,
                    tickets.nombre_cliente AS nombre_usuario, tickets.telefono, tickets.nombre_cliente
                    FROM tickets
                    LEFT JOIN estado_tickets ON tickets.id_estado = estado_tickets.id_estado
                    LEFT JOIN prioridades ON tickets.id_prioridad = prioridades.id_prioridad
                    LEFT JOIN categorias_ticket ON tickets.id_categoria = categorias_ticket.id_categoria
                    WHERE tickets.id_ticket = $id_ticket";

$resultado_ticket_info = $conn->query($info_ticket);

// verifico si el ticket existe
if ($resultado_ticket_info->num_rows > 0) {
    $ticket_info = $resultado_ticket_info->fetch_assoc();
} else {
    echo 'El ticket no existe.';
    exit();
}

// consulta para obtener de la bbdd los comentarios del ticket
$info_comentarios = "SELECT * FROM comentarios_tickets WHERE id_ticket = $id_ticket ORDER BY fecha_creacion DESC";
$resultado_comentarios = $conn->query($info_comentarios);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Comentario</title>
    <link rel="stylesheet" href="css/agregar_comentarios.css">
</head>

<body>
<?php include 'modooscuro.php'; ?>
    
    <h1>Agregar Comentario</h1>

    <button onclick="goBack()" class="boton-regresar">Volver a la página anterior</button>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    
    <div class="contenedor-ticket">
        <div class="encabezado-ticket">ID: <?php echo $ticket_info['id_ticket']; ?> | Título: <?php echo $ticket_info['titulo']; ?></div>
        <div class="detalles-ticket">Nombre Cliente: <?php echo $ticket_info['nombre_cliente']; ?></div>
        <div class="detalles-ticket">Categoría: <?php echo $ticket_info['nombre_categoria']; ?></div>
        <div class="detalles-ticket">Descripción: <?php echo $ticket_info['descripcion_ticket']; ?></div>
        <div class="detalles-ticket">Estado: <?php echo $ticket_info['nombre_estado']; ?></div>
        <div class="detalles-ticket">Prioridad: <?php echo $ticket_info['nombre_prioridad']; ?></div>
        <div class="detalles-ticket">Asignado a: <?php echo $ticket_info['nombre_usuario']; ?></div>
    </div>

    <div class="contenedor-formulario">
        <form method="POST" action="">
            <label for="comentario">Comentario:</label>
            <textarea name="comentario" id="comentario" rows="4" cols="50" required></textarea>
            <input type="submit" value="Agregar Comentario" class="boton-enviar">
        </form>
    </div>

    <div class="contenedor-comentarios">
        <h2>Comentarios</h2>
        <?php
        if ($resultado_comentarios->num_rows > 0) {
            while ($comentario = $resultado_comentarios->fetch_assoc()) {
                // Consulta para obtener el nombre del usuario correspondiente al ID de usuario del comentario
                $id_usuario_comentario = $comentario['id_usuario'];
                $info_nombre = "SELECT nombre FROM usuarios WHERE id_usuario = $id_usuario_comentario";
                $resultado_nombre = $conn->query($info_nombre);

                if ($resultado_nombre->num_rows > 0) {
                    $nombre_usuario_comentario = $resultado_nombre->fetch_assoc()['nombre'];
                } else {
                    $nombre_usuario_comentario = 'Usuario Desconocido';
                }

                echo '<div class="comentario">';
                echo '<div class="comentario-fecha">' . $comentario['fecha_creacion'] . '</div>';
                echo '<div class="comentario-usuario">' . $nombre_usuario_comentario . '</div>';
                echo '<div class="comentario-texto">' . $comentario['comentario'] . '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="no-comentarios">No hay comentarios aún.</div>';
        }
        ?>
    </div>
</body>

</html>
