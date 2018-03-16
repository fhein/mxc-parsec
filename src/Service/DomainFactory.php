<?php

namespace Mxc\Parsec\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\UnusedAttribute;

class DomainFactory implements FactoryInterface
{
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $options ?? [$container, 'UTF-8', 'UTF-8'];
        return new Domain(...$options);
    }
}
