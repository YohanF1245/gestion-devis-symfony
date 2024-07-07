<?php

namespace App\Form;

use App\Entity\Business;
use App\Entity\Outcome;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutcomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('outcome_date', null, [
                'widget' => 'single_text',
                'label' => 'Date de la dépense',
            ])
            ->add('outcome_amount', null, [
                'label' => 'Montant de la dépense',
            ])
            ->add('name', null, [
                'label' => 'Intitulé de la dépense',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Outcome::class,
        ]);
    }
}
