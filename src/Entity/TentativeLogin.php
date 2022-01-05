<?php

namespace App\Entity;

use App\Repository\TentativeLoginRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TentativeLoginRepository::class)
 */
class TentativeLogin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $addressip;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    public function __construct(?string $addressip, ?string $email)
    {
        $this->addressip = $addressip;
        $this->email = $email;
        $this->date = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddressip(): ?string
    {
        return $this->addressip;
    }

    public function setAddressip(?string $addressip): self
    {
        $this->addressip = $addressip;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
