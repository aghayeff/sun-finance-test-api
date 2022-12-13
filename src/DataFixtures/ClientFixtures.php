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
            $client = new Client();
            $client->setFirstName($this->faker->firstName);
            $client->setLastName($this->faker->lastName);
            $client->setEmail($this->faker->email);
            $client->setPhoneNumber('+99450' . $this->faker->numberBetween(2000000, 9999999));
            $manager->persist($client);

            $notification = new Notification();
            $notification->setChannel($this->faker->randomElement(NotificationChannel::values()));
            $notification->setContent($this->faker->realText());
            $notification->setClient($client);
            $manager->persist($notification);
        }

        $manager->flush();
    }
}
