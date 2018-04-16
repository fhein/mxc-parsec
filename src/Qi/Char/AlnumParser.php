<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Domain;

class AlnumParser extends CharClassParser
{
    public function __construct(Domain $domain, bool $negate = false)
    {
        parent::__construct($domain, 'alnum', $negate);
    }
}
