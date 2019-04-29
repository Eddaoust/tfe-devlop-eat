<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\CompanyCategory;
use App\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    /**
     * @param $label
     * @param $placeholder
     * @return array
     */
    private function getConfiguration($label, $required, $placeholder)
    {
        return [
            'label' => $label,
            'required' => $required,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration('Nom *', true, 'Entrez le nom de la société'))
            ->add('country', EntityType::class, [
                'label' => 'Pays',
                'class' => Country::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un pays'
            ])
            ->add('companyCategory', EntityType::class, [
                'label' => 'Forme juridique',
                'class' => CompanyCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un type de société'
            ])
            ->add('address', TextType::class, $this->getConfiguration('Adresse', false, 'Entrez l\'adresse'))
            ->add('postalCode', TextType::class, $this->getConfiguration('Code Postal', false, 'Entrez le code postal'))
            ->add('city', TextType::class, $this->getConfiguration('Ville', false, 'Entrez la ville'))
            ->add('phone', TextType::class, $this->getConfiguration('Téléphone', false, 'Entrez le numéro de téléphone'))
            ->add('tvaNum', TextType::class, $this->getConfiguration('Numéro de TVA', false, 'Entrez le numéro de téléphone'))
            ->add('registrationNum', TextType::class, $this->getConfiguration('Numero d\'enregistrement', false, 'Pour le Luxembourg'))
            ->add('bank', TextType::class, $this->getConfiguration('Banque', false, 'Nom de la banque'))
            ->add('bankAccount', TextType::class, $this->getConfiguration('Compte banquaire',false, 'Numero de compte banquaire'))
            ->add('shareholders', CollectionType::class, [
                'label' => 'Actionnaire(s)',
                'entry_type' => ShareholderType::class,
                'allow_add' => true,
                'by_reference' => false
            ])
            ->add('Ajouter', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
