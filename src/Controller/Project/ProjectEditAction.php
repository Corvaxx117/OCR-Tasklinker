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
    public function __invoke(Project $project, Request $request, EntityManagerInterface $em): Response
    {
        if ($project->isArchived()) {
            return $this->redirectToRoute('project_list');
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return $this->render('project/form.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
            'is_edit' => true,
        ]);
    }
}
