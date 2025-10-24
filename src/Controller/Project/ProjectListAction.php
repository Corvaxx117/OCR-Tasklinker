<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Repository\ProjectRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projects', name: 'project_list')]
#[Route('/', name: 'homepage')]
class ProjectListAction extends AbstractController
{
    public function __invoke(ProjectRepository $projectRepository, LoggerInterface $logger): Response
    {
        $projects = $projectRepository->findAllActive();
        $logger->info('Project list accessed', ['count' => count($projects)]);

        return $this->render('project/list.html.twig', [
            'projects' => $projects,
        ]);
    }
}
