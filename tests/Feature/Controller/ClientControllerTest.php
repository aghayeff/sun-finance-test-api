<?php

namespace App\Tests\Feature\Controller;

use App\Repository\ClientRepository;
use App\Tests\Feature\AuthenticatedClient;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ClientControllerTest extends WebTestCase
{
    use AuthenticatedClient;

    private object $clientRepository;
    private KernelBrowser $client;
    private Generator $faker;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();

        $this->clientRepository = $container->get(ClientRepository::class);
        $this->faker = Factory::create();
    }

    public function testShowClient(): void
    {
        $data = $this->clientRepository->findOneBy([]);

        $this->client->request('GET', '/api/clients/' . $data->getId());

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testGetAllClients(): void
    {
        $client = $this->createAuthenticatedClient($this->client);
        $client->request('GET', '/api/private/clients');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testStoreClient(): void
    {
        $this->client->request('POST', '/api/clients', [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phoneNumber' => '+99450' . $this->faker->numberBetween(2000000, 9999999),
        ]);

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateClient(): void
    {
        $data = $this->clientRepository->findOneBy([]);

        $this->client->request('PUT', '/api/clients/' . $data->getId(), [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phoneNumber' => '+99450' . $this->faker->numberBetween(2000000, 9999999),
        ]);

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteClient(): void
    {
        $data = $this->clientRepository->findOneBy([]);

        $this->client->request('DELETE', '/api/clients/' . $data->getId());

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
