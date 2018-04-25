<?php

namespace Mxc\Parsec\Service;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;

class ComplexParserFactory implements AbstractFactoryInterface
{
    protected $parserDefinitions;
    protected $parserManager;

    protected function getParserDefinitions(ContainerInterface $container)
    {
        if ($this->parserDefinitions === null) {
            $this->parserDefinitions = $container->get('parser_definitions');
        }
        return $this->parserDefinitions;
    }

    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Factory\AbstractFactoryInterface::canCreate()
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return null !== $this->getParserDefinitions($container)[$requestedName];
    }

    protected function prepareOptions(array $options)
    {
        foreach ($options as $key => $option) {
            if (is_array($option)) {
                $options[$key] = $this->getParser($option) ?? $this->prepareOptions($option);
            }
        }
        return $options;
    }

    protected function getParser(array $definition)
    {
        if (is_string($definition[0]) && $this->parserManager->has($definition[0])) {
            switch (count($definition)) {
                case 1:
                    return $this->parserManager->build($definition[0]);
                case 2:
                    if (is_array($definition[1])) {
                        return $this->parserManager->build($definition[0], $this->prepareOptions($definition[1]));
                    }
                    // intentional fall through
                default:
                    return null;
            }
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Factory\FactoryInterface::__invoke()
     */
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $parserDefinition = $this->getParserDefinitions($container)[$requestedName];
        $this->parserManager = $container->get('parser_manager');
        if (is_array($parserDefinition)) {
            $parser = $this->getParser($parserDefinition);
        }
        if ($parser === null) {
            throw new \Exception('Invalid parser definition record.');
        }
        return $parser;
    }
}
