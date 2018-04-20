<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Qi\Domain;

class BlankParser extends CharClassParser
{
    public function __construct(Domain $domain, bool $negate = false)
    {
        parent::__construct($domain, 'blank', $negate);
    }
}
