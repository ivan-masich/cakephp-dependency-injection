Dependency Injection in CakePHP
===============================
This plugin based on Symfony2 component [DependencyInjection](https://github.com/symfony/DependencyInjection).
More about DI you may find in this articles: [Martin Fowler "Inversion of Control Containers and the Dependency Injection pattern"](http://martinfowler.com/articles/injection.html) and [Fabien Potencier "What is Dependency Injection?"](http://fabien.potencier.org/article/11/what-is-dependency-injection)


Requirements:
-------------
1. **PHP** version **5.3.2** or greater
2. **CakePHP** version **1.3.x**
3. Plugin folder name should be **dependency_injection**


Installation:
-------------

Clone from git:

    cd app/plugins
    git clone git://github.com/mind-blowing/cakephp-dependency-injection.git dependency_injection

    cd dependency_injection
    git submodule init
    git submodule update



Init class loader:
------------------
For this plugin you need use class loader, for make your life easier I create class loader for this plugin.
For init plugin class loader you need add this code to **app/config/bootstrap.php**:

    App::import('libs', 'DependencyInjection.ClassLoader', array('file' => 'ClassLoader.php'));
    
    \dependency_injection\libs\ClassLoader::register();


Configuration:
--------------
You may configure this plugin. Configuration params need be placed at app/config folder in di.yml or di.ini file, depending on what file format you want to chose.

Available params:

**cache_config_name**: string default(**default**) - This parameter for set what cache config will be used to cache.<br />
**cache_container_key**: string default(**diPluginContainer**) - This parameter for set what cache key will be used to cache container.<br />
**use_cache**: boolean default(**false**) - This parameter for set cache use.<br />
**app_config_file_name**: string default(**di_services**) - This parameter for set what file name will be used for application service configuration.<br />
**plugin_config_file_name**: string default(**di_plugin_config**) - This parameter for set what file name will be used for store plugins configurations.<br />

####Example yaml:

    # app/config/di.yml

    use_cache: true
    app_config_file_name: services


####Example ini:

    # app/config/di.ini

    use_cache = true
    cache_config_name = memcacheStorage


Cache:
------
For best speed result I recommend use cache, but by default cache is disabled in this plugin. To enable cache you need set **use_cache** param to **true** in configuration. More information in previous section.


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


More documentation:
-------------------

More documentation you can find in **doc** folder or here [Documentation](https://github.com/mind-blowing/cakephp-dependency-injection/blob/develop/doc/home.md).