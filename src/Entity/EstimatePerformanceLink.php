<?php

namespace App\Entity;

use App\Repository\EstimatePerformanceLinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EstimatePerformanceLinkRepository::class)]
class EstimatePerformanceLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $estimate_tab_id = null;

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $performance_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstimateTabId(): ?Uuid
    {
        return $this->estimate_tab_id;
    }

    public function setEstimateTabId(Uuid $estimate_tab_id): static
    {
        $this->estimate_tab_id = $estimate_tab_id;

        return $this;
    }

    public function getPerformanceId(): ?Uuid
    {
        return $this->performance_id;
    }

    public function setPerformanceId(Uuid $performance_id): static
    {
        $this->performance_id = $performance_id;

        return $this;
    }
}
