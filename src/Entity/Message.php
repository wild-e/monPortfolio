<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     * @Assert\Length(
     *      min = "2"
     *      message = "Votre prénom doit avoir un minimum de 2 lettres..."
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     * @Assert\Length(
     *      min = "2"
     *      message = "Votre nom doit avoir un minimum de 2 lettres..."
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     * @Assert\Email(
     *     message = "Cet email :'{{ value }}' ne ressemble pas à un email."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @var string
     * @Assert\Length(
     *      min = "10"
     *      message = "Merci d'écrire quelque chose qui pourra m'intéresser !"
     * )
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
