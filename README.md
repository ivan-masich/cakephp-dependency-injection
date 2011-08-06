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
**Xml** application service configuration you need place to **app/configs/di_services.xml**, example:

    <?xml version="1.0" encoding="UTF-8" ?>

    <container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

        <services>

            <service id="service_name" class="ClassName">
                <argument>example</argument>
            </service>

        </services>

    </container>
More xml examples you can find here [Examples \*.xml (wiki)](https://github.com/mind-blowing/cakephp-dependency-injection/wiki/Examples-*.xml).


**Yaml** application service configuration you need place to **app/configs/di_services.yml**, example:

    services:
    service_name:
        class:        ClassName
        arguments:    [example]
More yaml examples you can find here [Examples \*.xml (wiki)](https://github.com/mind-blowing/cakephp-dependency-injection/wiki/Examples-*.yml).