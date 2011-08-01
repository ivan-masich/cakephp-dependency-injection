<?php

namespace dependency_injection\libs;

use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use \Symfony\Component\Config\FileLocator;

/**
 * @author Masich Ivan <john@masich.com>
 */
class Container
{
    /**
     * @var ContainerConfigurator
     */
    protected static $instance;

    /**
     * @static
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new ContainerConfigurator();
        }
        
        return self::$instance->getContainer();
    }
}