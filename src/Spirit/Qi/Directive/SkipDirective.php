<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\UnusedSkipper;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Parser;
use Mxc\Parsec\Qi\DelegatingParser;

class SkipDirective extends DelegatingParser
{
    public function __construct(Domain $domain, string $uid, $subject, $skipper = null)
    {
        parent::__construct($domain, $uid, $subject);

        $this->skipper = $skipper;
    }

    public function doParse($skipper)
    {
        if (is_string($this->skipper)) {
            $this->skipper = $this->domain->getParser($this->skipper);
        }
        if ($this->skipper instanceof Parser) {
            $skipper = $this->skipper;
        } elseif ($skipper instanceof UnusedSkipper) {
            $skipper = $skipper->getSkipper();
        }
        return $this->getSubject()->parse($skipper);
    }
}
