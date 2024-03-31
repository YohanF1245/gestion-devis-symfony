<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\ResetPass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class CreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mail', EmailType::class,[
                "required" => true,
                "constraints" => [
                    new NotBlank(message :"le champs doit être renseigné"),
                    new Email(),
                    new NotNull(),
                    new UniqueEntity([
                        "entityClass" => Users::class,
                        "fields" => "mail"
                    ], message:" email deja utiilsé"),
                ]
            ])
            ->add('pseudo', TextType ::class, [
                "required" => true,
                "constraints" => [
                    new NotBlank(),
                    new NotNull(),
                ]
            ])
            ->add('siret')
            ->add('signature', FileType::class, [
                "mapped" => false,
                "constraints" => [
                    new Image()
                ]
            ])
            ->add('pass', RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de pass doivent correspondre",
                'options' => [
                    'attr' => [
                        'class' => 'password-field',
                    ]
                ],
                'required' => true,
                'first_options' => ['label' => 'Entrez le mot de passe : '],
                'second_options' => ['label' => 'Confirmer le mot de passe : ']
            ])
            ->add('Envoyer', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
