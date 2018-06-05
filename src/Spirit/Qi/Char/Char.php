<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Qi\PreSkipper;
use Mxc\Parsec\Qi\Domain;

class Char extends PreSkipper
{
    protected $classifier;
    protected $negate;

    public function __construct(Domain $domain, string $uid, bool $negate = false)
    {
        $this->negate = $negate;
        parent::__construct($domain, $uid);
    }

    public function doParse($skipper = null)
    {
        $c = $this->iterator->parseChar();
        if ($c !== false) {
            if ($this->negate xor ($this->classifier)($this->iterator->getNoCaseComparableCharacter($c))) {
                $this->attribute .= $c;
                $this->iterator->next();
                return true;
            }
        }
        return false;
    }
}
