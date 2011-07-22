<?php

namespace dependency_injection\libs;

use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use \Symfony\Component\Config\FileLocator;

/**
 * @author Masich Ivan
 */
class Container
{
    /**
     * @var ContainerConfigurator
     */
    protected static $instance;

    /**
     * @var array
     */
    protected static $plugins;

    /**
     * @static
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new ContainerConfigurator(self::$plugins);
        }
        
        return self::$instance->getContainer();
    }

    /**
     * @static
     * @return void
     */
    public static function setPlugins(array $plugins)
    {
        self::$plugins = $plugins;
    }
}