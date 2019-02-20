<?php

namespace App\Form;

use App\Entity\Step;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('study', DateType::class, [
                'label' => 'Etude',
                'required' => false,
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y')-10, date('Y')+10),
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('mastery', DateType::class, [
                'label' => 'Maîtrise',
                'required' => false,
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y')-10, date('Y')+10),
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('permitStart', DateType::class, [
                'label' => 'Permis (début)',
                'required' => false,
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y')-10, date('Y')+10),
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('permitEnd', DateType::class, [
                'label' => 'Permit (fin)',
                'required' => false,
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y')-10, date('Y')+10),
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('worksStart', DateType::class, [
                'label' => 'Travaux (début)',
                'required' => false,
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y')-10, date('Y')+10),
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('worksEnd', DateType::class, [
                'label' => 'Travaux (fin)',
                'required' => false,
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y')-10, date('Y')+10),
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('delivery', DateType::class, [
                'label' => 'Livraison',
                'required' => false,
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y')-10, date('Y')+10),
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Step::class,
        ]);
    }
}
