<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/team', name: 'team_list')]
class TeamListAction extends AbstractController
{
    /**
     * Affiche la liste des employés actifs.
     *
     * @param EmployeeRepository $employeeRepository
     * @return Response
     */
    public function __invoke(EmployeeRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();
        return $this->render('team/list.html.twig', [
            'employees' => $employees,
        ]);
    }
}
