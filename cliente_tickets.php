<?php
session_start();
include 'conexion.php';

// verifico si un cliente tiene sesión iniciada
if (!isset($_SESSION['id_cliente'])) {
    header("location: index.php");
    exit();
}

$id_cliente = $_SESSION['id_cliente'];

// consulta para obtener de la base de datos el número total de tickets del cliente
$total_tickets_cliente = "SELECT COUNT(*) AS total_tickets FROM tickets WHERE id_cliente = '$id_cliente'";
$resultado_total_tickets_cliente = $conn->query($total_tickets_cliente);

// consulta para recoger información del ticket
$info = "SELECT tickets.id_ticket, tickets.titulo, tickets.fecha_creacion, tickets.estado, 
    tickets.nombre_usuario, tickets.descripcion_ticket, 
    categorias_ticket.categoria AS nombre_categoria, 
    prioridades.prioridad AS nombre_prioridad
    FROM tickets
    LEFT JOIN categorias_ticket ON tickets.id_categoria = categorias_ticket.id_categoria
    LEFT JOIN prioridades ON tickets.id_prioridad = prioridades.id_prioridad
    WHERE id_cliente = '$id_cliente'";

$resultado = $conn->query($info);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tickets Cliente</title>
    <link rel="stylesheet" href="css/cliente_tickets.css">
</head>

<body>
<?php include 'modooscuro.php'; ?>

    <div class="barra-lateral">
        <h2>Resolvesphere</h2>
        <a href="index_pag1.php" class="recomendaciones-link">Recomendaciones</a>
        <a href="index_pag2.php" class="problemas-frecuentes-link">Problemas frecuentes</a>
        <a href="index_pag4.php" class="contacto-link">Contacto</a>
    </div>

    <h1>Mis Tickets</h1>

    <p>
    <a href="perfil_cliente.php" class="enlace-perfil">Mi perfil</a>
    <a href="crear_ticket_cliente.php" class="enlace-crear-ticket">Crear Ticket</a>
    <a href="index.html" class="enlace-inicio">Volver a la página principal</a>
    </p>

    <?php
    // consulta para obtener el número de tickets del cliente
    $total_tickets_cliente = "SELECT COUNT(*) AS total_tickets FROM tickets WHERE id_cliente = '$id_cliente'";
    $resultado_total_tickets_cliente = $conn->query($total_tickets_cliente);

    if ($resultado_total_tickets_cliente) {
        $total_tickets_cliente = $resultado_total_tickets_cliente->fetch_assoc()['total_tickets'];
    } else {
        echo '<div class="mensaje-error">Error al obtener el número total de tickets del cliente.</div>';
    }
    ?>

    <div class="contador-tickets">
        <p>
            Tickets totales: 
            <?php echo $total_tickets_cliente; ?> 
            <img src="img/totales2.png" alt="Total de tickets: " width="30" height="30">
        </p>
    </div>

    <br>

    <?php
    // para mostrar la información del ticket
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            echo '<div class="contenedor-ticket">';
                echo '<div class="encabezado-ticket">ID: ' . $fila['id_ticket'] . ' | Título: ' . $fila['titulo'] . '</div>';
                echo '<div class="detalles-ticket">Fecha Creación: ' . $fila['fecha_creacion'] . '</div>';
                echo '<div class="detalles-ticket">Título: ' . $fila['titulo'] . '</div>';
                echo '<div class="detalles-ticket">Estado: ' . $fila['estado'] . '</div>';
                echo '<div class="detalles-ticket">Asignado a: ' . $fila['nombre_usuario'] . '</div>';
                echo '<div class="detalles-ticket">Categoría: ' . $fila['nombre_categoria'] . '</div>';
                echo '<div class="detalles-ticket">Descripción: ' . $fila['descripcion_ticket'] . '</div>';
                echo '<div class="detalles-ticket">Prioridad: ' . $fila['nombre_prioridad'] . '</div>';
                echo '<a class="enlace-anadir-comentario" href="agregar_comentarios.php?id_ticket=' . $fila['id_ticket'] . '"> · Añadir comentario · </a>';
            echo '</div>';
        }
    } else {
        echo '<div class="no-hay-tickets">No hay tickets disponibles.</div>';
    }
    ?>

</body>
</html>
