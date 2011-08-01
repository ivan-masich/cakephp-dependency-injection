<?php

use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use \dependency_injection\libs\Container as ContainerLib;

/**
 * @author Masich Ivan <john@masich.com>
 */
class ContainerComponent extends \Object
{
    /**
     * Initialize component method
     *
     * @param Symfony\Component\DependencyInjection\ContainerAwareInterface  $controller
     * @param array $settings
     * @return void
     */
    public function initialize(ContainerAwareInterface $controller, $settings = array())
    {
        $controller->setContainer(ContainerLib::getInstance());
    }
}
