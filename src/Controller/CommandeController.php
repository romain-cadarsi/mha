<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Paiement;
use App\Entity\User;
use App\Repository\CommandeRepository;
use App\Service\ClientService;
use App\Service\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommandeController extends MhaController
{
    /**
     * @Route("/xhr/storeCommande", name="storeCommande")
     */
    public function storeCommande(Request $request, EntityManagerInterface $entityManager, ClientService $clientService, Mail $mailService)
    {
        $commande = new Commande();
        $user = null;
        $result = json_decode($request->get("reservation"), true);
        $dateEnvoyee = json_decode($request->get('date'),true);
        $date = (date_create($dateEnvoyee['y'] . "-" . (intval($dateEnvoyee['m']) + 1) . "-" . $dateEnvoyee['d']  . " " . $dateEnvoyee['h'] . ":" . $dateEnvoyee['i']));

        $commande->setArrivee($result['_adresseArrivee'])
            ->setDate($date)
            ->setEffectue(false)
            ->setEmail($result['_mail'])
            ->setNomPrenom($result['_nom'] . " " . $result['_prenom'])
            ->setTelephone($result['_numero'])
            ->setDepart($result['_adresseDepart'])
            ->setPrixApproximatif($this->getPrix($this->getDistance($commande->getDepart(),$commande->getArrivee()),intval(date_format($commande->getDate(),"H"))))
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
        if($result['_paiementMethod'] == "carte"){
            $paiement = new Paiement();
            $paiement->setPaid(false)
                ->setAt(new \DateTime())
                ->setCommande($commande)
                ->setPrix($commande->getPrixApproximatif())
                ->setCode('tmp');

            $commande->setPaiementMethod('carte');
            $entityManager->persist($paiement);
            $entityManager->persist($commande);
            $entityManager->persist($user);
            $entityManager->flush();
            $options = [
                'payment_method_types' => ['card'],
                'line_items' => [],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('reservationPayee',[],UrlGeneratorInterface::ABSOLUTE_URL)."?commandeId=".$commande->getId()."&session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' =>  $this->generateUrl('reservationAnnulee',[],UrlGeneratorInterface::ABSOLUTE_URL),
            ];


            Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

            array_push($options['line_items'],
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => "Trajet le " . date_format($commande->getDate(),'d/m H:i') . " de " . $commande->getDepart() . " à ". $commande->getArrivee(),
                        ],
                        'unit_amount' => $commande->getPrixApproximatif() * 100,
                    ],
                    'quantity' => 1 ,
                ]);
            $stripe = (\Stripe\Checkout\Session::create($options));
            $paiement->setCode($stripe['id']);
            $entityManager->persist($commande);
            $entityManager->flush();
            $response = json_encode($stripe);

        }
        else{
            $commande->setPaiementMethod('espece');
            $entityManager->persist($commande);
            $entityManager->persist($user);
            $entityManager->flush();
            $response = $this->generateUrl('reservationEnvoyee')."?commandeId=".$commande->getId();
            $mailService->sendMHAMail($commande);
        }

        return new Response($response);
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
        return $this->render('mha/pages/reservationWebApp.html.twig');
    }



    /**
     * @Route("/xhr/doesThisClientStored" , name="clientStored", methods = "GET")
     */
    public function clientStored(Request $request, ClientService $clientService)
    {
        $mail = $request->get('mail');
        $user = $clientService->findIfExists($mail);
        if ($user) {
            return new Response('
                                <div class="h5 mb-4 text-center"
                                 style="font-size: 1.6em;font-weight: 500;line-height: 1.5em;letter-spacing: -0.79px;">
                               Bienvenue, ' .  $user->getName() .  '
                            </div>
                            <p>
Il semble que vous avez déjà effectué un trajet avec MHA vtc, nous vous remercions pour votre fidélité et avons hâte de vous revoir.</p>');
        } else {
            return new Response('
                                <div class="h5 mb-4 text-center"
                                 style="font-size: 1.6em;font-weight: 500;line-height: 1.5em;letter-spacing: -0.79px;">
                                Bienvenue 
                            </div>
                            <p>Il semble que c\'est votre première course avec MHA vtc, nous avons hâte de vous rencontrer. </p>');
        }


    }

    /**
     * @Route("/xhr/getPrix" , name="getPrix", methods = "GET")
     */
    public function getPrixXHR(Request $request)
    {
        $infos = json_decode($request->get('infos'),true);
        return new Response(json_encode(['prix' =>$this->getPrix($this->getDistance($infos['from'],$infos['to']),$infos['h'])]));
    }



    function getDistance($from,$to){
        // Google API key
        $apiKey = 'AIzaSyBT7jEnTsN1qsD6et-UlFFtugaGXomXdDc';
        // Change address format
        $formattedAddrFrom    = str_replace(' ', '+',$from);
        $formattedAddrTo     = str_replace(' ', '+', $to);
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$formattedAddrFrom&destinations=$formattedAddrTo&mode=driving&sensor=false&key=$apiKey";

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
      return ( json_decode($response, true)['rows'][0]['elements'][0]['distance']['value']);

    }

    function getPrix($distance,$horaire){
        return (round((( $distance / 1000) * 1.97 ),2,PHP_ROUND_HALF_DOWN) + ((($horaire > 18 || $horaire < 6) ? 10.5 : 5.5 ) / 2));
    }




}