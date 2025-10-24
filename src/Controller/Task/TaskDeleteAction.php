<?php

declare(strict_types=1);

namespace App\Controller\Task;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tasks/{id}/delete', name: 'task_delete', methods: ['POST'])]
class TaskDeleteAction extends AbstractController
{
    public function __invoke(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        $project = $task->getProject();
        $em->remove($task);
        $em->flush();
        $this->addFlash('success', 'Tâche supprimée');
        return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
    }
}
