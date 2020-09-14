<?php

namespace App\Entity;

use App\Repository\TasksRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Doctrine\TaskIdGenerator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TasksRepository::class)
 */
class Tasks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="App\Doctrine\TaskIdGenerator")
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Укажите заголовок задачи!")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=2000)
     * @Assert\NotBlank (message="Укажите описание задачи!")
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $estimation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_closed = false;

    /**
     * @ORM\ManyToOne(targetEntity=Sprint::class, inversedBy="tasks")
     */
    private $project;

    public function getId(): ?string
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEstimation(): ?float
    {
        return $this->estimation;
    }

    public function setEstimation(?float $estimation): self
    {
        $this->estimation = $estimation;

        return $this;
    }

    public function getIsClosed(): ?bool
    {
        return $this->is_closed;
    }

    public function setIsClosed(bool $is_closed): self
    {
        $this->is_closed = $is_closed;

        return $this;
    }

    public function getProject(): ?Sprint
    {
        return $this->project;
    }

    public function setProject(?Sprint $project): self
    {
        $this->project = $project;

        return $this;
    }
}
