<?php

namespace Mxc\Parsec;

use Mxc\Parsec\Service\ParserManager;

include __DIR__.'/../autoload.php';

$pm = var_dump(new ParserManager());
$sp = $pm->findShareableParsers();
