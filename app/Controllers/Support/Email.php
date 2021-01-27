<?php

namespace Myapp\Controllers\Support;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use stdClass;

class Email
{
    private $mail;
    private $data;
    
    public function __construct()
    {
        $this->mail = new PHPMailer();
        $this->data = new stdClass();

        $this->mail->isSMTP();
        $this->mail->isHTML();
        $this->mail->setLanguage('br');

        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = "tls";
        $this->mail->CharSet = "utf-8";

        $this->mail->Host = MAIL['host'];
        $this->mail->Port = MAIL['port'];
        $this->mail->Username = MAIL['username'];
        $this->mail->Password = MAIL['password'];
    }

    public function add($subject, $body, $recipient_name, $recipient_email)
    {
        $this->data->subject = $subject;
        $this->data->body = $body;
        $this->data->recipient_name = $recipient_name;
        $this->data->recipient_email = $recipient_email;
        
        return $this;
    }
    
    public function send($from_name = MAIL['from_name'], $from_email = MAIL['from_email'])
    {
        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->body);
            $this->mail->addAddress($this->data->recipient_email, $this->data->recipient_name);
            $this->mail->setFrom($from_email, $from_name);

            $this->mail->send();
            return true;
        } catch(Exception $ex) {
            return false;
        }
    }
}