<?php

namespace dependency_injection\libs;

/**
 * Class loader
 *
 * This class based on Symfony2 component
 *
 * @author Masich Ivan <john@masich.com>
 */
class ClassLoader
{
    private $namespaces = array();

    /**
     * Registers an array of namespaces
     *
     * @param array $namespaces An array of namespaces (namespaces as keys and locations as values)
     */
    public function registerNamespaces(array $namespaces)
    {
        foreach ($namespaces as $namespace => $locations) {
            $this->namespaces[$namespace] = (array) $locations;
        }
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $class The name of the class
     */
    public function loadClass($class)
    {
        if ($file = $this->findFile($class)) {
            require $file;
        }
    }

    /**
     * Finds the path to the file where the class is defined.
     *
     * @param string $class The name of the class
     *
     * @return string|null The path, if found
     */
    public function findFile($class)
    {
        if ('\\' == $class[0]) {
            $class = substr($class, 1);
        }

        if (false !== $pos = strrpos($class, '\\')) {
            // namespaced class name
            $namespace = substr($class, 0, $pos);
            foreach ($this->namespaces as $ns => $dirs) {
                foreach ($dirs as $dir) {
                    if (0 === strpos($namespace, $ns)) {
                        $className = substr($class, $pos + 1);
                        $file = $dir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
                        if (file_exists($file)) {
                            return $file;
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Registers new instance as an autoloader.
     *
     * @param Boolean $prepend Whether to prepend the autoloader or not
     */
    public static function register($prepend = false)
    {
        $loader = new ClassLoader();
        $loader->registerNamespaces(
            array(
                'Symfony\\Component\\Config' => __DIR__ . DS . '..' . DS . 'vendors' . DS,
                'Symfony\\Component\\DependencyInjection' => __DIR__ . DS . '..' . DS . 'vendors' . DS,
                'Symfony\\Component\\Yaml' => __DIR__ . DS . '..' . DS . 'vendors' . DS,
                'dependency_injection' => __DIR__ . DS . '..' . DS . '..' . DS
            )
        );

        spl_autoload_register(array($loader, 'loadClass'), true, $prepend);
    }
}