<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Qi\PreSkipper;
use Mxc\Parsec\Domain;

class Char extends PreSkipper
{
    protected $classifier;
    protected $negate;

    public function __construct(Domain $domain, bool $negate = false)
    {
        $this->negate = $negate;
        $this->defaultType = 'string';
        parent::__construct($domain);
    }

    public function doParse($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {
        if (! $iterator->valid()) {
            return false;
        }
        $c = $iterator->current();
        if (($this->classifier)($c)) {
            if (! $this->negate && ($expectedValue === null  || $c === $expectedValue)) {
                $this->assignTo($c, $attributeType);
                $iterator->next();
                return true;
            }
        } else {
            if ($this->negate && ($expectedValue === null || $expectedValue === $c)) {
                $this->assignTo($c, $attributeType);
                $iterator->next();
                return true;
            }
        }
        return false;
    }

    public function setNegate($negate = true)
    {
        $this->negate = $negate;
    }
}
