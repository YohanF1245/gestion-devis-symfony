<?php

namespace App\Entity;

use App\Repository\OutcomeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OutcomeRepository::class)]
class Outcome
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $outcome_date = null;

    #[ORM\Column]
    private ?int $outcome_amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $recipe_link = null;

    #[ORM\ManyToOne(inversedBy: 'outcomes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Business $business_id = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getOutcomeDate(): ?\DateTimeInterface
    {
        return $this->outcome_date;
    }

    public function setOutcomeDate(\DateTimeInterface $outcome_date): static
    {
        $this->outcome_date = $outcome_date;

        return $this;
    }

    public function getOutcomeAmount(): ?int
    {
        return $this->outcome_amount;
    }

    public function setOutcomeAmount(int $outcome_amount): static
    {
        $this->outcome_amount = $outcome_amount;

        return $this;
    }

    public function getRecipeLink(): ?string
    {
        return $this->recipe_link;
    }

    public function setRecipeLink(?string $recipe_link): static
    {
        $this->recipe_link = $recipe_link;

        return $this;
    }

    public function getBusinessId(): ?Business
    {
        return $this->business_id;
    }

    public function setBusinessId(?Business $business_id): static
    {
        $this->business_id = $business_id;

        return $this;
    }
}
