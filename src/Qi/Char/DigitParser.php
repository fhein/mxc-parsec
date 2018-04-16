<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Domain;

class DigitParser extends CharClassParser
{
    public function __construct(Domain $domain, bool $negate = false)
    {
        parent::__construct($domain, 'digit', $negate);
    }
}
