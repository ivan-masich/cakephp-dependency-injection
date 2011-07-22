<?php

use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use \dependency_injection\libs\Container;

/**
 * @author Masich Ivan
 */
class ContainerBehavior extends ModelBehavior
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerAwareInterface $model
     * @param array $settings
     * @return void
     */
    public function setup(ContainerAwareInterface $model, $settings)
    {
        $model->setContainer(Container::getInstance());
    }
}
