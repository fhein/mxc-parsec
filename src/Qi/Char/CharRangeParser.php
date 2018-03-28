<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;

class CharRangeParser extends Char
{
    public function __construct(Domain $domain, string $min, string $max, bool $negate = false)
    {
        parent::__construct($domain);

        $cc = $domain->getCharacterClassifier();

        if (! ($cc->isvalid($min) && $cc->isValid($max))) {
            throw new InvalidArgumentException('Invalid character range.');
        }

        if ($cc->ord($min) > $cc->ord($max)) {
            $x = $min;
            $min = $max;
            $max = $x;
        }

        $this->classifier = ($min === $max) ?
        function (string $ch) use ($min, $cc) {
            return ($cc->ord($ch) === ($cc->ord($min)));
        } :
        function (string $ch) use ($min, $max, $cc, $negate) {
            $ord = $cc->ord($ch);
            return (($ord >= $cc->ord($min)) && ($ord <= $cc->ord($max)));
        };
    }
}
