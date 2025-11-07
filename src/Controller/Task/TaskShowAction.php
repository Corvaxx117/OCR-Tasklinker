<?php

declare(strict_types=1);

namespace App\Controller\Task;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tasks/{id}', name: 'task_show')]
class TaskShowAction extends AbstractController
{
    /**
     * Affiche une tâche.
     *
     * @param Task $task
     * @return Response
     */
    public function __invoke(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }
}
