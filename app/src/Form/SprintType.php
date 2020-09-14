<?php

namespace App\Form;

use App\Entity\Sprint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SprintType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Week', IntegerType::class,
                [
                    'invalid_message' => 'неделя должна быть числом!',
                    'constraints' => [new NotBlank(['message' => 'Укажите неделю спринта!'])]
                ]
            )
            ->add('Year', IntegerType::class,
                [
                    'invalid_message' => 'год должен быть числом!',
                    'constraints' => [new NotBlank(['message' => 'Укажите год спринта!'])]
                ]
            )
            ->add('status', TextType::class, [
                'empty_data' => '',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sprint::class,
            'csrf_protection' => false
        ]);
    }
}
