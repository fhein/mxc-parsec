<?php

namespace Mxc\Parsec;

use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Operator\PlusOperator;
use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Parser\SpiritQiParser;
use Mxc\Parsec\Service\ParserManager;

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

$pm = new ParserManager();
$sqp = $pm->build(SpiritQiParser::class, [ null, 'ABCDE^']);
//var_dump($sqp);

// $pb = new ParserBuilder($pm, h$definitions);
// $parser = $pb->get('rule_1');
// var_dump($parser);
