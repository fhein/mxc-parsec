<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Domain;

abstract class PreSkipper extends PrimitiveParser
{

    public function __construct(Domain $domain)
    {
        parent::__construct($domain);
        $this->skip = true;
    }
}
