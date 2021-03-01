<?php
namespace App\Service;

use App\Entity\Commande;
use ContainerBV66yzA\getMaker_AutoCommand_MakeAuthService;
use DateInterval;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Constraints\Date;
use Twig\Environment;

class Mail{

    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer,Environment $twig){
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /** Nouvelle commande non payée, destiné au chauffeur
     * @param Commande $commande
     * @return string
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMHAMail(Commande $commande){
        date_default_timezone_set("UTC");
        $dateFin = new \DateTime('now',new \DateTimeZone('UTC'));
        date_timestamp_set($dateFin,$commande->getDate()->getTimestamp());
        $dateFin->add(new DateInterval('PT20M'));
        $dateDebut = new \DateTime('now',new \DateTimeZone('UTC'));
        date_timestamp_set($dateDebut,$commande->getDate()->getTimestamp());
        $dateDebutString =  date_format($dateDebut,"Ymd").'T'.date_format($commande->getDate(),"His")."Z";
        $dateFinString =  date_format($dateFin,"Ymd").'T'.date_format($dateFin,"His")."Z";
        $aller = str_replace(' ','+',$commande->getDepart());
        $arrivee = str_replace(' ','+',$commande->getArrivee());
        $lien = "https://www.google.com/calendar/render?action=TEMPLATE&text=Commande+MHA-VTC&dates=$dateDebutString/$dateFinString&ctz=Europe/Paris&sf=true&output=xml&details=Depart+:+$aller+/Arrivee+:+$arrivee";
        date_default_timezone_set("Europe/Paris");
        $email = (new Email())
            ->from('reservation@mha-vtc.fr')
            ->subject("Nouvelle commande de " . $commande->getNomPrenom())
            ->html($this->twig->render('/mha/mail/commandePassee.html.twig',[
                'commande' => $commande,
                'lien' => $lien
            ]));
        if($commande->getEmail() == "cadarsir@gmail.com"){
            $email->to("cadarsir@gmail.com");
        }
        else{
            $email->to("mhavtc@gmail.com");
        }
        $email->ensureValidity();
        $this->mailer->send($email);

        return '0';
    }

    /** Statut de la commande modifiée, envoyée au client
     * @param $status
     * @param Commande $commande
     * @return string
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
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

    /** Nouvelle commande payée destiné au chauffeur
     * @param $commande
     * @return string
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function mailPayeChauffeur($commande): string
    {
        date_default_timezone_set("UTC");
        $dateFin = new \DateTime('now',new \DateTimeZone('UTC'));
        date_timestamp_set($dateFin,$commande->getDate()->getTimestamp());
        $dateFin->add(new DateInterval('PT20M'));
        $dateDebut = new \DateTime('now',new \DateTimeZone('UTC'));
        date_timestamp_set($dateDebut,$commande->getDate()->getTimestamp());
        $dateDebutString =  date_format($dateDebut,"Ymd").'T'.date_format($commande->getDate(),"His")."Z";
        $dateFinString =  date_format($dateFin,"Ymd").'T'.date_format($dateFin,"His")."Z";
        $aller = str_replace(' ','+',$commande->getDepart());
        $arrivee = str_replace(' ','+',$commande->getArrivee());
        $lien = "https://www.google.com/calendar/render?action=TEMPLATE&text=Commande+MHA-VTC&dates=$dateDebutString/$dateFinString&ctz=Europe/Paris&sf=true&output=xml&details=Depart+:+$aller+/Arrivee+:+$arrivee";
        date_default_timezone_set("Europe/Paris");
        $email = (new Email())
            ->from('reservation@mha-vtc.fr')
            ->subject("Nouvelle réservation Payée de " . $commande->getNomPrenom())
            ->html($this->twig->render('/mha/mail/commandePayee.html.twig',[
                'commande' => $commande,
                'lien' => $lien
            ]));
        if($commande->getEmail() == "cadarsir@gmail.com"){
            $email->to("cadarsir@gmail.com");
        }
        else{
            $email->to("mhavtc@gmail.com");
        }
        $email->ensureValidity();
        $this->mailer->send($email);

        return '0';
    }

    /** Nouvelle commande payée destiné au client
     * @param $commande
     * @return string
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function mailPayeClient(Commande $commande){
        date_default_timezone_set("Europe/Paris");
        $email = (new Email())
            ->from('reservation@mha-vtc.fr')
            ->to($commande->getEmail())
            ->subject("Votre Réservation MHA - VTC" )
            ->html($this->twig->render('/mha/mail/commandePayeeClient.html.twig',[
                'commande' => $commande
            ]));
        $email->ensureValidity();
        $this->mailer->send($email);

        return '0';
    }

}