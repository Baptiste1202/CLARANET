<?php
// src/Form/TagType.php
namespace App\Form;

use App\Entity\Optionnel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionnelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        /*
        ->add('labelOption', type:EntityType::class, options:[
            'label' => 'Option',
            'class' => Optionnel::class,
            'choice_label' => function (Optionnel $option){
                return $option->getLabelOption();
            }
        ])
        */
        ->add('labelOption', type:TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Optionnel::class,
        ]);
    }
}