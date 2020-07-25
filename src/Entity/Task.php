<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=TaskSpan::class, mappedBy="task", orphanRemoval=true)
     */
    private $taskSpans;

    public function __construct()
    {
        $this->taskSpans = new ArrayCollection();
    }

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

    /**
     * @return Collection|TaskSpan[]
     */
    public function getTaskSpans(): Collection
    {
        return $this->taskSpans;
    }

    public function addTaskSpan(TaskSpan $taskSpan): self
    {
        if (!$this->taskSpans->contains($taskSpan)) {
            $this->taskSpans[] = $taskSpan;
            $taskSpan->setTask($this);
        }

        return $this;
    }

    public function removeTaskSpan(TaskSpan $taskSpan): self
    {
        if ($this->taskSpans->contains($taskSpan)) {
            $this->taskSpans->removeElement($taskSpan);
            // set the owning side to null (unless already changed)
            if ($taskSpan->getTask() === $this) {
                $taskSpan->setTask(null);
            }
        }

        return $this;
    }
}
