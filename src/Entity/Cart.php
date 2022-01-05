<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="string",length=5, nullable=true)
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="carts")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $month;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $cardnumber;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $typecarte;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): self
    {
        $this->year= $year;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(?string $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getCardnumber(): ?string
    {
        return $this->cardnumber;
    }

    public function setCardnumber(?string $cardnumber): self
    {
        $this->cardnumber = $cardnumber;

        return $this;
    }

    public function getTypecarte(): ?string
    {
        return $this->typecarte;
    }

    public function setTypecarte(?string $typecarte): self
    {
        $this->typecarte = $typecarte;

        return $this;
    }
}
