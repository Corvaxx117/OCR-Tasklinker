<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use App\Enum\AccessStatus;
use App\Enum\ContractType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[UniqueEntity('mail')]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $mail = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $startedAt = null;

    #[ORM\Column(enumType: AccessStatus::class)]
    #[Assert\NotNull]
    private ?AccessStatus $accessStatus = null;

    #[ORM\Column(enumType: ContractType::class)]
    #[Assert\NotNull]
    private ?ContractType $contractType = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $passwordHash = null;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'members')]
    private Collection $projects;

    #[ORM\ManyToMany(targetEntity: Task::class, mappedBy: 'assignees')]
    private Collection $tasks;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;
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

    public function getAccessStatus(): ?AccessStatus
    {
        return $this->accessStatus;
    }

    public function setAccessStatus(AccessStatus $accessStatus): self
    {
        $this->accessStatus = $accessStatus;
        return $this;
    }

    public function getContractType(): ?ContractType
    {
        return $this->contractType;
    }

    public function setContractType(ContractType $contractType): self
    {
        $this->contractType = $contractType;
        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): self
    {
        $this->passwordHash = $passwordHash;
        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addMember($this);
        }
        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            $project->removeMember($this);
        }
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
            $task->addAssignee($this);
        }
        return $this;
    }
    /**
     * Renvoie les initiales du nom et du prénom de l'employé.
     *
     * @return string Les initiales de l'employé.
     */
    public function getInitials(): string
    {
        return strtoupper(substr($this->firstName ?? '', 0, 1) . substr($this->lastName ?? '', 0, 1));
    }
}
