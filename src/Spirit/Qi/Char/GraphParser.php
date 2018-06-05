<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Qi\Domain;

class GraphParser extends CharClassParser
{
    public function __construct(Domain $domain, string $uid, bool $negate = false)
    {
        parent::__construct($domain, $uid, 'graph', $negate);
    }
}
