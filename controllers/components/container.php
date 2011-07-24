<?php

use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use \dependency_injection\libs\Container as ContainerLib;

/**
 * @author Masich Ivan <john@masich.com>
 */
class ContainerComponent extends \Object
{
    /**
     * Startup component method
     *
     * @param Controller $controller
     * @return void
     */
    public function startup(ContainerAwareInterface $controller)
    {
        $controller->setContainer(ContainerLib::getInstance());
    }
}
