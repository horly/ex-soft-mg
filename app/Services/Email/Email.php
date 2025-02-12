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
    protected $from_address;

    function __construct()
    {
        $this->app_name = config('app.name'); //EXAD
        $this->username = config('app.mail_username'); //webmaster@exadgroup.org
        $this->from_address = config('app.mail_from_address'); //sales@exadgroup.org

        $this->mail = new PHPMailer;
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0; //pas d'afficahe de debug mais si nous voulons afficher les erreurs il faut le mettre à 2
        $this->mail->Port = config('app.mail_port');
        $this->mail->Host = config('app.mail_host'); //mail.exadgroup.org
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Username = config('app.mail_username'); //webmaster@exadgroup.org
        $this->mail->Password = config('app.mail_password'); //hG9-vTunHvb3a5U
        $this->mail->CharSet  = "UTF-8";
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;


    }

    //pour l'envoie du mail
    public function sendHtmlEmail($subject, $emailUser, $name, $message)
    {
        $this->mail->Subject = $subject;
        $this->mail->setFrom($this->username, $this->app_name);
        $this->mail->addReplyTo($this->username, $this->app_name);
        $this->mail->addAddress($emailUser);
        $this->mail->IsHTML(true);
        $this->mail->Body = $message;

        return $this->mail->send();

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

        return $this->sendHtmlEmail($subject, $email, $name, $message);
    }

    //send change email request
    public function changeEmailAdressRequest($user)
    {
        $email = $user->email;
        $name = $user->name;
        $secret = $user->two_factor_secret;
        $firstname = explode(" ", $name);

        $device = new Device;
        $browser = $device->getBrowser();
        $platform = $device->getPlatform();

        $dateImm = new \DateTimeImmutable;
        $dateTime = DateTime::createFromImmutable($dateImm);
        $date = $dateTime->format('Y-m-d H:i:s');
        //dd($date->format('Y-m-d H:i:s'));

        $subject = "[" . config('app.name') . "] " . __('profile.email_address_change_request');
        $message = view('mail.change-email-address-request')
                    ->with([
                        'name' => $firstname[0],  //on passe nos variables dans la vue
                        'subject' => $subject,
                        'verification_code_secret' => $secret,
                        'time_date' => $date,
                        'browser' => $browser,
                        'platform' => $platform
            ]);

        $this->sendHtmlEmail($subject, $email, $name, $message);
    }

    //send change password request
    public function changePasswordRequest($user)
    {
        $email = $user->email;
        $name = $user->name;
        $secret = $user->two_factor_secret;
        $firstname = explode(" ", $name);

        $device = new Device;
        $browser = $device->getBrowser();
        $platform = $device->getPlatform();

        $dateImm = new \DateTimeImmutable;
        $dateTime = DateTime::createFromImmutable($dateImm);
        $date = $dateTime->format('Y-m-d H:i:s');
        //dd($date->format('Y-m-d H:i:s'));

        $subject = "[" . config('app.name') . "] " . __('profile.reset_password_request');
        $message = view('mail.change-password-request')
                    ->with([
                        'name' => $firstname[0],  //on passe nos variables dans la vue
                        'subject' => $subject,
                        'verification_code_secret' => $secret,
                        'time_date' => $date,
                        'browser' => $browser,
                        'platform' => $platform
            ]);

        $this->sendHtmlEmail($subject, $email, $name, $message);
    }

    //invite user management
    public function inviteUser($user, $userInv, $passwordClear)
    {
        $email = $user->email;
        $name = $user->name;
        $secret = $user->two_factor_secret;
        $firstname = explode(" ", $name);

        $nameExp = $userInv->name;

        $dateImm = new \DateTimeImmutable;
        $dateTime = DateTime::createFromImmutable($dateImm);
        $date = $dateTime->format('Y-m-d H:i:s');
        //dd($date->format('Y-m-d H:i:s'));

        $subject = "[" . config('app.name') . "] " . __('auth.email_verification');
        $message = view('mail.user-invitation-join')
                    ->with([
                        'name' => $firstname[0],  //on passe nos variables dans la vue
                        'nameExp' => $nameExp,
                        'subject' => $subject,
                        'verification_code_secret' => $secret,
                        'time_date' => $date,
                        'email' => $email,
                        'password' => $passwordClear,
            ]);

        $this->sendHtmlEmail($subject, $email, $name, $message);
    }

    public function send_email_invoice($from_email, $to_email, $concern_email, $greeting, $recipient_name, $message_email, $url, $entreprise_name)
    {
        $this->mail->Subject = $concern_email;
        $this->mail->setFrom($this->username, $entreprise_name);
        $this->mail->addReplyTo($from_email, $entreprise_name);
        $this->mail->addAddress($to_email);


        $message = view('mail.send_email_invoice')
                    ->with([
                        'recipient_name' => $recipient_name,
                        'greeting' => $greeting,
                        'subject' => $concern_email,
                        'message_email' => $message_email,
                        'url' => $url
            ]);

        $this->mail->IsHTML(true);
        $this->mail->Body = $message;

        return $this->mail->send();
    }

    //pour l'envoie du mail
    public function sendEmail($subject, $emailUser, $name, $message, $isHtml)
    {
        $this->mail->Subject = $subject;
        $this->mail->setFrom($this->username, $this->app_name); //from contact user email
        $this->mail->addReplyTo($emailUser, $name); //
        $this->mail->addAddress($this->from_address); //to sales@exadgroup.org
        $this->mail->IsHTML($isHtml);
        $this->mail->Body = $message;

        return $this->mail->send(); //true or false


        //ici c'est pour le teste et voir les erreurs
        /*if(!$this->mail->send())
        {
            return "error : " . $this->mail->ErrorInfo;
        }
        else
        {
            return "success";
        }*/
    }

    public function sendContactMessage($fullname, $email_addr, $phoneNumber, $message_text, $subject)
    {
        $email = $email_addr;
        $name = $fullname;
        $phone_number = $phoneNumber;
        $message = $message_text;

        //$subject = __('home.information');
        $messageHtml = view('mail.message-email')
                    ->with([
                        'name' => $name,
                        'message' => $message,
                        'phone_number' => $phone_number,
                        'email' => $email
            ]);

        return $this->sendEmail($subject, $email, $name, $messageHtml, true);
    }
}
