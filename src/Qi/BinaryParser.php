<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Domain;

abstract class BinaryParser extends NaryParser
{
    public function __construct(Domain $domain, Parser $subject1, Parser $subject2)
    {
        parent::__construct($domain, [$subject1, $subject2]);
    }
}
