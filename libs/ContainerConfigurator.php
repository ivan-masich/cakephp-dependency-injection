<?php

namespace dependency_injection\libs;

use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use \Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\Yaml\Yaml;
use \dependency_injection\DependencyInjection\CompilerPassDataInterface;

/**
 * @author Masich Ivan <john@masich.com>
 */
class ContainerConfigurator
{
    /**
     * @var array
     */
    private $mainConfiguration = array(
        'cache_engine' => 'default',
        'cache_container_key' => 'diPluginContainer',
        'use_cache' => false,
        'app_config_file_name' => 'di_services',
        'plugin_config_file_name' => 'di_plugin_config',
    );

    /**
     * @var array
     */
    private $pluginsConfiguration = array();

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

        if (!$this->readContainerFromCache()) {
            $this->container = new ContainerBuilder();

            $this->configureApp();

            $this->configurePlugins();

            $this->container->compile();

            $this->writeContainerToCache();
        }
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }

    private function readContainerFromCache()
    {
        if (\Configure::read('debug') > 0 || !$this->mainConfiguration['use_cache']) {
            return false;
        }

        $key = $this->mainConfiguration['cache_container_key'];
        $config = $this->mainConfiguration['cache_engine'];

        if (($container = \Cache::read($key, $config)) === false) {
            return false;
        }

        $this->container = unserialize($container);

        return true;
    }

    private function writeContainerToCache()
    {
        if (\Configure::read('debug') > 0 || !$this->mainConfiguration['use_cache']) {
            return false;
        }

        $key = $this->mainConfiguration['cache_container_key'];
        $config = $this->mainConfiguration['cache_engine'];

        \Cache::write($key, serialize($this->container), $config);
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

        $this->mainConfiguration = array_merge($this->mainConfiguration, $fileConfig);
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
        if (file_exists(CONFIGS . $this->mainConfiguration['app_config_file_name'] . '.xml')) {
            $this->getXmlFileLoader()->load(CONFIGS . $this->mainConfiguration['app_config_file_name'] . '.xml');
        } else if (file_exists(CONFIGS . $this->mainConfiguration['app_config_file_name'] . '.yml')) {
            $this->getYamlFileLoader()->load(CONFIGS . $this->mainConfiguration['app_config_file_name'] . '.yml');
        }
    }

    /**
     * @return void
     */
    private function readPluginsConfiguration()
    {
        $filename = CONFIGS . $this->mainConfiguration['plugin_config_file_name'] . '.yml';
        
        if (is_file($filename)) {
            $this->pluginsConfiguration = $this->getYaml()->parse($filename);
        }
    }

    /**
     * @return void
     */
    private function configurePlugins()
    {
        $this->readPluginsConfiguration();

        foreach ($this->pluginsConfiguration as $pluginName => $pluginOptions) {
            $extensionClass = '\\' . $pluginName . '\\DependencyInjection\\Extension';

            if (!empty($pluginOptions['options']['class_name'])) {
                $extensionClass = $pluginOptions['options']['class_name'];
            }

            if (!empty($pluginOptions['options']['class_path'])) {
                require_once($pluginOptions['options']['class_path']);
            }

            /**
             * @var \dependency_injection\DependencyInjection\CompilerPassDataInterface $extension
             */
            $extension = new $extensionClass();

            if ($extension instanceof CompilerPassDataInterface) {
                $compilerPassData = $extension->getCompilerPassData() ?: array();
                
                foreach ($compilerPassData as $object) {
                    $this->container->addCompilerPass($object);
                }
            }

            $this->container->registerExtension($extension);
            $this->container->loadFromExtension($pluginName, $pluginOptions['config'] ?: array());
        }
    }
}