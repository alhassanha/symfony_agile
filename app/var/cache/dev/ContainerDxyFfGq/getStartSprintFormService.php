<?php

namespace ContainerDxyFfGq;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getStartSprintFormService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'App\Form\StartSprintForm' shared autowired service.
     *
     * @return \App\Form\StartSprintForm
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'\\vendor\\symfony\\form\\FormTypeInterface.php';
        include_once \dirname(__DIR__, 4).'\\vendor\\symfony\\form\\AbstractType.php';
        include_once \dirname(__DIR__, 4).'\\src\\Form\\StartSprintForm.php';

        return $container->privates['App\\Form\\StartSprintForm'] = new \App\Form\StartSprintForm();
    }
}
