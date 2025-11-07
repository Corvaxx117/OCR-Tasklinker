<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TaskRepository;
use App\Enum\TaskStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\ManyToMany(targetEntity: Employee::class)]
    #[ORM\JoinTable(name: 'task_employee')]
    private Collection $assignees;

    #[ORM\Column(type: 'string', enumType: TaskStatus::class, options: ['default' => TaskStatus::TODO->value])]
    private TaskStatus $status = TaskStatus::TODO;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\GreaterThanOrEqual(value: 'today', message: 'La deadline ne peut pas être dans le passé.')]
    private ?\DateTimeInterface $deadline = null;


    public function __construct()
    {
        $this->assignees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection<int, Employee>
     */
    public function getAssignees(): Collection
    {
        return $this->assignees;
    }

    public function addAssignee(Employee $employee): self
    {
        if (!$this->assignees->contains($employee)) {
            $this->assignees->add($employee);
        }
        return $this;
    }

    public function removeAssignee(Employee $employee): self
    {
        $this->assignees->removeElement($employee);
        return $this;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): self
    {
        $this->status = $status;
        return $this;
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }
}
