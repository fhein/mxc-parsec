<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\PreSkipper;

class CharParser extends PreSkipper
{
    public function __construct(Domain $domain, bool $negate = false)
    {
        parent::__construct($domain, $negate);

        $this->classifier = function (string $c) {
            return true;
        };
    }
}
