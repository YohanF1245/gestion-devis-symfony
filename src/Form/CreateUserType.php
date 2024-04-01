<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\ResetPass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class CreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mail', TextType::class,[
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
            "attr" => [
                "novalidate" => "novalidate",
            ]
        ]);
    }
}
