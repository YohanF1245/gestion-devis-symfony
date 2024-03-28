<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $business_name = null;

    #[ORM\Column]
    private ?bool $is_physick = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail = null;

    #[ORM\Column(length: 64)]
    private ?string $num_street = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column(length: 128)]
    private ?string $zip_postal = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $index_tel = null;

    #[ORM\Column(length: 14, nullable: true)]
    private ?string $phone_number = null;

    #[ORM\Column(length: 255)]
    private ?string $town = null;

    #[ORM\OneToMany(targetEntity: DressEstimate::class, mappedBy: 'client_id')]
    private Collection $dressEstimates;

    public function __construct()
    {
        $this->dressEstimates = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getBusinessName(): ?string
    {
        return $this->business_name;
    }

    public function setBusinessName(?string $business_name): static
    {
        $this->business_name = $business_name;

        return $this;
    }

    public function isIsPhysick(): ?bool
    {
        return $this->is_physick;
    }

    public function setIsPhysick(bool $is_physick): static
    {
        $this->is_physick = $is_physick;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getNumStreet(): ?string
    {
        return $this->num_street;
    }

    public function setNumStreet(string $num_street): static
    {
        $this->num_street = $num_street;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getZipPostal(): ?string
    {
        return $this->zip_postal;
    }

    public function setZipPostal(string $zip_postal): static
    {
        $this->zip_postal = $zip_postal;

        return $this;
    }

    public function getIndexTel(): ?string
    {
        return $this->index_tel;
    }

    public function setIndexTel(?string $index_tel): static
    {
        $this->index_tel = $index_tel;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): static
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): static
    {
        $this->town = $town;

        return $this;
    }

    /**
     * @return Collection<int, DressEstimate>
     */
    public function getDressEstimates(): Collection
    {
        return $this->dressEstimates;
    }

    public function addDressEstimate(DressEstimate $dressEstimate): static
    {
        if (!$this->dressEstimates->contains($dressEstimate)) {
            $this->dressEstimates->add($dressEstimate);
            $dressEstimate->setClientId($this);
        }

        return $this;
    }

    public function removeDressEstimate(DressEstimate $dressEstimate): static
    {
        if ($this->dressEstimates->removeElement($dressEstimate)) {
            // set the owning side to null (unless already changed)
            if ($dressEstimate->getClientId() === $this) {
                $dressEstimate->setClientId(null);
            }
        }

        return $this;
    }
}
