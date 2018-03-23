<?php

namespace Mxc\Parsec;

use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Qi\Numeric\IntParser;
use Mxc\Parsec\Qi\Char\CharClassParser;

include __DIR__.'/../autoload.php';

$pm = new ParserManager();
$parser = $pm->build(IntParser::class);
$iterator = $parser->setSource('123');
$skipper = $pm->build(CharClassParser::class, [ 'space' ]);

$result = $parser->parse($iterator, 123, 'array', $skipper);

print($result ? "success" : "failure");
