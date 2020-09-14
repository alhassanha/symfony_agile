<?php

namespace ContainerJM0Tikv;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getTasksApiControllerService extends App_KernelDevDebugContainer
{
    /**
     * Gets the public 'App\Controller\TasksApiController' shared autowired service.
     *
     * @return \App\Controller\TasksApiController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/framework-bundle/Controller/AbstractController.php';
        include_once \dirname(__DIR__, 4).'/src/Controller/TasksApiController.php';

        $container->services['App\\Controller\\TasksApiController'] = $instance = new \App\Controller\TasksApiController();

        $instance->setContainer(($container->privates['.service_locator.g9CqTPp'] ?? $container->load('get_ServiceLocator_G9CqTPpService'))->withContext('App\\Controller\\TasksApiController', $container));

        return $instance;
    }
}