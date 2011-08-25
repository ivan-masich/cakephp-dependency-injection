Extension Class in plugin
=========================
Extension class must use namespaces, must have name "Extension.php" and it must located in DependencyInjection folder of your plugin.

####For automatic load services from plugin you must create in it Extension class, for example:

    <?php
    // app/plugins/example_plugin/DependencyInjection/Extension.php

    namespace example_plugin\DependencyInjection;

    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\DependencyInjection\Loader;
    use Symfony\Component\Config\FileLocator;

    class Extension extends BaseExtension
    {
        public function load(array $configs, ContainerBuilder $container)
        {
            $configuration = new Configuration();
            $config = $this->processConfiguration($configuration, $configs);

            $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../config'));
            $loader->load('services.xml');
        }
    }

####Configuration class describe plugin configuration structure, fills default values, validate etc., for example:

    <?php
    // app/plugins/example_plugin/DependencyInjection/Configuration.php

    namespace example_plugin\DependencyInjection;

    use Symfony\Component\Config\Definition\Builder\TreeBuilder;
    use Symfony\Component\Config\Definition\ConfigurationInterface;
    use \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

    class Configuration implements ConfigurationInterface
    {
        public function getConfigTreeBuilder()
        {
            $treeBuilder = new TreeBuilder();

            // to root method you must push plugin name
            $rootNode = $treeBuilder->root('example_plugin');

            $rootNode
                ->children()
                    ->scalarNode('model_class')->defaultValue('Vz\CMS\Module\ContactUsBundle\Entity\ContactUs')->end()
                    ->scalarNode('additional_fields_class')->defaultValue('Vz\CMS\Module\ContactUsBundle\Entity\ContactUsAdditionalFields')->end()
                ->end();

            return $treeBuilder;
        }
    }

More examples of Extension and Configuration classes you can find in [Symfony project](https://github.com/symfony/symfony). [Configuration example](https://github.com/symfony/symfony/blob/master/src/Symfony/Bundle/FrameworkBundle/DependencyInjection/Configuration.php) and [Extension example](https://github.com/symfony/symfony/blob/master/src/Symfony/Bundle/FrameworkBundle/DependencyInjection/FrameworkExtension.php).