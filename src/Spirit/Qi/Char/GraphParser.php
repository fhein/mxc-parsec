<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Qi\Domain;

class GraphParser extends CharClassParser
{
    public function __construct(Domain $domain, bool $negate = false)
    {
        parent::__construct($domain, 'graph', $negate);
    }
}