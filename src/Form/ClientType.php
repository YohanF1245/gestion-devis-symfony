<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'required' => 'false',
                'label' => 'Nom'
            ])
            ->add('last_name', TextType::class,[
                'required' => 'false',
                'label' => 'Prénom'
            ])
            ->add('business_name', TextType::class,[
                'required' => 'false',
                'label' => 'Nom de la société'
            ])
            ->add('is_physick', CheckboxType::class,[
                'required' => 'false',
                'label' => 'Personne physique'
            ] )
            ->add('mail', EmailType::class,[
                'label' => 'E-mail'
            ])
            ->add('num_street', TextType::class,[
                'label' => 'Numéro de rue'
            ])
            ->add('street', TextType::class,[
                'label' => 'Rue'
            ])
            ->add('zip_postal', TextType::class, [
                'label' => 'Code postal'
            ])
            ->add('index_tel', TextType::class,[
                'required' => 'false',
                'label' => 'Index téléphone'
            ])
            ->add('phone_number', TextType::class, [
                'label' => 'Numéro de téléphone'
            ])
            ->add('town', TextType::class, [
                'label' => "Ville"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
