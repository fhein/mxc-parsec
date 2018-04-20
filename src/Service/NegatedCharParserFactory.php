<?php

namespace Mxc\Parsec\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Mxc\Parsec\Qi\Domain;

class NegatedCharParserFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options[] = true;
        $requestedName = substr($requestedName, 1);
        return $options ? new $requestedName($container->get(Domain::class), ...$options)
            : new $requestedName($container->get(Domain::class));
    }
}
