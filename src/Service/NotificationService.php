<?php

namespace App\Service;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;

class NotificationService extends EntityService
{
    public function __construct(
        protected NotificationRepository $clientRepository,
        protected Notification $notification,
    ) {
        parent::__construct($notification, $clientRepository);
    }


    public function payload(Request $request): array
    {
        return [
            'clientId' => (int) $request->get('clientId'),
            'channel' => $request->get('channel'),
            'content' => $request->get('content'),
        ];
    }
}