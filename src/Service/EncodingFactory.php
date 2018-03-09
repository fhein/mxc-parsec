<?php

namespace Mxc\Parsec\Service;

use Mxc\Parsec\Encoding\CharacterClassifier;
use Mxc\Parsec\Encoding\Encoding;
use Mxc\Parsec\Encoding\Utf8Decoder;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class EncodingFactory implements AbstractFactoryInterface
{
    const CLASSIFIERS =
    [
        'UTF-8' => CharacterClassifier::class,
    ];

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
        $classifier = self::CLASSIFIERS[$requestedName];

        return new Encoding(new $classifier, new $iterator);
    }
}
