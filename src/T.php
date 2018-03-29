<?php

namespace Mxc\Parsec;

use IntlChar;
use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Encoding\CharacterClassifier;

include __DIR__.'/../autoload.php';

$pm = new ParserManager();

$cp = $pm->get(CharacterClassifier::class);

$charClasses =
[
    'alnum',
    'alpha',
    'digit',
    'xdigit',
    'cntrl',
    'graph',
    'lower',
    'upper',
    'print',
    'punct',
    'space',
    'blank',
];

for ($i = 0; $i < 256; $i++) {
    if ($cp->isvalid($i)) {
        foreach ($charClasses as $cc) {
            $method = 'is'.$cc;
            if (IntlChar::$method($i)) {
                $$cc[] = $i;
            }
        }
    }
}
var_export($space);
