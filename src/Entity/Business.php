<?php

namespace App\Entity;

use App\Repository\BusinessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: BusinessRepository::class)]
#[UniqueEntity('siret')]
class Business
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 14, unique: true)]
    private ?string $siret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\ManyToOne(inversedBy: 'businesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user_id = null;

    #[ORM\OneToMany(targetEntity: Outcome::class, mappedBy: 'business_id')]
    private Collection $outcomes;

    #[ORM\OneToMany(targetEntity: EstimateTab::class, mappedBy: 'business_id')]
    private Collection $estimateTabs;

    #[ORM\Column(length: 255)]
    private ?string $business_name = null;

    #[ORM\Column(length: 255)]
    private ?string $code_ape = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code_tva = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rib = null;

    public function __construct()
    {
        $this->outcomes = new ArrayCollection();
        $this->estimateTabs = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getUserId(): ?users
    {
        return $this->user_id;
    }

    public function setUserId(?users $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, Outcome>
     */
    public function getOutcomes(): Collection
    {
        return $this->outcomes;
    }

    public function addOutcome(Outcome $outcome): static
    {
        if (!$this->outcomes->contains($outcome)) {
            $this->outcomes->add($outcome);
            $outcome->setBusinessId($this);
        }

        return $this;
    }

    public function removeOutcome(Outcome $outcome): static
    {
        if ($this->outcomes->removeElement($outcome)) {
            // set the owning side to null (unless already changed)
            if ($outcome->getBusinessId() === $this) {
                $outcome->setBusinessId(null);
            }
        }

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
            $estimateTab->setBusinessId($this);
        }

        return $this;
    }

    public function removeEstimateTab(EstimateTab $estimateTab): static
    {
        if ($this->estimateTabs->removeElement($estimateTab)) {
            // set the owning side to null (unless already changed)
            if ($estimateTab->getBusinessId() === $this) {
                $estimateTab->setBusinessId(null);
            }
        }

        return $this;
    }

    public function getBusinessName(): ?string
    {
        return $this->business_name;
    }

    public function setBusinessName(string $business_name): static
    {
        $this->business_name = $business_name;

        return $this;
    }

    public function getCodeApe(): ?string
    {
        return $this->code_ape;
    }

    public function setCodeApe(string $code_ape): static
    {
        $this->code_ape = $code_ape;

        return $this;
    }

    public function getCodeTva(): ?string
    {
        return $this->code_tva;
    }

    public function setCodeTva(?string $code_tva): static
    {
        $this->code_tva = $code_tva;

        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(?string $rib): static
    {
        $this->rib = $rib;

        return $this;
    }
}
