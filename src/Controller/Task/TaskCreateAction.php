<?php

declare(strict_types=1);

namespace App\Controller\Task;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Project;
use App\Entity\Employee;
use App\Enum\TaskStatus;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('project/{id}/tasks/new', name: 'task_create')]
class TaskCreateAction extends AbstractController
{
    /**
     * Crée un projet.
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     *
     * @throws ORMException
     */
    public function __invoke(Request $request, EntityManagerInterface $em, #[MapEntity(mapping: ['id' => 'id'])] Project $project): Response
    {
        $task = new Task();
        $task->setStatus(TaskStatus::tryFrom(strtoupper($request->query->get('status', ''))) ?? TaskStatus::TODO);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion manuelle des assignees sélectionnés
            $ids = $request->request->all('assignees'); // tableau
            $ids = array_filter(array_unique(array_map('intval', (array) $ids)), fn($v) => $v > 0);

            foreach ($ids as $id) {
                $employee = $em->getRepository(Employee::class)->find($id);
                if ($employee) {
                    $task->addAssignee($employee);
                }
            }
            $project->addTask($task);
            $em->persist($task);
            $em->flush();
            $this->addFlash('success', 'Tâche créée');

            return $this->redirectToRoute('task_show', ['id' => $task->getId()]);
        }
        $employees = $em->getRepository(Employee::class)->findAll();

        return $this->render('task/form.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
            'is_edit' => false,
            'employees' => $employees,
        ]);
    }
}
