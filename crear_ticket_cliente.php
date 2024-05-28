<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_cliente'])) {
    $_SESSION['id_cliente'] = 1;
    $_SESSION['nombre_cliente'] = 'Nombre del Cliente';
    $_SESSION['descripcion_cliente'] = 'Descripción del Cliente';
    $_SESSION['telefono'] = '123456789';
    $_SESSION['prioridad'] = 'baja';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // verifico que las variables necesarias están definidas y no vacías
    if (
        isset($_SESSION['id_usuario'], $_POST['titulo'], $_POST['categoria'], $_POST['descripcion_ticket'])
        && !empty($_POST['titulo']) && !empty($_POST['categoria']) && !empty($_POST['descripcion_ticket'])
    ) {
        // obtenemos información del ticket
        $id_usuario = $_SESSION['id_usuario'];
        $titulo = $_POST['titulo'];
        $categoria = $_POST['categoria'];
        $descripcion_ticket = $_POST['descripcion_ticket'];

        // obtenemos información del cliente
        $id_cliente = isset($_SESSION['id_cliente']) ? $_SESSION['id_cliente'] : null;
        $info_cliente = "SELECT * FROM clientes WHERE id_cliente = ?";
        $declaracion_cliente = $conn->prepare($info_cliente);
        $declaracion_cliente->bind_param("i", $id_cliente);
        $declaracion_cliente->execute();
        $resultado_cliente = $declaracion_cliente->get_result();

        if ($resultado_cliente->num_rows == 1) {
            $fila_cliente = $resultado_cliente->fetch_assoc();
            $nombre_cliente = $fila_cliente['nombre'];
            $descripcion_cliente = $fila_cliente['descripcion_cliente'];
            $telefono = $fila_cliente['telefono'];
            $prioridad = $fila_cliente['prioridad'];

            // con esto insertamos la información metida en el formulario en la bbdd
            $info_ticket = "INSERT INTO tickets (titulo, descripcion_ticket, estado, id_estado, prioridad, fecha_creacion, id_usuario, id_cliente, id_categoria, nombre_cliente, descripcion_cliente, telefono)
                VALUES (?, ?, 'abierto', '1', ?, NOW(), ?, ?, ?, ?, ?, ?)";

            // "sssiisssi" representa las variables introducidas, cada una es una letra. 
            // 's' indica que el parametro es string;  'i' indica que el parametro es integer.
            $declaracion_ticket = $conn->prepare($info_ticket);
            $declaracion_ticket->bind_param("sssiisssi", $titulo, $descripcion_ticket, $prioridad, $id_usuario, $id_cliente, $categoria, $nombre_cliente, $descripcion_cliente, $telefono);

            if ($declaracion_ticket->execute()) {
                echo "Ticket creado correctamente.";
            } else {
                echo "Error al crear el ticket: " . $declaracion_ticket->error;
            }

            $declaracion_ticket->close();
        } else {
            echo "Error: No se encontraron datos del cliente.";
        }

        $declaracion_cliente->close();
    } else {
        echo "Error: Todos los campos del formulario son requeridos y no deben estar vacíos.";
    }
}
?>




<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Ticket</title>
    <link rel="stylesheet" href="css/crear_ticket.css">
</head>

<body>
<?php include 'modooscuro.php'; ?>
    
    <h1>Crear Ticket</h1>

    <a href="cliente_tickets.php" class="boton-volver">Volver a la página de tickets</a>

    <div class="contenedor-forms">
        <form method="POST" action="">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" required>
            <br>
            <label for="categoria">Categoría:</label>
            <select name="categoria" id="categoria" required>
                <option value="1">Hardware</option>
                <option value="2">Software</option>
                <option value="3">Conexión</option>
            </select>
            <br>
            <label for="descripcion_ticket">Descripción del Ticket:</label>
            <textarea name="descripcion_ticket" id="descripcion_ticket" rows="4" cols="50" required></textarea>
            <br>
            <input type="file" name="adjuntos" id="adjuntos">
            <input type="submit" value="Crear Ticket" class="boton-enviar">
        </form>
    </div>

</body>

</html>