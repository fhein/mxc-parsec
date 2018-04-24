<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PrimitiveParser;
use Mxc\Parsec\Qi\Domain;

class EpsParser extends PrimitiveParser
{
    protected $callable;

    public function __construct(Domain $domain, $callable = null)
    {
        $this->callable = $callable;
        parent::__construct($domain);
    }

    public function doParse($skipper)
    {
        return (is_callable($this->callable)) ? ($this->callable)() : true;
    }

    public function __debugInfo()
    {
        return array_merge_recursive(
            parent::__debugInfo(),
            [
                'callable' => $this->callable ?? 'n/a',
            ]
        );
    }
}
