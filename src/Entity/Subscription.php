<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $idCarte;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $cvc;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCarte(): ?string
    {
        return $this->idCarte;
    }

    public function setIdCarte(?string $idCarte): self
    {
        $this->idCarte = $idCarte;

        return $this;
    }

    public function getCvc(): ?string
    {
        return $this->cvc;
    }

    public function setCvc(?string $cvc): self
    {
        $this->cvc = $cvc;

        return $this;
    }
}
