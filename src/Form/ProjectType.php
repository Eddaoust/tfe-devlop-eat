<?php

namespace App\Form;

use App\Entity\Architect;
use App\Entity\Company;
use App\Entity\GeneralCompany;
use App\Entity\Project;
use App\Repository\ArchitectRepository;
use App\Repository\CompanyRepository;
use App\Repository\GeneralCompanyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
            ->add('fieldSize', IntegerType::class, $this->getConfiguration('Dimension du terrain', false, 'Dimension du terrain en are'))
            ->add('turnover', MoneyType::class, $this->getConfiguration('Chiffre d\'affaire', false, 'Chiffre d\'affaire'))
            ->add('lots', IntegerType::class, $this->getConfiguration('Lots', false, 'Nombres de lots'))
            ->add('projectOwner', EntityType::class, [
                'label' => 'Project owner *',
                'class' => Company::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un project owner',
                'query_builder' => function (CompanyRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('architect', EntityType::class, [
                'label' => 'Architecte',
                'required' => false,
                'class' => Architect::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un architecte',
                'query_builder' => function (ArchitectRepository $ar) {
                    return $ar->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                }
            ])
            ->add('generalCompany', EntityType::class, [
                'label' => 'Entreprise générale',
                'required' => false,
                'class' => GeneralCompany::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une entreprise générale',
                'query_builder' => function (GeneralCompanyRepository $gcr) {
                    return $gcr->createQueryBuilder('gc')
                        ->orderBy('gc.name', 'ASC');
                }
            ])
            ->add('img1', FileType::class, [
                'label' => 'Image 1 du projet',
                'required' => false
            ])
            ->add('img2', FileType::class, [
                'label' => 'Image 2 du projet',
                'required' => false
            ])
            ->add('img3', FileType::class, [
                'label' => 'Image 3 du projet',
                'required' => false
            ])
            ->add('Ajouter', SubmitType::class, [
                'label' => 'Valider',
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
