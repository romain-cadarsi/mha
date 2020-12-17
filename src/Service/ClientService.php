<?php

namespace App\Service;

use App\Repository\ClientRepository;

class ClientService {
    private $clientRepo;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepo = $clientRepo;
    }

    public function findIfExists($mail){
        $user = $this->clientRepo->findBy(['mail' => $mail]);
        if($user){
            return true;
        }
        return false;
    }
}