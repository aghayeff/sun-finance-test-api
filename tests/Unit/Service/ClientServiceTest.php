<?php

namespace App\Tests\Unit\Service;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Service\ClientService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ClientServiceTest extends KernelTestCase
{
    protected ?ClientRepository $clientRepository;
    protected ?Client $clientEntity;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->clientRepository = $container->get(ClientRepository::class);
        $this->clientEntity = $container->get(Client::class);
    }

    public function testInitialization(): void
    {
        $clientService = new ClientService($this->clientRepository, $this->clientEntity);
        self::assertInstanceOf(ClientService::class, $clientService);
    }
}
