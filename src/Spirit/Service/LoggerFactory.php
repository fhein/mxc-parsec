<?php

namespace Mxc\Parsec\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class LoggerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $logger = new Logger();
        unlink(__DIR__ . '/../../../config/log.txt');
        $logger->addWriter(new Stream(__DIR__ . '/../../../config/log.txt'));
        return $logger;
    }
}
