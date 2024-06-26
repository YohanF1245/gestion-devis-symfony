<?php

namespace App\Form;

use App\Entity\EstimateTab;
use App\Entity\Performance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PerformanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité'
            ])
            ->add('designation', TextType::class, [
                'label' => 'Désignation'
            ])
            // ->add('tax')
            ->add('pirce', NumberType::class, [
                'label' => 'Prix unitaire'
            ])
            ->add('unit', TextType::class, [
                'label' => 'Unité de mesure'
            ])
            
            ->add('tax', ChoiceType::class, [
                'label' => 'T.V.A',
                'choices' => [
                    '0%' => 0,
                    '2.1%' => 2.1,
                    '5%' => 5,
                    '10' => 10,
                    '20' => 20,
                ]
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Performance::class,
        ]);
    }
}
