<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Project;
use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom du projet'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description'
            ])
            ->add('startedAt', DateTimeType::class, [
                'required' => true,
                'label' => 'Date de démarrage',
                'widget' => 'single_text'
            ])
            ->add('deadline', DateTimeType::class, [
                'required' => false,
                'label' => 'Deadline',
                'widget' => 'single_text'
            ])
            ->add('members', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => fn(Employee $e) => $e->getFirstName() . ' ' . $e->getLastName(),
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'label' => 'Inviter des membres'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
