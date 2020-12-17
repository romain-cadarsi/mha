<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Service\ClientService;
use App\Service\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends MhaController
{
    /**
     * @Route("/xhr/storeCommande", name="store commande")
     */
    public function storeCommande(Request $request, EntityManagerInterface $entityManager, ClientService $clientService, Mail $mailService)
    {
        $commande = new Commande();
        $user = null;
        $result = json_decode($request->get("reservation"), true);
        date_default_timezone_set("Europe/Paris");
        $date = date_create($result['_date'], new \DateTimeZone('Europe/Paris'));
        $commande->setArrivee($result['_adresseArrivee'])
            ->setDate($date)
            ->setEffectue(false)
            ->setEmail($result['_mail'])
            ->setNomPrenom($result['_nom'] . " " . $result['_prenom'])
            ->setTelephone($result['_numero'])
            ->setPrixApproximatif($result['_prix'])
            ->setDepart($result['_adresseDepart'])
            ->setValidation(false)
            ->setCode(bin2hex(random_bytes(16)));
        if (!$clientService->findIfExists($commande->getEmail())) {
            $user = new Client();
            $user->setName($commande->getNomPrenom())
                ->setMail($commande->getEmail())
                ->setTelephone($commande->getTelephone())
                ->setPoints($commande->getPrixApproximatif());
        } else {
            $user = $entityManager->getRepository(Client::class)->findOneBy(['mail' => $commande->getEmail()]);
            $user->setPoints($user->getPoints() + $commande->getPrixApproximatif());
        }
        $entityManager->persist($commande);
        $entityManager->persist($user);
        $entityManager->flush();

        $mailService->sendMHAMail($commande);

        return $this->render('blank.html.twig', [
        ]);
    }

    /**
     * @Route("/confirmCommande", name="Confirm")
     */
    public function Confirm(Request $request, EntityManagerInterface $entityManager)
    {
        if ($request->get('id')) {
            $id = $request->get('id');
            $commande = $entityManager->getRepository(Commande::class)->find($id);
            if ($request->get('code') == $commande->getCode()) {
                $commande->setValidation(true);
                $entityManager->persist($commande);
                $entityManager->flush();
                echo("Commande validée, un mail à été envoyé au client.");
            } else echo("Vous ne devriez pas être là.");
        } else echo("Vous ne devriez pas être là.");
        return $this->render('blank.html.twig', [
        ]);
    }


    /**
     * @Route("/reservation" , name="reservation")
     */
    public function reservation()
    {
        return $this->render('mha/reservationWebApp.html.twig');
    }



    /**
     * @Route("/xhr/doesThisClientStored" , name="clientStored", methods = "GET")
     */
    public function clientStored(Request $request, ClientService $clientService)
    {
        $mail = $request->get('mail');
        if ($clientService->findIfExists($mail)) {
            return new Response('
                                <div class="h5 mb-4 text-center"
                                 style="font-size: 1.6em;font-weight: 500;line-height: 1.5em;letter-spacing: -0.79px;">
                                On se connait ?
                            </div>
                            <p>Il semble que vous déjà effectué un trajet avec mha-vtc, nous ajouterons les points de
                                cette course sur votre compte.</p>');
        } else {
            return new Response('
                                <div class="h5 mb-4 text-center"
                                 style="font-size: 1.6em;font-weight: 500;line-height: 1.5em;letter-spacing: -0.79px;">
                                Vous avez l\'air nouveau, Bienvenue !
                            </div>
                            <p>Les points liés à votre commande seront automatiquement liés à votre adresse mail, vous pourrez les utiliser bientôt pour avoir 
                            des avantages !</p>');
        }


    }


}