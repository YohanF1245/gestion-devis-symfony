<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\DressEstimate;
use App\Entity\EstimateTab;
use App\Entity\Performance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DressEstimateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('client', EntityType::class, [
            //     'class' => Client::class,
            //     'choice_label' => 'id',
            // ])
            ->add('creation_date', null, [
                'widget' => 'single_text',
                'label' => "Date de création du devis",
            ])
            //->add('estimate_number')
            ->add('validity',null,[
                'label' =>"Validité (jours)",
            ])
            ->add('expiration_date', null, [
                'widget' => 'single_text',
                'label' => "Date d'expiration du devis",
            ])
            ->add('intitule',null, [
                'label' =>"Intitulé du devis",
            ])
            ->add('is_valid', ChoiceType::class, [
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
                'label' =>"Le devis a-t-il été validé et signé ?"
            ])
            ->add('free_zone',null, [
                'label' =>"Zone libre",
            ])
            ->add('accompte',null, [
                'label' =>"Accompte (%)",
            ])
            ->add('discount',null, [
                'label' =>"Remise (%)",
            ])
            // ->add('id', EntityType::class, [
            //     'class' => Performance::class,
            //     'choice_label' => 'designation',
            // ])
            // ->add('client_id', EntityType::class, [
            //     'class' => Client::class,
            //     'choice_label' => 'name'.' '.'lastname'. ' '.'business_name' ,
            // ])
            // ->add('id', CollectionType::class, [
            //     'entry_type' => PerformanceType::class,
            //     'entry_options' => ['label' => false],
            //     'allow_add' => true,
            // ])
            // ->add('estimateTab', EntityType::class, [
            //     'class' => EstimateTab::class,
            //     'choice_label' => 'name',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DressEstimate::class,
        ]);
    }
}
