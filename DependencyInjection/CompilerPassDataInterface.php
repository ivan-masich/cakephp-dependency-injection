<?php

namespace dependency_injection\DependencyInjection;

/**
 * @author Masich Ivan <john@masich.com>
 */
interface CompilerPassDataInterface
{
    /**
     * @abstract
     * @param array $objects
     * @return void
     */
    function setCompilerPassData(array $objects);

    /**
     * @abstract
     * @return \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
     */
    function getCompilerPassData();
}
