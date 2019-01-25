<?php

namespace App\Form;

use App\Entity\Architect;
use App\Entity\Company;
use App\Entity\GeneralCompany;
use App\Entity\Project;
use App\Repository\CompanyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            ->add('name', TextType::class, $this->getConfiguration('Nom *', true, 'Entrez le nom du projet'))
            ->add('address', TextType::class, $this->getConfiguration('Adresse', false, 'Entrez l\'adresse du projet'))
            ->add('postalCode', TextType::class, $this->getConfiguration('Code postal', false, 'Entrez le code postal du projet'))
            ->add('city', TextType::class, $this->getConfiguration('Ville', false, 'Entrez la ville du projet'))
            ->add('description', TextareaType::class, $this->getConfiguration('Description', false, 'Description du projet'))
            ->add('pointOfInterest', TextareaType::class, $this->getConfiguration('Point d\'intérêt', false, 'Ajoutez les points d\'intérêts'))
            ->add('fieldSize', IntegerType::class, $this->getConfiguration('Dimension du terrain', false, 'Dimension du terrai en are'))
            ->add('turnover', MoneyType::class, $this->getConfiguration('Chiffre d\'affaire', false, 'Chiffre d\'affaire'))
            ->add('lots', IntegerType::class, $this->getConfiguration('Lots', false, 'Nombres de lots'))
            ->add('projectOwner', EntityType::class, [
                'label' => 'Project owner *',
                'class' => Company::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un project owner'
            ])
            ->add('architect', EntityType::class, [
                'label' => 'Architecte',
                'required' => false,
                'class' => Architect::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un architecte'
            ])
            ->add('generalCompany', EntityType::class, [
                'label' => 'Entreprise générale',
                'required' => false,
                'class' => GeneralCompany::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une entreprise générale'
            ])
            ->add('projectImages', CollectionType::class, [
                'entry_type' => ProjectImagesType::class,
                'allow_add' => true,
                'label' => 'Image du projet'
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
            'data_class' => Project::class,
        ]);
    }
}
