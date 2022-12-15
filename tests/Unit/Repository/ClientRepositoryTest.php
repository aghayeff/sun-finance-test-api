<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Service\ClientService;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ClientRepositoryTest extends KernelTestCase
{
    protected ?ClientRepository $repository;
    private Generator $faker;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->repository = $container->get(ClientRepository::class);
        $this->faker = Factory::create();
    }

    public function testStore(): void
    {
        $client = Client::fillFakeData($this->faker);
        $this->repository->save($client);

        $find = $this->repository->find($client->getId());

        self::assertEquals($client, $find);
    }

    public function testDelete(): void
    {
        $count = 0;

        $find = $this->repository->findOneBy([]);

        if ($find) {
            $count = 1;
            $this->repository->remove($find);
        }

        self::assertEquals(1, $count);
    }
}
