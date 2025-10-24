<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projects/{id}/delete', name: 'project_delete', methods: ['POST'])]
class ProjectDeleteAction extends AbstractController
{
    public function __invoke(Project $project, Request $request, EntityManagerInterface $em): Response
    {
        // Remove CSRF token validation  ici  
        $em->remove($project);
        $em->flush();
        $this->addFlash('success', 'Projet supprimé');
        return $this->redirectToRoute('project_list');
    }
}
