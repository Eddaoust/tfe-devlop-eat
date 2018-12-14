<?php

namespace App\Form;

use App\Entity\Architect;
use App\Entity\Company;
use App\Entity\GeneralCompany;
use App\Entity\Project;
use App\Repository\CompanyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    /**
     * @param $label
     * @param $placeholder
     * @return array
     */
    private function getConfiguration($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration('Nom', 'Entrez le nom du projet'))
            ->add('address', TextType::class, $this->getConfiguration('Adresse', 'Entrez l\'adresse du projet'))
            ->add('postalCode', TextType::class, $this->getConfiguration('Code postal', 'Entrez le code postal du projet'))
            ->add('city', TextType::class, $this->getConfiguration('Ville', 'Entrez la ville du projet'))
            ->add('description', TextareaType::class, $this->getConfiguration('Description', 'Description du projet'))
            ->add('pointOfInterest', TextareaType::class, $this->getConfiguration('Point d\'intérêt', 'Ajoutez les points d\'intérêts'))
            ->add('fieldSize', IntegerType::class, $this->getConfiguration('Dimension du terrain', 'Dimension du terrai en are'))
            ->add('turnover', MoneyType::class, $this->getConfiguration('Chiffre d\'affaire', 'Chiffre d\'affaire'))
            ->add('lots', IntegerType::class, $this->getConfiguration('Lots', 'Nombres de lots'))
            ->add('projectOwner', EntityType::class, [
                'label' => 'Project owner',
                'class' => Company::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un project owner'
            ])
            ->add('architect', EntityType::class, [
                'label' => 'Architecte',
                'class' => Architect::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un architecte'
            ])
            ->add('generalCompany', EntityType::class, [
                'label' => 'Entreprise générale',
                'class' => GeneralCompany::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une entreprise générale'
            ])
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
