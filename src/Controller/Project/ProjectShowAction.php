<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\Project;
use App\Entity\Task;
use App\Enum\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projects/{id}', name: 'project_show')]
class ProjectShowAction extends AbstractController
{
    /**
     * Affiche le tableau de bord de tâches d'un projet.
     *
     * Si le projet est archivé, redirige vers la liste des projets.
     *
     * @param Project $project
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function __invoke(Project $project, EntityManagerInterface $em): Response
    {
        if ($project->isArchived()) {
            return $this->redirectToRoute('project_list');
        }
        // Regroupement des tâches par statut (To Do / Doing / Done)
        $tasks = $em->getRepository(Task::class)->findBy(['project' => $project]);
        $todo = [];
        $doing = [];
        $done = [];

        foreach ($tasks as $task) {
            $status = $task->getStatus();

            match ($status) {
                TaskStatus::DOING => $doing[] = $task,
                TaskStatus::DONE => $done[] = $task,
                default => $todo[] = $task,
            };
        }

        return $this->render('task/board.html.twig', [
            'project' => $project,
            'tasks_todo' => $todo,
            'tasks_doing' => $doing,
            'tasks_done' => $done,
            'statuses' => TaskStatus::ordered(),
        ]);
    }
}
