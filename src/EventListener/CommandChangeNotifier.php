<?php
namespace App\EventListener;

use App\Entity\Commande;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Service\Mail;

class CommandChangeNotifier
{
    private $mail;
    function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }
// the entity listener methods receive two arguments:
// the entity instance and the lifecycle event
public function postUpdate(Commande $commande, LifecycleEventArgs $event)
{
    if($commande->getValidation() == true){
        $this->mail->sendMail("confirmation",$commande);
    }
    else{
        $this->mail->sendMail("cancel",$commande);
    }
}
}