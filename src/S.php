<?php

namespace Mxc\Parsec;

use Mxc\Dev\Parsec\ParserManagerInfo;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Operator\PlusOperator;
use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Service\ParserBuilder;

include __DIR__.'/../autoload.php';

$definitions = [
    'rule_1' => [ Rule::class, [
        'rule_1',
        [ PlusOperator::class, [
            [ DifferenceOperator::class, [
                [ CharParser::class, [ null, false ]],
                [ CharParser::class, [ '^', false, ]],
            ]],
        ]],
    ]],
];

$pm = new ParserManagerInfo();
$pm->updateInfoFiles();


$pb = new ParserBuilder($pm, $definitions);
$parser = $pb->get('rule_1');
var_dump($parser);
