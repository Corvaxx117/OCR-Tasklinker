<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Task;
use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\ProjectStatus;
use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
                'label' => 'Deadline'
            ])
            ->add('projectStatus', EntityType::class, [
                'class' => ProjectStatus::class,
                'choice_label' => 'label',
                'label' => 'Statut'
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => fn(Employee $e) => $e->getFirstName() . ' ' . $e->getLastName(),
                'label' => 'Assigné à'
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'label' => 'Projet'
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => fn(Tag $t) => $t->getType() . ': ' . $t->getLabel(),
                'multiple' => true,
                'required' => false,
                'label' => 'Tags'
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
