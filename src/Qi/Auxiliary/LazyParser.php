<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Domain;

class LazyParser extends Parser
{

    protected $class;
    protected $subject;

    public function __construct(Domain $domain, string $class, ...$args)
    {
        $this->domain = $domain;
        $this->args = $args;
        $this->class = $class;
    }

    public function parseImpl($iterator, $expectedValue, $attributeType, $skipper)
    {
        return $this->getSubject()->parseImpl($iterator, $expectedValue, $attributeType. $skipper);
    }

    protected function getSubject()
    {
        if ($this->subject === null) {
            $this->subject = new $this->class($this->domain, $this->args);
        }
        return $this->subject;
    }
}
