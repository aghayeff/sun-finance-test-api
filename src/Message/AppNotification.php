<?php

namespace App\Message;

use App\Entity\Notification;

final class AppNotification
{
     public function __construct(
         private readonly int $notificationId
     ){
     }

    public function getNotificationId(): int
    {
        return $this->notificationId;
    }
}
