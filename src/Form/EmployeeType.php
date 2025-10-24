<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
                'label' => 'Prénom'
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'label' => 'Nom'
            ])
            ->add('mail', EmailType::class, [
                'required' => true,
                'label' => 'E-mail'
            ])
            ->add('accessStatus', EnumType::class, [
                'class' => \App\Enum\AccessStatus::class,
                'required' => true,
                'label' => 'Accès',
                'choice_label' => function (\App\Enum\AccessStatus $status) {
                    return match ($status) {
                        \App\Enum\AccessStatus::ADMIN => 'Administrateur',
                        \App\Enum\AccessStatus::USER => 'Utilisateur',
                    };
                },
            ])
            ->add('contractType', EnumType::class, [
                'class' => \App\Enum\ContractType::class,
                'required' => true,
                'label' => 'Type de contrat',
                'choice_label' => function (\App\Enum\ContractType $contract) {
                    return ucfirst($contract->value);
                },
            ])
            ->add('startedAt', DateType::class, [
                'required' => true,
                'label' => "Date d'entrée",
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
