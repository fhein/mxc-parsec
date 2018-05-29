<?php

namespace Mxc\Parsec\Exception;

use Interop\Container\Exception\NotFoundException;

class ServiceNotFoundException extends \InvalidArgumentException implements
    NotFoundException
{
}
