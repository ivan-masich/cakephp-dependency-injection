<?php

namespace dependency_injection\libs;

use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use \Symfony\Component\Config\FileLocator;

/**
 * @author Masich Ivan <john@masich.com>
 */
class ContainerConfigurator
{
    /**
     * @var array
     */
    private $plugins = array();

    /**
     * @var \Symfony\Component\DependencyInjection\Loader\XmlFileLoader
     */
    private  $loader;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private $container;

    /**
     *
     */
    public function __construct($plugins = null)
    {
        if ($plugins) {
            $this->plugins = $plugins;
        }

        $this->container = new ContainerBuilder();

        $this->configureApp();

        $this->configurePlugins();

        //$this->container->compile();
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Loader\XmlFileLoader
     */
    private function getXmlFileLoader()
    {
        if ($this->loader == null) {
            $this->loader = new XmlFileLoader($this->container, new FileLocator());
        }

        return $this->loader;
    }

    /**
     * @return void
     */
    private function configureApp()
    {
        $this->getXmlFileLoader()->load(CONFIGS . 'services.xml');
    }

    /**
     * @return void
     */
    private function configurePlugins()
    {
        foreach ($this->plugins as $plugin) {
            if (file_exists(APP . DS . 'plugins' . DS . $plugin . DS . 'DependencyInjection' . DS . 'Extension.php')) {
                $extensionClass = '\\' . $plugin . '\\DependencyInjection\\Extension';
                $this->container->registerExtension(new $extensionClass());
                $this->container->loadFromExtension($plugin, array());
                $this->container->compile();
            }
        }
    }
}
