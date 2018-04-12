<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Qi\PreSkipper;
use Mxc\Parsec\Domain;

class Char extends PreSkipper
{
    protected $classifier;
    protected $negate;

    public function __construct(Domain $domain, bool $negate = false, $noCase = false)
    {
        $this->negate = $negate;
        $this->defaultType = 'string';

        parent::__construct($domain);
    }

    public function doParse($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        $c = $iterator->parseChar();
        if ($c !== false) {
            if ((! $this->negate && ($this->classifier)($c))
                || ($this->negate && ! ($this->classifier)($c))) {
                return $this->validateChar($expectedValue, $c, $attributeType);
            }
        }
        return false;
    }

    public function setNegate($negate = true)
    {
        $this->negate = $negate;
    }
}
