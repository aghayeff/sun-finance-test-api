<?php

namespace App\Service;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;

class ClientService extends EntityService
{
    public function __construct(
        protected ClientRepository $clientRepository,
        protected Client $client,
    ) {
        parent::__construct($client, $clientRepository);
    }


    public function payload(Request $request): array
    {
        return [
            'firstName' => $request->get('firstName'),
            'lastName' => $request->get('lastName'),
            'email' => $request->get('email'),
            'phoneNumber' => $request->get('phoneNumber'),
        ];
    }
}