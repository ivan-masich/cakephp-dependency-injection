<?php

namespace dependency_injection\libs;

use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use \Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\Yaml\Yaml;

/**
 * @author Masich Ivan <john@masich.com>
 */
class ContainerConfigurator
{
    /**
     * @var array
     */
    private $mainConfig = array(
        'appConfigFileType' => 'xml', // xml or yaml
        'appConfigFileName' => 'di_services',
        'pluginConfigFileName' => 'di_plugin_config',
    );

    /**
     * @var \Symfony\Component\DependencyInjection\Loader\XmlFileLoader
     */
    private  $loader;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    private $yaml;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private $container;

    /**
     * 
     */
    public function __construct()
    {
        $this->loadMainConfig();

        $this->container = new ContainerBuilder();

        $this->configureApp();

        $this->configurePlugins();

        $this->container->compile();
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return \Symfony\Component\Yaml\Yaml
     */
    private function getYaml()
    {
        if (!$this->yaml) {
            $this->yaml = new Yaml();
        }

        return $this->yaml;
    }

    /**
     * Load configuration from app/config/di.ini
     *
     * @return void
     */
    private function loadMainConfig()
    {
        $fileConfig = array();
        
        if (file_exists(CONFIGS . 'di.ini')) {
            $fileConfig = parse_ini_file(CONFIGS . 'di.ini');
        } else if (file_exists(CONFIGS . 'di.yml')) {
            $fileConfig = $this->getYaml()->parse(CONFIGS . 'di.yml');
        }

        $this->mainConfig = array_merge($this->mainConfig, $fileConfig);
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Loader\XmlFileLoader
     */
    private function getXmlFileLoader()
    {
        if (empty($this->loader['xml'])) {
            $this->loader['xml'] = new XmlFileLoader($this->container, new FileLocator());
        }

        return $this->loader['xml'];
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Loader\YamlFileLoader
     */
    private function getYamlFileLoader()
    {
        if (empty($this->loader['yaml'])) {
            $this->loader['yaml'] = new YamlFileLoader($this->container, new FileLocator());
        }

        return $this->loader['yaml'];
    }

    /**
     * @return void
     */
    private function configureApp()
    {
        if ($this->mainConfig['appConfigFileType'] == 'xml') {
            if (file_exists(CONFIGS . $this->mainConfig['appConfigFileName'] . '.xml')) {
                $this->getXmlFileLoader()->load(CONFIGS . $this->mainConfig['appConfigFileName'] . '.xml');
            }
        } else {
            if (file_exists(CONFIGS . $this->mainConfig['appConfigFileName'] . '.yml')) {
                $this->getYamlFileLoader()->load(CONFIGS . $this->mainConfig['appConfigFileName'] . '.yml');
            }
        }
    }

    /**
     * @return void
     */
    private function configurePlugins()
    {
        foreach (array() as $plugin) {
            if (file_exists(APP . DS . 'plugins' . DS . $plugin . DS . 'DependencyInjection' . DS . 'Extension.php')) {
                $extensionClass = '\\' . $plugin . '\\DependencyInjection\\Extension';
                $this->container->registerExtension(new $extensionClass());
                $this->container->loadFromExtension($plugin, array());
            }
        }
    }
}
