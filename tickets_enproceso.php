<?php
session_start();
include 'conexion.php';

// para verificar si el usuario tiene sesión iniciada y es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// consulta para obtener info de los tickets con estado=en_proceso o id_estado=2
$info_tickets = "SELECT tickets.id_ticket, tickets.titulo, tickets.estado, tickets.prioridad, 
                tickets.fecha_creacion, tickets.nombre_usuario, tickets.nombre_cliente,
                tickets.descripcion_ticket, 
                estado_tickets.estado AS nombre_estado, prioridades.prioridad AS nombre_prioridad,
                categorias_ticket.categoria AS nombre_categoria, clientes.telefono
                FROM tickets
                LEFT JOIN estado_tickets ON tickets.id_estado = estado_tickets.id_estado
                LEFT JOIN prioridades ON tickets.id_prioridad = prioridades.id_prioridad
                LEFT JOIN categorias_ticket ON tickets.id_categoria = categorias_ticket.id_categoria
                LEFT JOIN clientes ON tickets.id_cliente = clientes.id_cliente
                WHERE tickets.estado = 'en_proceso' OR tickets.id_estado = 2";

$resultado_tickets = $conn->query($info_tickets);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets en Proceso</title>
    <link rel="stylesheet" href="css/admin_tickets.css">
</head>

<body>
<?php include 'modooscuro.php'; ?>
    
    <div class="barra-lateral">
        <h2>Opciones de Tickets</h2>
        <ul>
            <li><a href="tickets_abiertos.php">Tickets abiertos</a></li>
            <li><a href="tickets_enproceso.php">Tickets en proceso</a></li>
            <li><a href="tickets_cerrados.php">Tickets cerrados</a></li>
            <li><a href="admin_tickets.php">Tickets totales</a></li>
        </ul>
    </div>

    <div class="contenido">
        <h1>Tickets en Proceso<img src="img/t_enproceso.png" alt="Tickets en Proceso" width="40" height="40"></h1>


        <?php
        if ($resultado_tickets->num_rows > 0) {
            while ($fila = $resultado_tickets->fetch_assoc()) {
                echo '<div class="contenedor-ticket">';

                echo '<div class="cabecera-ticket">ID: ' . $fila['id_ticket'] . ' | Título: ' . $fila['titulo'] . '</div>';
                echo '<div class="detalles-ticket"> Nombre Cliente: ' . $fila['nombre_cliente'] . '</div>';
                echo '<div class="detalles-ticket"> Teléfono Cliente: ';
                    if (!empty($fila['telefono'])) {
                        echo $fila['telefono'];
                    } else {
                        echo 'No disponible';
                    }
                echo '</div>';
                echo '<div class="detalles-ticket"> Categoría: ' . $fila['nombre_categoria'] . '</div>';
                echo '<div class="detalles-ticket"> Descripción: ' . $fila['descripcion_ticket'] . '</div>';
                echo '<div class="detalles-ticket"> Estado: ' . $fila['nombre_estado'] . '</div>';
                echo '<div class="detalles-ticket"> Prioridad: ' . $fila['nombre_prioridad'] . '</div>';
                echo '<div class="detalles-ticket"> Fecha Creación: ' . $fila['fecha_creacion'] . '</div>';
                echo '<div class="detalles-ticket"> Asignado a: ' . $fila['nombre_usuario'] . '</div>';

                echo '<div class="acciones-ticket">';

                // Formulario para cambiar el estado del ticket
                echo '<div class="formulario-estado">';
                    echo '<form method="POST" action="">';
                        echo '<input type="hidden" name="id_ticket" value="' . $fila['id_ticket'] . '">';

                        echo '<label for="nuevo_estado" style="display: inline-block; margin-right: 5px;">Cambiar Estado:</label>';
                            echo '<select name="nuevo_estado" id="nuevo_estado" style="display: inline-block;">';
                            echo '<option value="abierto">Abierto</option>';
                            echo '<option value="en_proceso">En Proceso</option>';
                            echo '<option value="cerrado">Cerrado</option>';
                            echo '</select>';
            
                        echo '<button type="submit" class="enlace-estado">Cambiar</button>';
                    echo '</form>';
                echo '</div>'; // Cierre div formulario-estado



                // Formulario para cambiar la categoría del ticket
                echo '<div class="formulario-categoria">';
                    echo '<form method="POST" action="">';
                        echo '<input type="hidden" name="id_ticket" value="' . $fila['id_ticket'] . '">';

                            echo '<label for="nueva_categoria" style="display: inline-block; margin-right: 5px;">Cambiar Categoría:</label>';
                            echo '<select name="nueva_categoria" id="nueva_categoria" style="display: inline-block;">';
                            echo '<option value="1">Hardware</option>';
                            echo '<option value="2">Software</option>';
                            echo '<option value="3">Conexión</option>';
                            echo '</select>';

                        echo '<button type="submit" class="enlace-categoria">Cambiar</button>';
                    echo '</form>';
                echo '</div>'; // Cierre div formulario-categoria


                echo '<a class="enlace-agregar-comentario" href="agregar_comentarios.php?id_ticket=' . $fila['id_ticket'] . '"> · Agregar Comentario · </a>';

                echo '</div>'; // Cierre div acciones-ticket
                
                echo '</div>'; // Cierre div contenedor-ticket
            }
        } else {
            echo 'No hay tickets disponibles.';
        }
        ?>
    </div>
</body>

</html>