<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Qi\Domain;

class CharParser extends Char
{
    public function __construct(Domain $domain, string $c = null, bool $negate = false)
    {
        parent::__construct($domain, $negate);
        if ($c !== null) {
            $this->classifier = function (string $ch) use ($c) {
                return $ch === $c;
            };
        } else {
            $this->classifier = function (string $ch) {
                return true;
            };
        }
    }
}
