<?php

namespace App\Entity;

use App\Repository\EstimateTabRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EstimateTabRepository::class)]
class EstimateTab
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

<<<<<<< HEAD
    #[ORM\ManyToOne(targetEntity:Business::class, inversedBy: 'estimateTabs')]
=======
    #[ORM\ManyToOne(inversedBy: 'estimateTabs')]
>>>>>>> parent of acdad8f (estimate number auto generation and ui fixes)
    #[ORM\JoinColumn(nullable: false)]
    private ?Business $business_id = null;

    #[ORM\OneToOne(inversedBy: 'estimateTab', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?DressEstimate $estimate_id = null;

    #[ORM\ManyToMany(targetEntity: Performance::class, inversedBy: 'estimateTabs')]
    private Collection $performace_id;

    #[ORM\OneToOne(inversedBy: 'estimateTab', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?FactureEmit $facture_id = null;

    public function __construct()
    {
        $this->performace_id = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getEstimateId(): ?DressEstimate
    {
        return $this->estimate_id;
    }

    public function setEstimateId(DressEstimate $estimate_id): static
    {
        $this->estimate_id = $estimate_id;

        return $this;
    }

    /**
     * @return Collection<int, Performance>
     */
    public function getPerformaceId(): Collection
    {
        return $this->performace_id;
    }

    public function addPerformaceId(Performance $performaceId): static
    {
        if (!$this->performace_id->contains($performaceId)) {
            $this->performace_id->add($performaceId);
        }

        return $this;
    }

    public function removePerformaceId(Performance $performaceId): static
    {
        $this->performace_id->removeElement($performaceId);

        return $this;
    }

    public function getFactureId(): ?FactureEmit
    {
        return $this->facture_id;
    }

    public function setFactureId(FactureEmit $facture_id): static
    {
        $this->facture_id = $facture_id;

        return $this;
    }
}
