<?php

namespace App\Tests\Unit\Service;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NotificationServiceTest extends KernelTestCase
{
    protected ?NotificationRepository $notificationRepository;
    protected ?Notification $notificationEntity;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->notificationRepository = $container->get(NotificationRepository::class);
        $this->notificationEntity = $container->get(Notification::class);
    }

    public function testInitialization(): void
    {
        $notificationService = new NotificationService($this->notificationRepository, $this->notificationEntity);
        self::assertInstanceOf(NotificationService::class, $notificationService);
    }
}
