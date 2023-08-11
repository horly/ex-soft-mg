<?php

namespace App\Services\Email;

/**
 * installation
 * - composer require phpmailer/phpmailer
 */

use App\Services\Device\Device;
use DateTime;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    protected $mail;
    protected $app_name;
    protected $username;

    function __construct()
    {
        $this->app_name = config('app.name'); //in .en file = EXADERP!
        $this->username = config('app.mail_username'); //in .en file = contact@exaderp.com

        $this->mail = new PHPMailer;
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0; //pas d'afficahe de debug mais si nous voulons afficher les erreurs il faut le mettre à 2
        $this->mail->Port = config('app.mail_port'); 
        $this->mail->Host = config('app.mail_host'); 
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $this->username;
        $this->mail->Password = config('app.mail_password');
        $this->mail->CharSet  = "UTF-8";
    }

    //pour l'envoie du mail
    public function sendHtmlEmail($subject, $emailUser, $name, $message)
    {
        $this->mail->Subject = $subject;
        $this->mail->setFrom($this->username, $this->app_name);
        $this->mail->addReplyTo($this->username, $this->app_name);
        $this->mail->addAddress($emailUser, $name);
        $this->mail->IsHTML(true);
        $this->mail->Body = $message;
        $this->mail->send();

        //ici c'est pour le teste et voir les erreurs
        /*if(!$mail->send())
        {
            //return "error : " . $this->$mail->ErrorInfo;
        }
        else
        {
            return "success";
        }*/
    }

    //pour l'envoie du mail
    public function sendTextEmail($subject, $emailUser, $name, $message)
    {
        $this->mail->Subject = $subject;
        $this->mail->setFrom($this->username, $this->app_name);
        $this->mail->addReplyTo($this->username, $this->app_name);
        $this->mail->addAddress($emailUser, $name);
        $this->mail->IsHTML(false);
        $this->mail->Body = $message;
        $this->mail->send();

        //ici c'est pour le teste et voir les erreurs
        /*if(!$mail->send())
        {
            //return "error : " . $this->$mail->ErrorInfo;
        }
        else
        {
            return "success";
        }*/
    }


    //send verification code 
    public function sendVerifactionCode($user, $code, $secret)
    {
        $email = $user->email;
        $name = $user->name;
        $firstname = explode(" ", $name);

        $device = new Device;
        $browser = $device->getBrowser();
        $platform = $device->getPlatform();

        $dateImm = new \DateTimeImmutable;
        $dateTime = DateTime::createFromImmutable($dateImm);
        $date = $dateTime->format('Y-m-d H:i:s');
        //dd($date->format('Y-m-d H:i:s'));

        $subject = "[" . config('app.name') . "] " . __('auth.please_verify_your_device');
        $message = view('mail.login-mail-authentication')
                    ->with([
                        'name' => $firstname[0],  //on passe nos variables dans la vue
                        'subject' => $subject,
                        'verification_code' => $code,
                        'verification_code_secret' => $secret, 
                        'time_date' => $date,
                        'browser' => $browser,
                        'platform' => $platform
            ]);

        $this->sendHtmlEmail($subject, $email, $name, $message);
    }
}