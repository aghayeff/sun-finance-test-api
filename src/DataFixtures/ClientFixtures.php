<?php

namespace App\DataFixtures;

use App\Config\NotificationChannel;
use App\Entity\Client;
use App\Entity\Notification;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends BaseFixtures
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $client = Client::fillFakeData($this->faker);
            $manager->persist($client);

            $notification = Notification::fillFakeData($this->faker, $client);
            $manager->persist($notification);
        }

        $manager->flush();
    }
}
