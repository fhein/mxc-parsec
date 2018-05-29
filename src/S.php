<?php

use Mxc\Blockly\BlocklyGenerator;
use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Dev\Parsec\ParserManagerInfo;

include __DIR__.'/../autoload.php';

$definitions = [
    'rule_1' => [ 'rule', [
        'rule_1',
        [ 'plus', [
            [ 'difference', [
                [ 'char', [ null, false ]],
                [ 'char', [ '^', false, ]],
            ]],
        ]],
    ]],
];

$json = <<<'EOT'
{
    "alpha_type":{"name":"alpha","generator":"no_args_generator"},
    "alnum_type":{"name":"alnum","generator":"no_args_generator"},
    "digit_type":{"name":"digit","generator":"no_args_generator"},
    "xdigit_type":{"name":"xdigit","generator":"no_args_generator"},
    "upper_type":{"name":"upper","generator":"no_args_generator"},
    "lower_type":{"name":"lower","generator":"no_args_generator"},
    "graph_type":{"name":"graph","generator":"no_args_generator"},
    "print_type":{"name":"print","generator":"no_args_generator"},
    "punct_type":{"name":"punct","generator":"no_args_generator"},
    "cntrl_type":{"name":"cntrl","generator":"no_args_generator"},
    "space_type":{"name":"space","generator":"no_args_generator"},
    "blank_type":{"name":"blank","generator":"no_args_generator"},
    "distinct_type":{"name":"distinct","generator":"args_parser_parser_generator"},
    "difference_type":{"name":"difference","generator":"args_parser_parser_generator"},
    "list_type":{"name":"list","generator":"args_parser_parser_generator"},
    "plus_type":{"name":"plus","generator":"args_parser_generator"},
    "optional_type":{"name":"optional","generator":"args_parser_generator"},
    "not_type":{"name":"not","generator":"args_parser_generator"},
    "and_type":{"name":"and","generator":"args_parser_generator"},
    "as_string_type":{"name":"as_string","generator":"args_parser_generator"},
    "skip_type":{"name":"skip","generator":"args_parser_generator"},
    "no_skip_type":{"name":"no_skip","generator":"args_parser_generator"},
    "no_case_type":{"name":"no_case","generator":"args_parser_generator"},
    "raw_type":{"name":"raw","generator":"args_parser_generator"},
    "matches_type":{"name":"matches","generator":"args_parser_generator"},
    "lexeme_type":{"name":"lexeme","generator":"args_parser_generator"},
    "hold_type":{"name":"hold","generator":"args_parser_generator"},
    "expect_d_type":{"name":"expect_d","generator":"args_parser_generator"},
    "omit_type":{"name":"omit","generator":"args_parser_generator"},
    "permutation_type":{"name":"permutation","generator":"args_parserarray_generator"},
    "alternative_type":{"name":"alternative","generator":"args_parserarray_generator"},
    "sequence_type":{"name":"sequence","generator":"args_parserarray_generator"},
    "sequencial_or_type":{"name":"sequence_or","generator":"args_parserarray_generator"}
}
EOT;

$block = '{"type": "block_type","message0": "block","colour": 230,"tooltip": "%{BKY_TOOLTIP","helpUrl": "%{BKY_HELPURL"}';
$b = json_decode($block, JSON_OBJECT_AS_ARRAY);
$j = json_decode($json, JSON_OBJECT_AS_ARRAY);

$pm = new ParserManagerInfo();
$a = $pm->getParsers();

$tooltip = "%{BKY_TOOLTIP_";
$helpurl = "%{BKY_HELPURL_";

foreach ($a as $parser) {
    $type = $parser.'_type';
    if (! $j[$type]) {
        $b['type'] = $type;
        $b['message0'] = $type;
        $b['tooltip'] = $tooltip.strtoupper($type)."}";
        $b['helpUrl'] = $helpurl.strtoupper($type)."}";
        $x[] = $b;
    }
}

$out = "{\n";
foreach ($x as $record) {
    $out .= '    '.json_encode($record).",\n";
}
$out .= "}";
print $out;
die();
//print json_encode($j);

$out = "{\n";
foreach ($j as $t => $e) {
    $out .= '    '.$t.':'.json_encode($e).",\n";
}
$out .= "}";
print($out);


//$sqp = $pm->build(SpiritQiParser::class, [ $definitions, 'ABCDE^']);
//var_dump($sqp);

// $pb = new ParserBuilder($pm, h$definitions);
// $parser = $pb->get('rule_1');
// var_dump($parser);
