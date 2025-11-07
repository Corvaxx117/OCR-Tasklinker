<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\Project;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projects/{id}/edit', name: 'project_edit')]
class ProjectEditAction extends AbstractController
{
    /**
     * Modifie un projet.
     *
     * @param Project $project
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     *
     * @throws \RuntimeException si le projet est archivé.
     */
    public function __invoke(Project $project, Request $request, EntityManagerInterface $em): Response
    {
        if ($project->isArchived()) {
            return $this->redirectToRoute('project_list');
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->flush();
                $this->addFlash('success', 'Projet mis à jour');
                return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
            }
            $deadlineErrors = $form->get('deadline')->getErrors(true);
            foreach ($deadlineErrors as $error) {
                $this->addFlash('error', $error->getMessage());
            }
            if ($deadlineErrors->count() === 0) {
                $this->addFlash('error', 'Le formulaire contient des erreurs.');
            }
        }

        return $this->render('project/form.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
            'is_edit' => true,
        ]);
    }
}
