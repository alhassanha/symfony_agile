<?php

namespace App\Form;

use App\Form\Transformer\EstimationTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TaskEstimationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'id',
                TextType::class,
                [
                    'constraints' => [new NotBlank(['message' => 'Укажите Id задачи!'])]
                ]
            )
            ->add(
                'estimation',
                TextType::class,
                [
                    'constraints' => [new NotBlank(['message' => 'Укажите оценку задачи!'])],
                    'invalid_message' => 'неправильный формат оценки! (пример правильного формата: 1d, 2.5h, 30m)'
                ]
            )
        ;
        $builder->get('estimation')
            ->addModelTransformer(new EstimationTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}
