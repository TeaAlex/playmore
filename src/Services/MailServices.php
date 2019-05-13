<?php
namespace App\Services;



use App\Entity\User;

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


    public function forgotPass($mail, $url)
    {
        $message = (new \Swift_Message('Demande de mot de passe Playmore'))
            ->setFrom($this->adminEmail)
            ->setTo($mail)
            ->addPart(
                'Lien de réinitialisation du mot de passe:'." ".$url
            );

        return $this->mailer->send($message) > 0;
    }
    public function notifyPassword($mail)
    {
        $message = (new \Swift_Message('Changement de mot de passe'))
            ->setFrom($this->adminEmail)
            ->setTo($mail)
            ->addPart(
                'Le mot de passe a bien été changer'
            );

        return $this->mailer->send($message) > 0;
    }

    public function notifyOfferDemmand($mail,User $user)
    {
        $message = (new \Swift_Message('Votre offre à une demande'))
            ->setFrom($this->adminEmail)
            ->setTo($mail)
            ->addPart(
                "L'utilisateur ".$user->getUsername()." a répondu à votre offre, vous pouvez l'examiné dans votre profil"
            );

        return $this->mailer->send($message) > 0;
    }
}
