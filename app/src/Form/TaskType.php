<?php

namespace App\Form;

use App\Entity\Tasks;
use App\Form\Transformer\EstimationTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('estimation')
            ->add('is_closed')
            ->add('project')
        ;
//        $builder->get('estimation')
//            ->addModelTransformer(new EstimationTransformer());
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class,
            'csrf_protection' => false
        ]);
    }
}
