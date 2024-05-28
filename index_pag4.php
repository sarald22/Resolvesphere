<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="css/index_pag4.css">
</head>
<body>
<?php include 'modooscuro.php'; ?>
    
<div class="contenedor">
        <button onclick="goBack()" class="boton-volver">Volver a la página anterior</button>   
        <h1><div class="contenedor-logo">
            <img src="img/index7.png" alt="Logo" class="logo">
        </div>
        </h1>
        

<br>
        <div class="info-contacto">
            <h2>Contacto</h2>
            <ul>
                <li>Correo electrónico: slamasdiz@danielcastelao.org</li>
                <li>Teléfono: +34 678901234  |  +34 986012345</li>
                <li>Ubicación: García Barbón n48, 36201 Vigo, Pontevedra</li>
               <li>Redes Sociales: 
                    <a href="#">Facebook</a>
                    <a href="#">Twitter</a>
                    <a href="#">LinkedIn</a>
               </li>
            </ul>
        </div>

<br>
        <div class="formulario-contacto">
            <h2>Formulario de Contacto</h2>
            <p>Para hablar con administración rellena este formulario con tu pregunta y se enviará un correo.
                Será respondido en las siguientes 48 horas.</p>
            <form action="formulario_contacto.php" method="post">
                <input type="text" name="name" placeholder="Nombre y apellidos" required>
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <textarea name="message" placeholder="Escribe tu pregunta" rows="5" required></textarea>
                <button type="submit">Enviar</button>
            </form>
        </div>

<br>
        <div class="mapa">
            <h2>Ubicación</h2>
            <p>Encuéntranos en esta ubicación:</p>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2953.9629718001734!2d-8.717126523424824!3d42.23660404289158!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd2f62662d23b87d%3A0x209b0d27afed70eb!2sCentro%20Concertado%20de%20Formaci%C3%B3n%20Profesional%20Daniel%20Castelao!5e0!3m2!1ses!2ses!4v1716078039531!5m2!1ses!2ses" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</body>
</html>
