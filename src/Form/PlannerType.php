<?php

namespace App\Form;

use App\Entity\Planner;
use App\Entity\Recipe;
use App\Entity\Time;
use App\Entity\User;
use App\Entity\Week;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlannerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            ->add('day', EntityType::class, [
                'class' => Week::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('recipe', EntityType::class, [
                'class' => Recipe::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('time', EntityType::class, [
                'class' => Time::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planner::class,
        ]);
    }
}
