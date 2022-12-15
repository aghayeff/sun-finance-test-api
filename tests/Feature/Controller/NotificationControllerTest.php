<?php

namespace App\Tests\Feature\Controller;

use App\Config\NotificationChannel;
use App\Repository\ClientRepository;
use App\Repository\NotificationRepository;
use App\Tests\Feature\AuthenticatedClient;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class NotificationControllerTest extends WebTestCase
{
    use AuthenticatedClient;

    private object $notificationRepository;
    private object $clientRepository;
    private KernelBrowser $client;
    private Generator $faker;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();

        $this->notificationRepository = $container->get(NotificationRepository::class);
        $this->clientRepository = $container->get(ClientRepository::class);
        $this->faker = Factory::create();
    }

    public function testShowNotification(): void
    {
        $client = $this->createAuthenticatedClient($this->client);

        $data = $this->notificationRepository->findOneBy([]);

        $client->request('GET', '/api/notifications/' . $data->getId());

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testGetAllNotifications(): void
    {
        $client = $this->createAuthenticatedClient($this->client);
        $client->request('GET', '/api/notifications');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testStoreNotification(): void
    {
        $client = $this->createAuthenticatedClient($this->client);

        $user = $this->clientRepository->findOneBy([]);

        $client->request('POST', '/api/notifications', [
            'clientId' => $user->getId(),
            'channel' => $this->faker->randomElement(NotificationChannel::values()),
            'content' => $this->faker->realText(120),
        ]);

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }
}
