<?php

namespace ContainerDxyFfGq;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getConsole_Command_RouterDebugService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'console.command.router_debug' shared service.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Command\RouterDebugCommand
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'\\vendor\\symfony\\console\\Command\\Command.php';
        include_once \dirname(__DIR__, 4).'\\vendor\\symfony\\framework-bundle\\Command\\BuildDebugContainerTrait.php';
        include_once \dirname(__DIR__, 4).'\\vendor\\symfony\\framework-bundle\\Command\\RouterDebugCommand.php';

        $container->privates['console.command.router_debug'] = $instance = new \Symfony\Bundle\FrameworkBundle\Command\RouterDebugCommand(($container->services['router'] ?? $container->getRouterService()), ($container->privates['debug.file_link_formatter'] ?? $container->getDebug_FileLinkFormatterService()));

        $instance->setName('debug:router');

        return $instance;
    }
}
