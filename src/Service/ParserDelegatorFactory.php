<?php

namespace Mxc\Parsec\Service;

use Zend\ServiceManager\Factory\DelegatorFactoryInterface;
use Interop\Container\ContainerInterface;

class ParserDelegatorFactory implements DelegatorFactoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Factory\DelegatorFactoryInterface::__invoke()
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        print($name."\n");
        $parser = $options === null ? $callback() : $callback($options);
        return new ParserDelegator($parser, $options);
    }
}
