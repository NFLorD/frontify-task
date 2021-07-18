<?php

namespace App\Entity;

use App\Repository\ColorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ColorRepository::class)
 * @UniqueEntity("hex", message="A color with hex {{ value }} already exists.")
 */
class Color
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(normalizer="trim")
     * @Assert\Regex("/^[0-9a-zA-Z -]{1,32}$/")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=6, unique=true)
     * @Assert\Length(min=6, max=6)
     * @Assert\Regex("/^[0-9a-fA-F]{6}$/")
     */
    private $hex;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHex(): ?string
    {
        return $this->hex;
    }

    public function setHex(string $hex): self
    {
        $this->hex = $hex;

        return $this;
    }
}
