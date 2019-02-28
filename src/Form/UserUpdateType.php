<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => false
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'required' => false
            ])
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance',
                'required' => false,
                'format' => 'dd-MM-yyyy',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('image', ProfilImageType::class, [
                'label' => 'Image de profil',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
