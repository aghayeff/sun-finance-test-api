<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Notification;
use App\Repository\ClientRepository;
use App\Repository\NotificationRepository;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NotificationRepositoryTest extends KernelTestCase
{
    protected ?NotificationRepository $repository;
    protected ?ClientRepository $clientRepository;
    private Generator $faker;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->repository = $container->get(NotificationRepository::class);
        $this->clientRepository = $container->get(ClientRepository::class);
        $this->faker = Factory::create();
    }

    public function testStore(): void
    {
        $client = $this->clientRepository->findOneBy([]);
        $notification = Notification::fillFakeData($this->faker, $client);
        $this->repository->save($notification);

        $find = $this->repository->find($notification->getId());

        self::assertEquals($notification, $find);
    }
}
