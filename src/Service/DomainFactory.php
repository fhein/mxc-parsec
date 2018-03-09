<?php

namespace Mxc\Parsec\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Mxc\Parsec\Domain;

class DomainFactory implements FactoryInterface
{
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $options ?? [$container->get('UTF-8'), $container->get('UTF-8')];
        return new Domain(...$options);
    }
}
