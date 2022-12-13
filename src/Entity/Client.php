<?php

namespace App\Entity;

use App\Mapping\BaseEntity;
use App\Repository\ClientRepository;
use App\Validator\PhoneNumberFormat;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 32)]
    private string $firstName;

    #[ORM\Column(length: 32)]
    private string $lastName;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column(length: 15)]
    private string $phoneNumber;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications;

    public function __construct()
    {
        parent::__construct();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('firstName', new Assert\NotBlank());
        $metadata->addPropertyConstraint(
            'firstName',
            new Assert\Length(['min' => 2, 'max' => 32])
        );

        $metadata->addPropertyConstraint('lastName', new Assert\NotBlank());
        $metadata->addPropertyConstraint(
            'lastName',
            new Assert\Length(['min' => 2, 'max' => 32])
        );

        $metadata->addPropertyConstraint('email', new Assert\NotBlank());
        $metadata->addPropertyConstraint(
            'email',
            new Assert\Email()
        );

        $metadata->addPropertyConstraint('phoneNumber', new Assert\NotBlank());
        $metadata->addPropertyConstraint('phoneNumber', new PhoneNumberFormat('string'));
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setClient($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getClient() === $this) {
                $notification->setClient(null);
            }
        }

        return $this;
    }
}
