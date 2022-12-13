<?php

namespace App\Message;

use App\Entity\Notification;

final class AppNotification
{
     private Notification $notification;

     public function __construct(Notification $notification)
     {
         $this->notification = $notification;
     }

    public function getNotification(): Notification
    {
        return $this->notification;
    }
}
