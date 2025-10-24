<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\Project;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projects/{id}', name: 'project_show')]
class ProjectShowAction extends AbstractController
{
    public function __invoke(Project $project, EntityManagerInterface $em): Response
    {
        if ($project->isArchived()) {
            return $this->redirectToRoute('project_list');
        }
        // Regroupement des tâches par statut (To Do / Doing / Done)
        // Hypothèse: projectStatus.label contient ces libellés
        $tasks = $em->getRepository(Task::class)->findBy(['project' => $project]);
        $todo = [];
        $doing = [];
        $done = [];
        foreach ($tasks as $task) {
            $label = $task->getProjectStatus()?->getLabel();
            switch ($label) {
                case 'Doing':
                    $doing[] = $task;
                    break;
                case 'Done':
                    $done[] = $task;
                    break;
                default:
                    $todo[] = $task;
                    break; // fallback To Do
            }
        }
        return $this->render('task/board.html.twig', [
            'project' => $project,
            'tasks_todo' => $todo,
            'tasks_doing' => $doing,
            'tasks_done' => $done,
        ]);
    }
}
