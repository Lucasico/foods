<?php
namespace App\Support\Email;
use Exception;
use stdClass;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    private $mail;

    private $data;

    private $error;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->data = new stdClass();

        //serve settings
        $this->mail->isSMTP();
        $this->mail->isHTML();
        $this->mail->setLanguage("br");

        //padrão de autentificacão
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->CharSet = "utf-8";

        $this->mail->Host = "smtp.gmail.com";
        $this->mail->Port = 587;
        $this->mail->Username = "PlataformaFamilyFoods@gmail.com";
        $this->mail->Password = "family145/*";

    }

    public function add(string $subject, string $body, string $recipient_name, string $recipient_email){

        $this->data->subject = $subject;
        $this->data->body = $body;
        $this->data->recipient_name = $recipient_name;
        $this->data->recipient_email = $recipient_email;
        return $this;
    }

    public function send(string $from_name = "FamilyFoods", string $fro_email = "PlataformaFamilyFoods@gmail.com"){
        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML( $this->data->body);
            $this->mail->addAddress($this->data->recipient_email,$this->data->recipient_name);
            $this->mail->setFrom($fro_email, $from_name);

            $this->mail->send();
            return true;

        }catch (Exception $exception){
            $this->error = $exception;
            return false;
        }
    }

    public function error() {
        return $this->error;
    }
}
