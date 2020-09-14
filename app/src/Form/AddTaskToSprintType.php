<?php

namespace App\Form;

use App\Form\Transformer\EstimationTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddTaskToSprintType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'sprintId',
                TextType::class,
                [
                    'constraints' => [new NotBlank(['message' => 'Укажите Id спринта!'])]
                ]
            )
            ->add(
                'taskId',
                TextType::class,
                [
                    'constraints' => [new NotBlank(['message' => 'Укажите Id задачи!'])],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}
