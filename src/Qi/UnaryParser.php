<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Domain;

abstract class UnaryParser extends PrimitiveParser
{
    protected $subject;

    public function __construct(Domain $domain, Parser $subject)
    {
        parent::__construct($domain);
        $this->subject = $subject;
    }
}
