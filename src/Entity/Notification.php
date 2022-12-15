<?php

namespace App\Entity;

use App\Config\NotificationChannel;
use App\Mapping\BaseEntity;
use App\Repository\NotificationRepository;
use App\Validator\ChannelFormat;
use App\Validator\NotificationContentFormat;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    private int $clientId;

    #[ORM\Column(length: 16)]
    private string $channel;

    #[ORM\Column(length: 255)]
    private string $content;

    #[ORM\Column(type: 'boolean')]
    private bool $status = false;

    public function getId(): int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('clientId', new Assert\NotBlank());
        $metadata->addPropertyConstraint('channel', new ChannelFormat('string'));
        $metadata->addPropertyConstraint('content',  new NotificationContentFormat('string'));
    }

    public static function fillFakeData($faker, Client $client): Notification
    {
        $notification = new Notification();
        $notification->setChannel($faker->randomElement(NotificationChannel::values()));
        $notification->setContent($faker->realText());
        $notification->setClient($client);

        return $notification;
    }
}
