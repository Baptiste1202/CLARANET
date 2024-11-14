<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Wheel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WheelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'brand', type:EntityType::class, options:[
                'label' => 'Marque',
                'class' => Brand::class,
                'choice_label' => function (Brand $brand){
                    return $brand->getLabelBrand().' toto';
                }
            ])
            ->add(child: 'size', type: IntegerType::class, options:[
                'label' => 'Taille'
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wheel::class,
        ]);
    }
}
