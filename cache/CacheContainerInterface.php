<?php

namespace dependency_injection\cache;

/**
 * @author Masich Ivan <john@masich.com>
 */
interface CacheContainerInterface
{
    function read();
    function write($container);
}