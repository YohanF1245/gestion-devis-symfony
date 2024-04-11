<?php

namespace App\Form;

use App\Entity\Business;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class BusinessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('siret', TextType::class, [
                "constraints" => [
                    new Regex('/[0-9]+/', message:"Un siret est composé uniquement de chiffre"),
                    new Length([
                        "min" => 14,
                        "max" => 14,
                        "minMessage" => "le siret est trop petit",
                        "maxMessage" => "le siret est trop grand"
                    ]),
                ]
            ])
            ->add('logo', FileType::class, [
                "required" => false,
                "mapped" => false,
                "constraints" => [
                    new Image()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Business::class,
        ]);
    }
}
