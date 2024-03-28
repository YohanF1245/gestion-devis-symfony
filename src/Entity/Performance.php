<?php

namespace App\Entity;

use App\Repository\PerformanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PerformanceRepository::class)]
class Performance
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[ORM\Column(nullable: true)]
    private ?float $tax = null;

    #[ORM\Column]
    private ?int $pirce = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $unit = null;

    #[ORM\ManyToMany(targetEntity: EstimateTab::class, mappedBy: 'performace_id')]
    private Collection $estimateTabs;

    public function __construct()
    {
        $this->estimateTabs = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(?float $tax): static
    {
        $this->tax = $tax;

        return $this;
    }

    public function getPirce(): ?int
    {
        return $this->pirce;
    }

    public function setPirce(int $pirce): static
    {
        $this->pirce = $pirce;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<int, EstimateTab>
     */
    public function getEstimateTabs(): Collection
    {
        return $this->estimateTabs;
    }

    public function addEstimateTab(EstimateTab $estimateTab): static
    {
        if (!$this->estimateTabs->contains($estimateTab)) {
            $this->estimateTabs->add($estimateTab);
            $estimateTab->addPerformaceId($this);
        }

        return $this;
    }

    public function removeEstimateTab(EstimateTab $estimateTab): static
    {
        if ($this->estimateTabs->removeElement($estimateTab)) {
            $estimateTab->removePerformaceId($this);
        }

        return $this;
    }
}
