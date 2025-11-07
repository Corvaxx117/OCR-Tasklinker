<?php

declare(strict_types=1);

namespace App\Controller\Task;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tasks/{id}/delete', name: 'task_delete', methods: ['GET'])]
class TaskDeleteAction extends AbstractController
{
    /**
     * Supprime a tâche.
     *
     * @param Task $task
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     *
     * Supprime une tâche, redirige vers le tableau de bord.
     */
    public function __invoke(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        $project = $task->getProject();
        $em->remove($task);
        $em->flush();
        $this->addFlash('success', 'Tâche supprimée');

        return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
    }
}
