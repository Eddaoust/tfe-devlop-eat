<?php

namespace App\Form;

use App\Entity\Step;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('study', DateTimeType::class)
            ->add('mastery', DateTimeType::class)
            ->add('permitStart', DateTimeType::class)
            ->add('permitEnd', DateTimeType::class)
            ->add('worksStart', DateTimeType::class)
            ->add('worksEnd', DateTimeType::class)
            ->add('delivery', DateTimeType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Step::class,
        ]);
    }
}
