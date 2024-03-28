<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\ResetPass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mail')
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
            ->add('pseudo')
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
