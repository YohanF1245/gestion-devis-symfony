<?php

namespace App\Form;

use App\Entity\DressEstimate;
use App\Entity\EstimateTab;
use App\Entity\FactureEmit;
use App\Repository\DressEstimateRepository;
use App\Repository\FactureEmitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureEmitType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('creation_date', null, [
                'widget' => 'single_text',
            ])
            ->add('payment_date', null, [
                'widget' => 'single_text',
            ])
            ->add('majoration')
            ->add('date_limit', null, [
                'widget' => 'single_text',
            ])
            ->add('is_paid')
            // ->add('estimateTab', EntityType::class, [
            //     'class' => EstimateTab::class,
            //     'choice_label' => 'id',
            // ])
            ->add('estimate_tab')
        ;
    }
   
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FactureEmit::class,
            'user_id' => null,
        ]);
    }
}
