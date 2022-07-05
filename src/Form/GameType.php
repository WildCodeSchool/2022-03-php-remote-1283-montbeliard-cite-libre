<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\Positive;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nommez la partie',
                'label_attr' => ['class' => 'fs-3 p-2 mb-2 text-center shadow-sm'],
                'required'   => true,
                'attr' => [
                    'placeholder' => 'Nom de la partie',
                    'class' => 'fs-3 p-2 shadow-sm'
                ]
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée souhaitée (max 60 minutes)',
                'label_attr' => ['class' => 'fs-3 mt-3 p-2   shadow-sm'],
                'attr' => [
                    'min' => 0,
                    'max' => 60,
                    'class' => 'p-3 border-0 mt-3 shadow-sm text-center w-auto ',
                    'placeholder' => '45'
                ],
                'constraints' => [
                    new LessThan(
                        value: 60
                    ),
                    new Positive()
                ],
                'empty_data' => '45',
            ])

            ->add('type', ChoiceType::class, [
                'label' => 'Type de jeu',
                'label_attr' => ['class' => 'fs-3 w-auto p-2  '],
                'choices' => [
                    'Maître du jeu' => 'mdj',
                    'Solo' => 'solo'
                ],
                'attr' => [
                    'class' => 'form-check fw-bold '
                ],
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
