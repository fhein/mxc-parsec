<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Qi\NonTerminal\Rule;
use Mxc\Parsec\Qi\Operator\PlusOperator;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Char\CharParser;

// [ <parser_class>, <parser_parameter_array> ]
return [
    'rule_1' => [
        Rule::class,
        [
            'rule_1',
            [
                PlusOperator::class,
                [ DifferenceOperator::class,
                    [
                        [ CharParser::class,
                            [
                                null,
                                false,
                            ],
                        ],
                        [ CharParser::class,
                            [
                                '^',
                                false,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
