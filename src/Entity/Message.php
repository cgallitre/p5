<?php

namespace App\Entity;

use App\Entity\UploadFile;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Message
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
     *      min = 4,
     *      minMessage = "L'objet doit au moins faire 4 caractères",
     *      allowEmptyString = false
     *      )
     * @Assert\NotBlank(message = "L'objet est obligatoire")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
    * @Assert\Length(
     *      min = 10,
     *      minMessage = "Le message doit au moins faire 10 caractères",
     *      allowEmptyString = false
     *      )
     * @Assert\NotBlank(message = "Le message est obligatoire")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="messages")
     * @Assert\NotBlank(message = "Le type est obligatoire")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="messages")
      * @Assert\NotBlank(message = "Le projet est obligatoire")
     */
    private $project;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UploadFile", mappedBy="message", cascade={"persist" , "remove"}, orphanRemoval=true)
     */
    private $uploadFiles;

    public function __construct()
    {
        $this->uploadFiles = new ArrayCollection();
    }

     /**
     * Crée automatiquement la date du message
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initialize()
    {
        if (empty($this->createdAt))
        {
            $this->createdAt = new \DateTime();
        }
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Collection|UploadFile[]
     */
    public function getUploadFiles(): Collection
    {
        return $this->uploadFiles;
    }

    public function addUploadFile(UploadFile $uploadFile): self
    {
        if (!$this->uploadFiles->contains($uploadFile)) {
            $this->uploadFiles[] = $uploadFile;
            $uploadFile->setMessage($this);
        }

        return $this;
    }

    public function removeUploadFile(UploadFile $uploadFile): self
    {
        if ($this->uploadFiles->contains($uploadFile)) {
            $this->uploadFiles->removeElement($uploadFile);
            // set the owning side to null (unless already changed)
            if ($uploadFile->getMessage() === $this) {
                $uploadFile->setMessage(null);
            }
        }

        return $this;
    }
}
