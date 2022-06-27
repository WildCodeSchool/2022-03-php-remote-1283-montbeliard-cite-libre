<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur",
            ])
            ->add('password', null, [
                'label' => 'Mot de passe',
            ])
            ->add('classe', null, ['choice_label' => 'classe',]);

        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            $builder
                ->add('roles', ChoiceType::class, [
                    'label' => "Rôles",
                    'expanded' => true,
                    'multiple' => true,
                    'choices' => [
                        'Administrateur' => 'ROLE_ADMIN',
                        'Super administrateur' => 'ROLE_SUPER_ADMIN'
                    ]
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
