<?php

namespace App\Entity;

use App\Repository\ReceiverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReceiverRepository::class)
 */
class Receiver
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $country_code;

    /**
     * @ORM\OneToMany(targetEntity=Gift::class, mappedBy="receiver")
     */
    private $gift;

    public function __construct()
    {
        $this->gift = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function setCountryCode(string $country_code): self
    {
        $this->country_code = $country_code;

        return $this;
    }

    /**
     * @return Collection|Gift[]
     */
    public function getGift(): Collection
    {
        return $this->gift;
    }

    public function addGift(Gift $gift): self
    {
        if (!$this->gift->contains($gift)) {
            $this->gift[] = $gift;
            $gift->setReceiver($this);
        }

        return $this;
    }

    public function removeGift(Gift $gift): self
    {
        if ($this->gift->removeElement($gift)) {
            // set the owning side to null (unless already changed)
            if ($gift->getReceiver() === $this) {
                $gift->setReceiver(null);
            }
        }

        return $this;
    }
}
