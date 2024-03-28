<?php

namespace App\Entity;

use App\Repository\ResetPassRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ResetPassRepository::class)]
class ResetPass
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $reset_date = null;

    #[ORM\Column(length: 255)]
    private ?string $reset_link = null;

    #[ORM\OneToOne(inversedBy: 'resetPass', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?users $user_id = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getResetDate(): ?\DateTimeInterface
    {
        return $this->reset_date;
    }

    public function setResetDate(\DateTimeInterface $reset_date): static
    {
        $this->reset_date = $reset_date;

        return $this;
    }

    public function getResetLink(): ?string
    {
        return $this->reset_link;
    }

    public function setResetLink(string $reset_link): static
    {
        $this->reset_link = $reset_link;

        return $this;
    }

    public function getUserId(): ?users
    {
        return $this->user_id;
    }

    public function setUserId(users $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
}
