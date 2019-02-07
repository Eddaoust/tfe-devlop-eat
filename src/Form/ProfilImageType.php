<?php

namespace App\Form;

use App\Entity\ProfilImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class ProfilImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'label' => false,
                'constraints' => [
                    new File(['mimeTypes' => ['image/png', 'image/jpeg']]),
                    new Image([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'L\'image est trop lourde ({{ size }} {{ suffix }}). Le poid maximum est de {{ limit }} {{ suffix }}.',
                        'minRatio' => 0.5,
                        'maxRatio' => 1.5,
                        'minRatioMessage' => 'Le ratio de l\'image est trop petit({{ ratio }}). Le ratio minimum accepté est: {{ min_ratio }}',
                        'maxRatioMessage' => 'Le ratio de l\'image est trop grand({{ ratio }}). Le ratio maximum accepté est: {{ max_ratio }}'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProfilImage::class,
        ]);
    }
}
