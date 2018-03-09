<?php

namespace Mxc\Parsec\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Mxc\Parsec\Domain;
use Zend\ServiceManager\ServiceManager;

class DomainFactory implements FactoryInterface
{
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $encodings = include __DIR__ . '/../../config/config.php';
        $encodings = new ServiceManager($encodings['encodings']);
        $options = $options ?: ['internalEncoding' => 'UTF-8', 'inputEncoding' => 'UTF-8'];
        return new Domain($encodings, $options);
    }
}
