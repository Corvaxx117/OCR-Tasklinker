<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/team/{id}/delete', name: 'employee_delete', methods: ['POST'])]
class EmployeeDeleteAction extends AbstractController
{
    public function __invoke(Employee $employee, Request $request, EntityManagerInterface $em): Response
    {
        $em->remove($employee);
        $em->flush();
        $this->addFlash('success', 'Membre supprimé');

        return $this->redirectToRoute('team_list');
    }
}
