<?php

namespace Mxc\Parsec\Service;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;

class JsonParserFactory implements AbstractFactoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Factory\AbstractFactoryInterface::canCreate()
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $this->request = json_decode($requestedName, JSON_OBJECT_AS_ARRAY);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Factory\FactoryInterface::__invoke()
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $parser = $container->get('parser_manager')->build(...$this->request);
        return $parser;
    }
}
