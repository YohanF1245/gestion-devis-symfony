<?php

namespace App\Entity;

use App\Repository\DressEstimateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DressEstimateRepository::class)]
class DressEstimate
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\Column]
    private ?int $estimate_number = null;

    #[ORM\Column(nullable: true)]
    private ?int $validity = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expiration_date = null;

    #[ORM\Column(length: 128)]
    private ?string $intitule = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $free_zone = null;

    #[ORM\Column(nullable: true)]
    private ?int $accompte = null;

    #[ORM\Column(nullable: true)]
    private ?int $discount = null;

    #[ORM\ManyToOne(inversedBy: 'dressEstimates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client_id = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user_id = null;

    #[ORM\OneToOne(mappedBy: 'estimate_id', cascade: ['persist', 'remove'])]
    private ?EstimateTab $estimateTab = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): static
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getEstimateNumber(): ?int
    {
        return $this->estimate_number;
    }

    public function setEstimateNumber(int $estimate_number): static
    {
        $this->estimate_number = $estimate_number;

        return $this;
    }

    public function getValidity(): ?int
    {
        return $this->validity;
    }

    public function setValidity(?int $validity): static
    {
        $this->validity = $validity;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(\DateTimeInterface $expiration_date): static
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getFreeZone(): ?string
    {
        return $this->free_zone;
    }

    public function setFreeZone(?string $free_zone): static
    {
        $this->free_zone = $free_zone;

        return $this;
    }

    public function getAccompte(): ?int
    {
        return $this->accompte;
    }

    public function setAccompte(?int $accompte): static
    {
        $this->accompte = $accompte;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getClientId(): ?Client
    {
        return $this->client_id;
    }

    public function setClientId(?Client $client_id): static
    {
        $this->client_id = $client_id;

        return $this;
    }

    public function getUserId(): ?Users
    {
        return $this->user_id;
    }

    public function setUserId(?Users $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
    public function getEstimateTab(): ?EstimateTab
    {
        return $this->estimateTab;
    }

    public function setEstimateTab(EstimateTab $estimateTab): static
    {
        // set the owning side of the relation if necessary
        if ($estimateTab->getEstimateId() !== $this) {
            $estimateTab->setEstimateId($this);
        }

        $this->estimateTab = $estimateTab;

        return $this;
    }
}
