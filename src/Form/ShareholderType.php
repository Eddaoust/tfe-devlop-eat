<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Shareholder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShareholderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('part', IntegerType::class, [
                'label' => 'Parts',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nombre de parts en %'
                ]
            ])
            ->add('shareholder', EntityType::class, [
                'label' => 'Actionnaire',
                'required' => true,
                'class' => Company::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un actionnaire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Shareholder::class,
        ]);
    }
}
