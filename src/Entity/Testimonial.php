<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TestimonialRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Testimonial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min = 10,
     *      max = 1000,
     *      minMessage = "Le message doit au moins faire 10 caractères",
     *      maxMessage = "Le message ne peut dépasser 1000 caractères",
     *      allowEmptyString = false
     *      )
     * @Assert\NotBlank(message = "Le message est obligatoire")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "La signature doit faire au moins 10 caractères",
     *      maxMessage = "La signature ne peut pas dépasser 255 caractères",
     *      allowEmptyString = false
     *      )
     * @Assert\NotBlank(message = "La signature est obligatoire")
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * Crée automatiquement la date du témoignage et initialise son état à 0 (non publié)
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeCreate()
    {
        if (empty($this->createdAt))
        {
            $this->createdAt = new \DateTime();
        }

        if (empty($this->published))
        {
            $this->published = 0;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }
}
