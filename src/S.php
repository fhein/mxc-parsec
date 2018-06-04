<?php

use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Service\ParserBuilder;

include __DIR__.'/../autoload.php';

$definitions = [
    'rule_1' => [ 'rule', [
        'rule_1',
        [ 'kleene_plus', [
            [ 'difference', [
                [ 'char', [ null, false ]],
                [ 'char', [ '^', false, ]],
            ]],
        ]],
    ]],
];

$x = IntlChar::chr(12);

 $pm = new ParserManager();
 $pb = $pm->get('parser_builder');
 $pb->setDefinitions($definitions);
 $parser = $pb->getRule('rule_1');
 $parser->setSource('aaaa');
 $parser->parse($pm->get('space'));
 var_dump($parser);
