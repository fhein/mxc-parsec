<?php

namespace Mxc\Parsec;

use Mxc\Parsec\Service\ParserManager;

include __DIR__.'/../autoload.php';

$pm = new ParserManager();
var_export($pm->getParsersByClass());
print("\n\n\n");
var_export($pm->getParsersByCategory());
