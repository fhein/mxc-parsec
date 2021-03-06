<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;

class CharSetParser extends Char
{
    public function __construct(Domain $domain, $charset, bool $negate = false)
    {
        parent::__construct($domain, $negate);

        if (is_string($charset)) {
            $charset = $this->parseCharset($domain->getInternalIterator($charset));
        }

        $this->classifier = function (string $c) use ($charset) {
            return isset($charset[$c]);
        };
    }

    protected function parseCharset($iterator)
    {
        $cs = [];
        while ($iterator->valid()) {
            $c = $iterator->current();
            if ($c === null) {
                throw new InvalidArgumentException('Invalid character code in charset definition');
            }
            $iterator->next();
            if ($iterator->valid()) {
                $next = $iterator->current();
                if ($next === null) {
                    throw new InvalidArgumentException('Invalid character code in charset definition');
                }
                if ($next === '-') {
                    $iterator->next();
                    if (! $iterator->valid()) {
                        $cs['-'] = 1;
                        break;
                    }
                    $next = $iterator->current();
                    for ($i = $iterator->ord($c); $i <= $iterator->ord($next); $i++) {
                        $cs[$iterator->chr($i)] = 1;
                    }
                    $iterator->next();
                } else {
                    $cs[$c] = 1;
                }
            } else {
                $cs[$c] = 1;
            }
        }
        return $cs;
    }
}
