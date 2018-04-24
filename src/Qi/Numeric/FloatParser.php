<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\String\SymbolsParser;
use Mxc\Parsec\Qi\PreSkipper;

class FloatParser extends PreSkipper
{
    protected $char;

    public function __construct(Domain $domain)
    {
        parent::__construct($domain);
        $this->symbols = new SymbolsParser(
            $domain,
            ['NAN' => NAN, '-NAN' => -NAN, 'INF' => INF, '-INF' => INF, '+NAN' => NAN, '+INF' => INF]
        );
    }

    public function doParse($skipper)
    {
        $fraction = false;
        $attr = '';
        $this->iterator->try();

        if ($this->iterator->done($this->symbols->doParse($skipper))) {
            $this->attribute = $this->symbols->getAttribute();
            return true;
        }

        $c = $this->iterator->current();
        if ($c === '-' || ($c === '+')) {
            $attr .= $c;
        }

        // parse integer part
        while ($this->iterator->valid()) {
            $c = $this->iterator->current();
            if ($c >= '0' && $c <= '9') {
                $attr .= $c;
                $this->iterator->next();
            } else {
                break;
            }
        }

        // parse fractional part
        if ($c === '.') {
            $attr .= $c;
            $this->iterator->next();
            $fraction = true;
        }

        if ($attr === '') {
            return false;
        }

        // parse fractional part
        if ($fraction) {
            while ($this->iterator->valid()) {
                $c = $this->iterator->current();
                if ($c >= '0' && $c <= '9') {
                    $attr .= $c;
                    $this->iterator->next();
                } else {
                    break;
                }
            }
        }

        // parse exponent marker
        if ($c === 'e' || $c === 'E') {
            // parse exponent
            $attr .= 'e';
            $this->iterator->next();

            while ($this->iterator->valid()) {
                $c = $this->iterator->current();
                if ($c >= '0' && $c <= '9') {
                    $attr .= $c;
                    $this->iterator->next();
                } else {
                    break;
                }
            }
        }
        $this->attribute = floatval($attr);
        return true;
    }
}
