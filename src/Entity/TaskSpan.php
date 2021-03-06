<?php

namespace App\Entity;

use App\Repository\TaskSpanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskSpanRepository::class)
 */
class TaskSpan
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Task::class, inversedBy="taskSpans", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $task;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $stopped_at;

    public function __construct()
    {
        $this->created_at = new \DateTime;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function getFormatedCreatedAt()
    {
        return $this->getCreatedAt()->format('F d, Y H:i:s');
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getStoppedAt(): ?\DateTimeInterface
    {
        return $this->stopped_at;
    }

    public function stop()
    {
        return $this->setStoppedAt(new \DateTime);
    }

    public function setStoppedAt(?\DateTimeInterface $stopped_at): self
    {
        $this->stopped_at = $stopped_at;

        return $this;
    }

    public function getTaskSpanInterval()
    {
        $interval = $this->getCreatedAt()->diff($this->getStoppedAt());
        
        return $interval;
    }
}
