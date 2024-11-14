<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Vehicule;
use App\Entity\Wheel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;
use Vich\UploaderBundle\Form\Type\VichImageType;

class VehiculesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', type:EntityType::class, options:[
                'label' => 'Marque',
                'class' => Brand::class,
                'choice_label' => function (Brand $brand){
                    return $brand->getLabelBrand().' toto';
                }
            ])
            ->add('model', options:[
                'label' => 'Modèle'
            ])
            ->add('year', options:[
                'label' => 'Année'
            ])

            ->add('imageFile', VichImageType::class, options:[
                'required' => false, 
            ])

            ->add('kilometrage', options:[
                'label' => 'Kilométrage'
            ])
            ->add('price', IntegerType::class,
            [
                'label' => 'Prix',
                'required' => true,
                'constraints' => [
                    new Range(min: 50)
                ]
            ])
            ->add('categorie', options:[
                'label' => 'Catégorie'
            ])
            ->add('location', options:[
                'label' => 'Localisation'
            ])
            ->add('countDoors', options:[
                'label' => 'Nombre de portes'
            ])
            ->add('wheel', type:EntityType::class, options:[
                'label' => 'Modèle de roues',
                'class' => Wheel::class,
                'choice_label' => function (Wheel $wheel){
                    return $wheel->getBrand()->getLabelBrand().' - '.$wheel->getSize();
                }
            ])
            ->add('optionnels', type:CollectionType::class, options:[
                'entry_type' => OptionnelFormType::class, 
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'empty_data' => ''
            ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
