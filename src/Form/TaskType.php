<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Task;
use App\Entity\Employee;
use App\Entity\Project;
use App\Enum\TaskStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Titre'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description'
            ])
            ->add('deadline', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Deadline',
                'html5' => true,
                'attr' => [
                    'min' => (new \DateTimeImmutable('today'))->format('Y-m-d\TH:i'),
                ],
            ])
            ->add('status', EnumType::class, [
                'class' => TaskStatus::class,
                'label' => 'Statut',
                'choice_label' => fn(TaskStatus $s) => $s->label(),
            ])
            ->add('assignees', EntityType::class, [
                'class' => Employee::class,
                'multiple' => true,
                'by_reference' => false, // pour que add/removeAssignee soient appelés
                'choice_label' => fn(Employee $e) => $e->getFirstName() . ' ' . $e->getLastName(),
                'label' => 'Assigner des membres',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
