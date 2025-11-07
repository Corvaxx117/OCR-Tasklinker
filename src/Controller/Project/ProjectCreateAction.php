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

#[Route('/projects/new', name: 'project_create')]
class ProjectCreateAction extends AbstractController
{
    /**
     * Crée un projet.
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function __invoke(Request $request, EntityManagerInterface $em): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->persist($project);
                $em->flush();
                $this->addFlash('success', 'Projet créé');
                return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
            }
            // Ajout flash ciblé si violation deadline
            $deadlineErrors = $form->get('deadline')->getErrors(true);
            foreach ($deadlineErrors as $error) {
                $this->addFlash('error', $error->getMessage());
            }
            if ($deadlineErrors->count() === 0) {
                // Fallback générique
                $this->addFlash('error', 'Le formulaire contient des erreurs.');
            }
        }

        return $this->render('project/form.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
            'is_edit' => false,
        ]);
    }
}
