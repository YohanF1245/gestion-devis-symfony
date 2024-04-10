<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', TextType::class,[
            "required" => true,
            "constraints" => [
                new NotBlank(message :"le champ doit être renseigné"),
                new Email(),
                new NotNull(),
            ]
        ])
        ->add('pseudo', TextType ::class, [
            "required" => true,
            "constraints" => [
                new NotBlank(message : "le champ doit etre renseigné"),
                new NotNull(),
            ]
        ])
        ->add('signature', FileType::class, [
            "required" => false,
            "mapped" => false,
            "constraints" => [
                new Image()
            ]
        ])
        ->add('password', RepeatedType::class,[
            'type' => PasswordType::class,
            'invalid_message' => "Les mots de passe doivent correspondre",
            'options' => [
                'attr' => [
                    'class' => 'password-field',
                ]
            ],
            'required' => true,
            'first_options' => ['label' => 'Entrez le mot de passe : '],
            'second_options' => ['label' => 'Confirmer le mot de passe : ']
        ])
        // ->add('Envoyer', SubmitType::class, [
        //     'label' => 'Envoyer'
        // ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
