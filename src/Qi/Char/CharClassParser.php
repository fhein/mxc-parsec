<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;

class CharClassParser extends Char
{
    public function __construct(Domain $domain, string $class)
    {
        $classifier = $domain->getCharacterClassifier();
        $method = 'is' . $class;
        if (! method_exists($classifier, $method)) {
            throw new InvalidArgumentException(sprintf('Invalid character class: %s', $class));
        }
        $this->classifier = [$classifier, $method];
    }
}
