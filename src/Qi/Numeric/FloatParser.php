<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Qi\Numeric\Detail\DecimalIntPolicy;
use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Qi\Directive\NoCaseDirective;
use Mxc\Parsec\Qi\String\SymbolsParser;

class FloatParser extends Integer
{
    protected $char;

    public function __construct(Domain $domain)
    {
        parent::__construct($domain, new DecimalIntPolicy());
        $this->char = new NoCaseDirective($domain, new CharParser($domain));
        $this->symbols = new SymbolsParser(
            $domain,
            ['NAN' => NAN, '-NAN' => -NAN, 'INF' => INF, '-INF' => INF, '+NAN' => NAN, '+INF' => INF]
        );
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $fraction = false;
        $attr = '';
        $iterator->try();
        if ($iterator->done($this->symbols->doParse($iterator, null, null, null))) {
            $this->attribute = $this->symbols->getAttribute();
            return true;
        }

        // parse integer part
        if (parent::doParse($iterator, null, 'string', $skipper)) {
            $attr .= $this->getAttribute();
        }
        if ($this->char->doParse($iterator, '.', null, null)) {
            $attr .= '.';
            $fraction = true;
        }
        if ($attr === '') {
            return false;
        }

        // parse fractional part
        if ($fraction && parent::doParse($iterator, null, 'string', null)) {
            $attr .= $this->getAttribute();
        }

        // parse exponent marker
        if ($this->char->doParse($iterator, 'e', null, null)) {
            // parse exponent
            $attr .= 'e';
            if (! parent::doParse($iterator, null, 'string', null)) {
                return false;
            }
            $attr .= strval($this->getAttribute());
        }
        $this->assignTo($attr, 'double');
        return true;
    }
}
