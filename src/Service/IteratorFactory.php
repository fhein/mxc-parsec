<?php

namespace Mxc\Parsec\Service;

use Mxc\Parsec\Encoding\Utf8Decoder;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class IteratorFactory implements AbstractFactoryInterface
{
    const ITERATORS =
    [
        'UTF-8' => Utf8Decoder::class,
    ];

    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Factory\AbstractFactoryInterface::canCreate()
     */
    public function canCreate(\Interop\Container\ContainerInterface $container, $requestedName)
    {
        return isset(self::ITERATORS[$requestedName]);
    }

    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Factory\FactoryInterface::__invoke()
     */
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $iterator = self::ITERATORS[$requestedName];
        return $options ? new $iterator(...$options) : new $iterator();
    }
}
