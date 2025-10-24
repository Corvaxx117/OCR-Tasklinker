<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Entity\Employee;
use App\Form\EmployeeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/team/{id}/edit', name: 'employee_edit')]
class EmployeeEditAction extends AbstractController
{
    public function __invoke(Employee $employee, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('team_list');
        }

        return $this->render('team/edit.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
        ]);
    }
}
