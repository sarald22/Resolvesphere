<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es administrador y tiene sesión iniciada
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Consulta para seleccionar información de la tabla de tickets
$info_tickets = "SELECT tickets.id_ticket, tickets.titulo, tickets.estado, tickets.prioridad, 
                tickets.fecha_creacion, tickets.nombre_usuario, tickets.nombre_cliente,
                tickets.descripcion_ticket, 
                estado_tickets.estado AS estado, prioridades.prioridad AS nombre_prioridad,
                categorias_ticket.categoria AS categoria, clientes.telefono
                FROM tickets
                LEFT JOIN estado_tickets ON tickets.id_estado = estado_tickets.id_estado
                LEFT JOIN prioridades ON tickets.id_prioridad = prioridades.id_prioridad
                LEFT JOIN categorias_ticket ON tickets.id_categoria = categorias_ticket.id_categoria
                LEFT JOIN clientes ON tickets.id_cliente = clientes.id_cliente
                ORDER BY tickets.fecha_creacion DESC"; // Añadido ORDER BY para ordenar por fecha de creación descendente

$resultado_tickets = $conn->query($info_tickets);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_ticket'])) {
        $id_ticket = $_POST['id_ticket'];

        if (isset($_POST['nuevo_estado'])) {
            $nuevo_estado = $_POST['nuevo_estado'];

            // Actualizar el estado del ticket en la base de datos
            $t_actualizar_estado = "UPDATE tickets SET id_estado = (SELECT id_estado FROM estado_tickets WHERE estado = '$nuevo_estado') WHERE id_ticket = $id_ticket";
            if ($conn->query($t_actualizar_estado) === TRUE) {
                echo '<div class="success-message">Estado del ticket actualizado correctamente.</div>';
            } else {
                echo '<div class="error-message">Error al actualizar el estado del ticket.</div>';
            }
        }

        if (isset($_POST['nueva_categoria'])) {
            $nueva_categoria_id = $_POST['nueva_categoria'];

            // Actualizar la categoría del ticket en la base de datos
            $t_actualizar_categoria = "UPDATE tickets SET id_categoria = '$nueva_categoria_id' WHERE id_ticket = $id_ticket";
            if ($conn->query($t_actualizar_categoria) === TRUE) {
                echo '<div class="success-message">Categoría del ticket actualizada correctamente.</div>';
            } else {
                echo '<div class="error-message">Error al actualizar la categoría del ticket.</div>';
            }
        }

        if (isset($_POST['nueva_prioridad'])) {
            $nueva_prioridad_id = $_POST['nueva_prioridad'];

            // Actualizar la prioridad del ticket en la base de datos
            $t_actualizar_prioridad = "UPDATE tickets SET id_prioridad = (SELECT id_prioridad FROM prioridades WHERE prioridad = '$nueva_prioridad_id') WHERE id_ticket = $id_ticket";
            if ($conn->query($t_actualizar_prioridad) === TRUE) {
                echo '<div class="success-message">Prioridad del ticket actualizada correctamente.</div>';
            } else {
                echo '<div class="error-message">Error al actualizar la prioridad del ticket.</div>';
            }
        }

        if (isset($_POST['nuevo_usuario'])) {
            $nuevo_usuario_id = $_POST['nuevo_usuario'];

            // Actualizar el usuario asignado al ticket en la base de datos
            $t_actualizar_usuario = "UPDATE tickets SET id_usuario = '$nuevo_usuario_id' WHERE id_ticket = $id_ticket";
            if ($conn->query($t_actualizar_usuario) === TRUE) {
                echo '<div class="success-message">Usuario asignado al ticket actualizado correctamente.</div>';
            } else {
                echo '<div class="error-message">Error al actualizar el usuario asignado al ticket: ' . $conn->error . '</div>';
            }
        }
    }
}

// Consulta para seleccionar los tickets según su estado
$t_abiertos = "SELECT COUNT(*) AS total_abiertos FROM tickets WHERE id_usuario = $id_usuario AND id_estado = (SELECT id_estado FROM estado_tickets WHERE estado = 'abierto')";
$t_en_proceso = "SELECT COUNT(*) AS total_en_proceso FROM tickets WHERE id_usuario = $id_usuario AND id_estado = (SELECT id_estado FROM estado_tickets WHERE estado = 'en_proceso')";
$t_cerrados = "SELECT COUNT(*) AS total_cerrados FROM tickets WHERE id_usuario = $id_usuario AND id_estado = (SELECT id_estado FROM estado_tickets WHERE estado = 'cerrado')";
$t_totales = "SELECT COUNT(*) AS total_tickets FROM tickets WHERE id_usuario = $id_usuario";

$resultado_abiertos = $conn->query($t_abiertos);
$resultado_en_proceso = $conn->query($t_en_proceso);
$resultado_cerrados = $conn->query($t_cerrados);
$resultado_totales = $conn->query($t_totales);

$total_abiertos = $resultado_abiertos->fetch_assoc()['total_abiertos'];
$total_en_proceso = $resultado_en_proceso->fetch_assoc()['total_en_proceso'];
$total_cerrados = $resultado_cerrados->fetch_assoc()['total_cerrados'];
$total_tickets = $resultado_totales->fetch_assoc()['total_tickets'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Tickets</title>
    <link rel="stylesheet" href="css/admin_tickets.css">
</head>

<body>
<?php include 'modooscuro.php'; ?>
    
    <div class="barra-lateral">
        <h2>Resolvesphere</h2>
            <a href="tickets_abiertos.php">Tickets abiertos</a>
            <a href="tickets_enproceso.php">Tickets en proceso</a>
            <a href="tickets_cerrados.php">Tickets cerrados</a>
            <a href="admin_tickets.php">Total de tickets</a>
    </div>

    <div class="contenido">
        <h1>Administrar Tickets</h1>

        <div class="contenedor-botones">
            <a href="perfil_admin.php" class="boton-perfil">Mi perfil</a>
            <a href="crear_ticket_admin.php" class="boton-crear-ticket">Crear Ticket</a>
            <a href="index.html" class="boton-inicio">Volver a la página principal</a>
        </div>

        <div class="contenedor-contador">
            <p>
                Tickets abiertos: 
                    <?php echo $total_abiertos; ?> 
                    <img src="img/t_abierto.png" alt="Tickets Abiertos" width="30" height="30">
                |
                Tickets en proceso: 
                    <?php echo $total_en_proceso; ?> 
                    <img src="img/t_enproceso.png" alt="Tickets en Proceso" width="30" height="30">
                |
                Tickets cerrados: 
                    <?php echo $total_cerrados; ?> 
                    <img src="img/t_cerrado.png" alt="Tickets Cerrados" width="30" height="30">
                |
                Total de tickets: 
                    <?php echo $total_tickets; ?>
                    <img src="img/t_totales.png" alt="Tickets Totales" width="30" height="30">
            </p>
        </div>

        <?php
        if ($resultado_tickets->num_rows > 0) {
            while ($row = $resultado_tickets->fetch_assoc()) {
                echo '<div class="contenedor-ticket">';

                echo '<div class="cabecera-ticket">ID: ' . $row['id_ticket'] . ' | Título: ' . $row['titulo'] . '</div>';
                echo '<div class="detalles-ticket"> Nombre Cliente: ' . $row['nombre_cliente'] . '</div>';
                echo '<div class="detalles-ticket"> Teléfono Cliente: ';
                    if (!empty($row['telefono'])) {
                        echo $row['telefono'];
                    } else {
                        echo 'No disponible';
                    }

                echo '</div>';
                echo '<div class="detalles-ticket"> Categoría: ' . $row['categoria'] . '</div>';
                echo '<div class="detalles-ticket"> Descripción: ' . $row['descripcion_ticket'] . '</div>';
                echo '<div class="detalles-ticket"> Estado: ' . $row['estado'] . '</div>';
                echo '<div class="detalles-ticket"> Prioridad: ' . $row['prioridad'] . '</div>';
                echo '<div class="detalles-ticket"> Fecha Creación: ' . $row['fecha_creacion'] . '</div>';
                echo '<div class="detalles-ticket"> Asignado a: ' . $row['nombre_usuario'] . '</div>';

                echo '<div class="acciones-ticket">';

                // Formulario para cambiar el estado del ticket
                    echo '<div class="formulario-estado">';
                        echo '<form method="POST" action="">';
                            echo '<input type="hidden" name="id_ticket" value="' . $row['id_ticket'] . '">';

                            echo '<label for="nuevo_estado" style="display: inline-block; margin-right: 5px;">Cambiar Estado:</label>';
                            echo '<select name="nuevo_estado" id="nuevo_estado" style="display: inline-block;">';
                                echo '<option value="abierto">Abierto</option>';
                                echo '<option value="en_proceso">En Proceso</option>';
                                echo '<option value="cerrado">Cerrado</option>';
                            echo '</select>';

                            echo '<button type="submit" class="enlace-estado">Cambiar</button>';
                        echo '</form>';
                    echo '</div>';

                    // Formulario para cambiar la categoría del ticket
                    echo '<div class="formulario-categoria">';
                        echo '<form method="POST" action="">';
                            echo '<input type="hidden" name="id_ticket" value="' . $row['id_ticket'] . '">';

                            echo '<label for="nueva_categoria" style="display: inline-block; margin-right: 5px;">Cambiar Categoría:</label>';
                            echo '<select name="nueva_categoria" id="nueva_categoria" style="display: inline-block;">';
                                echo '<option value="1">Hardware</option>';
                                echo '<option value="2">Software</option>';
                                echo '<option value="3">Conexión</option>';
                            echo '</select>';

                            echo '<button type="submit" class="enlace-categoria">Cambiar</button>';
                        echo '</form>';
                    echo '</div>';

                    // Formulario para cambiar la prioridad del ticket
                    echo '<div class="formulario-prioridad">';
                        echo '<form method="POST" action="">';
                            echo '<input type="hidden" name="id_ticket" value="' . $row['id_ticket'] . '">';

                            echo '<label for="nueva_prioridad" style="display: inline-block; margin-right: 5px;">Cambiar Prioridad:</label>';
                            echo '<select name="nueva_prioridad" id="nueva_prioridad" style="display: inline-block;">';
                                echo '<option value="baja">Baja</option>';
                                echo '<option value="media">Media</option>';
                                echo '<option value="alta">Alta</option>';
                                echo '<option value="muy_alta">Muy alta</option>';
                            echo '</select>';

                            echo '<button type="submit" class="enlace-prioridad">Cambiar</button>';
                        echo '</form>';
                    echo '</div>';

                    // Formulario para cambiar el usuario asignado al ticket
                    echo '<div class="formulario-usuario">';
                        echo '<form method="POST" action="">';
                            echo '<input type="hidden" name="id_ticket" value="' . $row['id_ticket'] . '">';

                            echo '<label for="nuevo_usuario" style="display: inline-block; margin-right: 5px;">Cambiar Asignado:</label>';
                            echo '<select name="nuevo_usuario" id="nuevo_usuario" style="display: inline-block;">';
                                
                                // Obtener usuarios disponibles
                                $usuarios_disponibles_query = "SELECT id_usuario, nombre FROM usuarios";
                                $result_usuarios = $conn->query($usuarios_disponibles_query);
                                
                                if ($result_usuarios->num_rows > 0) {
                                    while ($usuario = $result_usuarios->fetch_assoc()) {
                                        echo '<option value="' . $usuario['id_usuario'] . '">' . $usuario['nombre'] . '</option>';
                                    }
                                }
                            
                            echo '</select>';

                            echo '<button type="submit" class="enlace-usuario">Cambiar</button>';
                        echo '</form>';
                    echo '</div>';

                    echo '<br>';
                    echo '<a class="enlace-agregar-comentario" href="agregar_comentarios.php?id_ticket=' . $row['id_ticket'] . '"> · Añadir comentario · </a>';
                echo '</div>'; // contenedor acciones ticket
                echo '</div>'; // contenedor general tickets
            }
        } else {
            echo 'No hay tickets disponibles.';
        }
        ?>
    </div>
</body>

</html>

