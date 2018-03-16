<?php

namespace Mxc\Parsec\Qi\Char;

use Mxc\Parsec\Domain;

class CharParser extends Char
{

    public function __construct(Domain $domain, string $c = null, bool $negate = false)
    {
        $cc = $domain->getCharacterClassifier();
        $this->classifier = ($c === null)
            ? function (string $c) use ($negate) {
                return ! $negate;
            }
            : function (string $ch) use ($c, $cc, $negate) {
                $res = ($cc->ord($ch) === ($cc->ord($c)));
                return $negate ? ! $res : $res;
            };
    }
}
