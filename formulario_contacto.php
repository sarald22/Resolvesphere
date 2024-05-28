<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['name']);
    $correo = htmlspecialchars($_POST['email']);
    $mensaje = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);

    try {
        // servidor SMTP de gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '22sarald22@gmail.com';
        $mail->Password = 'qbid iuoz gvif zhgb';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //remitente y destinatarios
        $mail->setFrom($correo, $nombre);
        $mail->addAddress('slamasdiz@danielcastelao.org', 'ResolveSphere');
        $mail->addCC($correo);

        $mail->addEmbeddedImage('img/index8.png', 'logo_img');

        // Mensaje
        $mail->isHTML(true);
        $mail->Subject = "Formulario de contacto, $nombre";
        $mail->Body = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 20px;
                }
                .contenedor {
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    margin-bottom: 20px;
                    border: 1px solid #e0e0e0;
                }
                h1 {
                    color: #a200ff;
                    text-align: center;
                    font-size: 22px;
                    margin-bottom: 20px;
                }
                p {
                    color: #333333;
                    line-height: 1.6;
                    margin: 0 0 10px;
                }
                .pie-pagina {
                    margin-top: 20px;
                    text-align: center;
                    color: #555;
                    font-size: 14px;
                }
                .pie-pagina p {
                    margin: 5px 0;
                }
                .logo {
                    display: block;
                    margin: 0 auto 20px;
                }
                .resaltado {
                    background-color: #f0e6ff;
                    padding: 5px;
                    border-radius: 3px;
                    color: #a200ff;
                    font-weight: bold;
                }
                .mensaje-centrado {
                    text-align: center;
                    margin-top: 20px;
                }
                .mensaje-centrado p {
                    font-size: 16px;
                    color: #333;
                }
                .boton-volver {
                    margin: 10px;
                    padding: 8px 16px;
                    font-size: 16px;
                    background-color: #a200ff;
                    color: #fff;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }
                .boton-volver:hover {
                    background-color: #8f00cc;
                }
            </style>
        </head>
        <body>
            <div class='contenedor'>
                <h1>Formulario de contacto</h1>
                <img src='cid:logo_img' alt='Logo' class='logo'>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Correo:</strong> $correo</p>
                <p><strong>Mensaje:</strong></p>
                <p>$mensaje</p>
            </div>
        </body>
        </html>";
        $mail->AltBody = "Nombre: $nombre\nCorreo: $correo\n\nMensaje:\n$mensaje";

        $mail->send();
        echo "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 20px;
                }
                .mensaje-centrado {
                    text-align: center;
                    margin-top: 20px;
                }
                .mensaje-centrado p {
                    font-size: 16px;
                    color: #333;
                }
                .boton-volver {
                    margin: 10px;
                    padding: 8px 16px;
                    font-size: 16px;
                    background-color: #a200ff;
                    color: #fff;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }
                .boton-volver:hover {
                    background-color: #8f00cc;
                }
            </style>
        </head>
        <body>
            <div class='mensaje-centrado'>
                <p>Mensaje enviado correctamente. Será respondido en las siguientes 48 horas.</p>
                <button class='boton-volver' onclick=\"window.location.href='index_pag4.php'\">Página anterior</button>
            </div>
        </body>
        </html>";
    } catch (Exception $e) {
        echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
    }
} else {
    header("Location: index.html");
    exit();
}
?>
