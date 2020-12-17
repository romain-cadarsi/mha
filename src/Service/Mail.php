<?php
namespace App\Service;

use App\Entity\Commande;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class Mail{

    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer,Environment $twig){
        $this->mailer = $mailer;
        $this->twig = $twig;
        date_default_timezone_set("Europe/Paris");
    }
    public function sendMHAMail(Commande $commande){
        $email = (new Email())
            ->from('reservation@mha-vtc.fr')
            ->to('cadarsir@gmail.com')
            ->subject("Nouvelle commande de " . $commande->getNomPrenom())
            ->html($this->twig->render('/mha/mail/commandePassee.html.twig',[
                'commande' => $commande
            ]));
        $email->ensureValidity();
        $this->mailer->send($email);

        return '0';
    }



    public function sendMail($status ,Commande $commande){
        $subject = $status == 'confirmation' ? "Confirmation de votre commande"  : "Annulation de votre Commande";
        if($status == 'confirmation'){
            $html = $this->twig->render('/mha/mail/commandeClient.html.twig',[
                'commande' => $commande
            ]);
        }
        else{
            $html = $this->twig->render('/mha/mail/annulationClient.html.twig',[
                'commande' => $commande
            ]);
        }

        $email = (new Email())
            ->from('reservation@mha-vtc.fr')
            ->to($commande->getEmail())
            ->subject($subject)
            ->html($html);
        $email->ensureValidity();
        $this->mailer->send($email);

        return '0';

    }
}