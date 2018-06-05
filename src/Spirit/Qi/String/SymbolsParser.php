<?php

namespace Mxc\Parsec\Qi\String;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\PreSkipper;

class SymbolsParser extends PreSkipper
{

    protected $symbols;
    protected $map = [];

    public function __construct(Domain $domain, string $uid, array $symbols = [])
    {
        parent::__construct($domain, $uid);
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
        return $this;
    }

    public function doParse($skipper)
    {
        $m = $this->map;
        $symbol = '';
        $result = false;
        while ($this->iterator->valid()) {
            $c = $this->iterator->currentNoCase();
            if (! isset($m[$c])) {
                break;
            }
            $m = $m[$c];
            $symbol .= $c;
            $this->iterator->next();
            if (isset($m['accept'])) {
                $result = true;
                $attr = $symbol;
                $this->iterator->accept();
                $this->iterator->try();
            }
        };
        if ($result === true) {
            $this->iterator->reject();
            $this->iterator->try();
            $this->attribute = $this->symbols[$attr];
        }
        return $result;
    }
}
