<?php
namespace dependency_injection\cache;

/**
 * @author Masich Ivan <john@masich.com>
 */
class CakePHPCacheContainer implements CacheContainerInterface
{
    private $engine;

    private $containerKey;

    public function __construct($containerKey, $engine = 'default')
    {
        $this->engine = $engine;
        $this->containerKey = $containerKey;
    }

    function read()
    {
        if (\Configure::read('debug') > 0) {
            return false;
        }

        $key = $this->containerKey;
        $config = $this->engine;

        if (($container = \Cache::read($key, $config)) === false) {
            return false;
        }

        return unserialize($container);
    }

    function write($container)
    {
        if (\Configure::read('debug') > 0) {
            return false;
        }

        $key = $this->containerKey;
        $config = $this->engine;

        \Cache::write($key, serialize($container), $config);
    }
}
