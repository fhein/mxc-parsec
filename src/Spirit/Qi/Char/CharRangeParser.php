<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;

class CharRangeParser extends Char
{
    public function __construct(Domain $domain, string $uid, string $min, string $max, bool $negate = false)
    {
        parent::__construct($domain, $uid, $negate);

        $cc = $this->iterator;

        // @todo: CodePage support for min and max

        if (! ($cc->isvalid($min) && $cc->isValid($max))) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s: Invalid character range.',
                    $this->what()
                )
            );
        }

        if ($cc->ord($min) > $cc->ord($max)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s: Ordinal of min (%s) is bigger than ordinal of max (%s)',
                    $this->what(),
                    $min,
                    $max
                )
            );
        }

        $this->classifier = function (string $ch) use ($min, $max, $cc) {
            $ord = $cc->ord($ch);
            return (($ord >= $cc->ord($min)) && ($ord <= $cc->ord($max)));
        };
    }
}
