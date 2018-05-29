<?php

namespace Mxc\Parsec\Service;

use Interop\Container\ContainerInterface;
use Mxc\Parsec\Qi\Domain;
use Zend\ServiceManager\Factory\FactoryInterface;

class DomainFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $options ?? [$container, 'UTF-8', 'UTF-8'];
        return new Domain(...$options);
    }
}
