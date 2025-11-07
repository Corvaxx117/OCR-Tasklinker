<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $startedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deadline = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $isArchived = false;

    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'projects')]
    #[ORM\JoinTable(name: 'project_employee')]
    private Collection $members;


    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Task::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $tasks;


    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    #[Assert\Callback]
    public function validateChronology(ExecutionContextInterface $context): void
    {
        if ($this->deadline === null || $this->startedAt === null) {
            return; // deadline optionnelle ou startedAt manquant (déjà NotBlank)
        }
        if ($this->deadline < $this->startedAt) {
            $context->buildViolation('La deadline doit être supérieure ou égale à la date de démarrage.')
                ->atPath('deadline')
                ->addViolation();
        }
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

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

    public function isArchived(): bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): self
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Employee $employee): self
    {
        if (!$this->members->contains($employee)) {
            $this->members->add($employee);
        }

        return $this;
    }

    public function removeMember(Employee $employee): self
    {
        $this->members->removeElement($employee);

        return $this;
    }


    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }
}
