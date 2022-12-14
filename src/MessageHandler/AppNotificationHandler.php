<?php

namespace App\MessageHandler;

use App\Entity\Notification;
use App\Message\AppNotification;
use App\Repository\NotificationRepository;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final class AppNotificationHandler
{
    protected Notification $notification;

    public function __construct(
        protected NotificationRepository $notificationRepository,
        protected MailerInterface $mailer,
    ) {

    }

    public function __invoke(AppNotification $message): void
    {
        $this->notification = $this->notificationRepository->find($message->getNotificationId());

        $this->handle();
    }

    private function handle(): void
    {
        try {
            $this->notification->getChannel() === 'sms' ?
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

    private function sendEmail(): void
    {
        $email = (new Email())
            ->from('noreply@sunfinance.test')
            ->to($this->notification->getClient()->getEmail())
            ->subject('New notification from ' . $this->notification->getClient()->getFirstName())
            ->text($this->notification->getContent());

        $this->mailer->send($email);
    }
}
