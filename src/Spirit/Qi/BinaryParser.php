<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Qi\Domain;

abstract class BinaryParser extends NaryParser
{
    public function __construct(Domain $domain, $subject1, $subject2)
    {
        parent::__construct($domain, [$subject1, $subject2]);
    }
}
