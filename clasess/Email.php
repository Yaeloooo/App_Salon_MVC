<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public $email;
    public $nombre;
    public $token;


    public function __construct($nombre, $email, $token)
    {

        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        //crear el objeto de email

        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['EMAIL_PORT'];

        $mail->setFrom('cuentas@salon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        //setHTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . " </strong>Has creado tu cuenta en appsalon, Confírmala desde el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui: <a href='".  $_ENV['APP_URL'] ."/confirmar-cuenta?token="
            . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tú no registraste esta cuenta ignora el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //enviar email


        $mail->Body  = $contenido;

        $mail->send();
    }

    public function enviarInstrucciones()
    {


        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['EMAIL_PORT'];

        $mail->setFrom('cuentas@salon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Reestablece tu password';

        //setHTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . " </strong>Has solicitado reestablecer tu password</p>";
        $contenido .= "<p>Presiona aqui: <a href='".  $_ENV['APP_URL'] ."/recuperar?token="
            . $this->token . "'>Reestablecer password</a></p>";
        $contenido .= "<p>Si tú no registraste esta cuenta ignora el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //enviar email


        $mail->Body  = $contenido;

        $mail->send();
    }
}
