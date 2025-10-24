<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projects/{id}/archive', name: 'project_archive', methods: ['DELETE'])]
class ProjectArchiveAction extends AbstractController
{
    public function __invoke(Project $project, Request $request, EntityManagerInterface $em): Response
    {
        $project->setIsArchived(true);
        $em->flush();
        $this->addFlash('success', 'Projet archivé');

        return $this->redirectToRoute('project_list');
    }
}
