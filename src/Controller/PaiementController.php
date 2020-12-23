<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Repository\PaiementRepository;
use App\Service\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;

class PaiementController extends MhaController
{

    /**
     * @Route("/reservationEnvoyee" , name="reservationEnvoyee")
     */
    public function reservationEnvoyee(Request $request,CommandeRepository $commandeRepository)
    {
        $commande = $commandeRepository->find($request->get('commandeId'));
        return $this->render('mha/pages/reservationEnvoyee.html.twig',['light' => true,'commande' => $commande]);
    }

    /**
     * @Route("/reservationPayee" , name="reservationPayee")
     */
    public function reservationPayee(Request $request,CommandeRepository $commandeRepository, EntityManagerInterface $em,Mail $mail)
    {
        $commande = $commandeRepository->find($request->get('commandeId'));
        $paiement = $commande->getPaiements()->first();
        if($request->get('session_id') == $paiement->getCode()){
            $paiement->setPaid(true);
        }

        $em->persist($paiement);
        $em->flush();
        $mail->mailPayeClient($commande);
        $mail->mailPayeChauffeur($commande);
        return $this->render('mha/pages/reservationPayee.html.twig',['light' => true,'commande' => $commande]);
    }

    /**
     * @Route("/reservationAnnulee" , name="reservationAnnulee")
     */
    public function reservationAnnulee()
    {
        return $this->render('mha/pages/reservationAnnulee.html.twig',['light' => true]);
    }


}