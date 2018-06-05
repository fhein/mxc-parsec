<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Qi\Domain;

class UpperParser extends CharClassParser
{
    public function __construct(Domain $domain, string $uid, bool $negate = false)
    {
        parent::__construct($domain, $uid, 'upper', $negate);
    }
}
