<?php
namespace App\Services;



class MailServices {

    private $mailer;
    private $adminEmail;

    public function __construct(\Swift_Mailer $mailer, $adminEmail)
    {
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
    }

    public function notifyRegistration($mail)
    {


        $message = (new \Swift_Message('Inscription sur Playmore'))
            ->setFrom($this->adminEmail)
            ->setTo($mail)
            ->addPart(
                'Merci de vous être inscrit sur Playmore'
            );

        return $this->mailer->send($message) > 0;
    }


}