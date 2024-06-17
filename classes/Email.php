<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token){
        $this->email= $email;
        $this->nombre=$nombre;
        $this->token=$token;


    }

    public function enviarConfirmacion(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('tasknexus@nexus.com');
        $mail->addAddress('tasknexus@nexus.net', 'tasknexus.com');     //Add a recipient
        $mail->Subject = 'Confirma Tu cuenta de TaskNexus';
        $mail->isHTML(true);
        $mail->CharSet='UTF-8';
        $contenido="<html>";
        $contenido.="<p><strong>Hola ". $this->nombre ."</strong> Has Creado tu cuenta en TaskNexus, confirmala presionando el siguiente enlace</p>"  ;
        $contenido.="<p>Presiona aqui: <a href='" . $_ENV['APP_URL'] . "/confirmar?token=" . $this->token . "'>Confirma Tu cuenta</a> </p>";
        $contenido.= "<p>Si tu no realizaste el proceso de crear la cuenta en TaskNexus puedes ignorar este mensaje</p>";
        $contenido.="</html>";

        $mail->Body=$contenido;
        $mail->send();
    }

    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('tasknexus@nexus.com');
        $mail->addAddress('tasknexus@nexus.net', 'tasknexus.com');     
        $mail->Subject = 'Recupera Tu contraseña de TaskNexus';
        $mail->isHTML(true);
        $mail->CharSet='UTF-8';
        $contenido="<html>";
        $contenido.="<p><strong>Hola ". $this->nombre ."</strong> ¿Quieres restablecer tu contraseña en TaskNexus?, Crea una nueva presionando el siguiente Boton</p>"  ;
        $contenido.="<p>Presiona aqui: <a href='" . $_ENV['APP_URL'] . "/restablecer?token=" . $this->token . "'>Restablece Tu contraseña</a> </p>";
        $contenido.= "<p>Si tu no realizaste el proceso  puedes ignorar este mensaje</p>";
        $contenido.="</html>";

        $mail->Body=$contenido;
        $mail->send();
    }
}