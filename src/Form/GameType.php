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

            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée souhaitée',
                'label_attr' => ['class' => 'fs-3 p-2 bg-white bg-opacity-75 mb-2 shadow-sm'],
                'attr' => [
                    'min' => 0,
                    'max' => 60,
                    'class' => 'p-3 border-0 mt-3 shadow-sm text-center'
                ],
                'constraints' => [
                    new LessThan(
                        value: 60
                    ),
                    new Positive()
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Maître du jeu' => 'mdj',
                    'Solo' => 'solo'
                ],
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
