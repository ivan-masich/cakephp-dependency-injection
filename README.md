Dependency Injection in CakePHP
===============================
This plugin based on Symfony2 component [DependencyInjection](https://github.com/symfony/DependencyInjection).
More about DI you may find in this articles: [Martin Fowler "Inversion of Control Containers and the Dependency Injection pattern"](http://martinfowler.com/articles/injection.html) and [Fabien Potencier "What is Dependency Injection?"](http://fabien.potencier.org/article/11/what-is-dependency-injection)


Requirements:
-------------
1. **PHP** version **5.3.2** or greater
2. **CakePHP** version **1.3.x**
3. Plugin folder name should be **dependency_injection**


Init class loader:
------------------
For this plugin you need use class loader, for make your life easier I create class loader for this plugin.
For init plugin class loader you need add this code to **app/config/bootstrap.php**:

    App::import('libs', 'DependencyInjection.ClassLoader', array('file' => 'ClassLoader.php'));
    
    \dependency_injection\libs\ClassLoader::register();


Get container in controller:
----------------------------
For use DI in controller you need inject DI container. You need implement **ContainerAwareInterface** in class and add component **DependencyInjection.Container**, example:

    use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
    use \Symfony\Component\DependencyInjection\ContainerInterface;

    class ExampleController extends AppController implements ContainerAwareInterface
    {
        public $components = array('DependencyInjection.Container');

        private $container;

        public function setContainer(ContainerInterface $container = null)
        {
            $this->container = $container;
        }


Get container in model:
-----------------------
For use DI in model you need inject DI container. You need implement **ContainerAwareInterface** in class and add behavior **DependencyInjection.Container**, example:

    use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
    use \Symfony\Component\DependencyInjection\ContainerInterface;

    class Example extends AppModel implements ContainerAwareInterface
    {
        public $actsAs = array('DependencyInjection.Container');

        private $container;

        public function setContainer(ContainerInterface $container = null)
        {
            $this->container = $container;
        }


Application configuration:
-------------------------
**Xml** application service configuration you need place to **app/config/di_services.xml**, example:

    <services>
        <service id="service_name" class="ClassName">
            <argument>example</argument>
        </service>
    </services>
More xml examples you can find here [Container configuration examples](https://github.com/mind-blowing/cakephp-dependency-injection/blob/develop/doc/container_configuration_examples.md).


**Yaml** application service configuration you need place to **app/config/di_services.yml**, example:

    services:
        service_name:
            class:        ClassName
            arguments:    [example]
More yaml examples you can find here [Container configuration examples](https://github.com/mind-blowing/cakephp-dependency-injection/blob/develop/doc/container_configuration_examples.md).


Plugins configuration:
----------------------

DI plugin may load plugin services automaticly and you may configurate plugin services without change it source code, this process can be divided into several steps:

**Create Extension class in your plugin:**

    <?php

    // app/plugins/example_plugin/DependencyInjection/Extension.php

    namespace example_plugin\DependencyInjection;

    use \dependency_injection\DependencyInjection\BaseExtension;
    use \Symfony\Component\DependencyInjection\ContainerBuilder;

    class Extension extends BaseExtension
    {
        function load(array $config, ContainerBuilder $container)
        {
            // TODO: Implement load() method.
        }

        function getAlias()
        {
            return 'example_plugin';
        }
    }
More info about how to create Extension class in plugin you can find here [Extension class in plugin](https://github.com/mind-blowing/cakephp-dependency-injection/blob/develop/doc/extension_class_in_plugin.md).

**Configure plugins in application:** allow only in yaml format, for this you need put all in **app/config/di_plugin_config.yml**:

In this example you will see how set  custom  Extension class name and set path from where it will be included(with require_once function),
by default class will loaded using your autoload system, and full class name will be generated with namespace by this rule: **\\$pluginName\\DependencyInjection\\Extension**

    example_plugin:
        options:
            class_name: ExtensionClassName
            class_path: "<?php echo APP . 'plugins' . DS . 'example_plugin' . DS . 'DependencyInjection' . DS . 'ExtensionClassName.php'; ?>"
        config:
            param: example
            param_collection:
                - one
                - two

Example without any settings (will be used \\example_plugin\\DependencyInjection\\Extension class):

    example_plugin:
        config: ~


More documentation:
-------------------

More documentation you can find in **doc** folder or here [Documentation](https://github.com/mind-blowing/cakephp-dependency-injection/blob/develop/doc/home.md).