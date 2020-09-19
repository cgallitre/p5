<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeRepository")
 */
class Type
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Le titre doit au moins faire 2 caractères",
     *      allowEmptyString = false
     *      )
     * @Assert\NotBlank(message = "Le titre est obligatoire")
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="type")
     */
    private $messages;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $screenOrder;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setType($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getType() === $this) {
                $message->setType(null);
            }
        }

        return $this;
    }

    public function getScreenOrder(): ?int
    {
        return $this->screenOrder;
    }

    public function setScreenOrder(?int $screenOrder): self
    {
        $this->screenOrder = $screenOrder;

        return $this;
    }
}
