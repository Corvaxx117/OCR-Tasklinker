<?php

declare(strict_types=1);

namespace App\Controller\Task;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tasks/{id}/edit', name: 'task_edit')]
class TaskEditAction extends AbstractController
{
    public function __invoke(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('task_show', ['id' => $task->getId()]);
        }

        return $this->render('task/form.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
            'is_edit' => true,
        ]);
    }
}
