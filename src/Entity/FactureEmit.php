<?php

namespace App\Entity;

use App\Repository\FactureEmitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FactureEmitRepository::class)]
class FactureEmit
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $payment_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $majoration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_limit = null;

    #[ORM\Column]
    private ?bool $is_paid = null;

    #[ORM\OneToOne(mappedBy: 'facture_id', cascade: ['persist', 'remove'])]
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

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setPaymentDate(?\DateTimeInterface $payment_date): static
    {
        $this->payment_date = $payment_date;

        return $this;
    }

    public function getMajoration(): ?int
    {
        return $this->majoration;
    }

    public function setMajoration(?int $majoration): static
    {
        $this->majoration = $majoration;

        return $this;
    }

    public function getDateLimit(): ?\DateTimeInterface
    {
        return $this->date_limit;
    }

    public function setDateLimit(?\DateTimeInterface $date_limit): static
    {
        $this->date_limit = $date_limit;

        return $this;
    }

    public function isIsPaid(): ?bool
    {
        return $this->is_paid;
    }

    public function setIsPaid(bool $is_paid): static
    {
        $this->is_paid = $is_paid;

        return $this;
    }

    public function getEstimateTab(): ?EstimateTab
    {
        return $this->estimateTab;
    }

    public function setEstimateTab(EstimateTab $estimateTab): static
    {
        // set the owning side of the relation if necessary
        if ($estimateTab->getFactureId() !== $this) {
            $estimateTab->setFactureId($this);
        }

        $this->estimateTab = $estimateTab;

        return $this;
    }
}
