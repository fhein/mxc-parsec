<?php

namespace Mxc\Parsec\Qi\String;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\PreSkipper;

class SymbolsParser extends PreSkipper
{

    protected $symbols;
    protected $map = [];

    public function __construct(Domain $domain, array $symbols = [])
    {
        parent::__construct($domain);
        $this->symbols = $symbols;
        foreach ($symbols as $symbol => $value) {
            $this->add($symbol, $value);
        }
    }

    public function add($symbol, $value)
    {
        $this->symbols[$symbol] = $value;

        $iterator = $this->domain->getInternalIterator($symbol);
        $m = &$this->map;

        while ($iterator->valid()) {
            $c = $iterator->current();
            if (! isset($m[$c])) {
                $m[$c] = [];
            }
            $m = &$m[$c];
            $iterator->next();
        }
        $m['accept'] = true;
    }

    // return longest match
    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $m = $this->map;
        $symbol = '';
        while ($iterator->valid()) {
            $c = $iterator->currentNoCase();
            if (! isset($m[$c])) {
                break;
            }
            $m = $m[$c];
            $symbol .= $c;
            $iterator->next();
        };
        if (isset($m['accept']) && $this->validate($expectedValue, $this->symbols[$symbol], $attributeType)) {
            return true;
        }
        return false;
    }
}
