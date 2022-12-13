<?php

namespace App\MessageHandler;

use App\Entity\Notification;
use App\Message\AppNotification;
use App\Repository\NotificationRepository;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

final class AppNotificationHandler
{
    protected string $channel;
    protected Notification $notification;
    protected NotificationRepository $notificationRepository;

    public function __invoke(AppNotification $message, NotificationRepository $notificationRepository)
    {
        $this->notification = $message->getNotification();
        $this->channel = $message->getNotification()->getChannel();

        $this->handle();
    }

    private function handle(): void
    {
        try {
            $this->channel === 'sms' ?
                $this->sendSms() :
                $this->sendEmail();
        }catch (TransportExceptionInterface $e){

        }

        $this->notification->setStatus(true);
        $this->notificationRepository->save($this->notification, true);
    }

    private function sendSms()
    {

    }

    private function sendEmail()
    {

    }
}
