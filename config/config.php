<?php

namespace Mxc\Parsec;

use Mxc\Parsec\Qi\String\StringParser;
use Mxc\Parsec\Qi\Auxiliary\EolParser;
use Mxc\Parsec\Service\ParserFactory;
use Mxc\Parsec\Qi\Auxiliary\AttrParser;
use Mxc\Parsec\Qi\Auxiliary\EoiParser;
use Mxc\Parsec\Qi\Auxiliary\EpsilonParser;
use Mxc\Parsec\Qi\Auxiliary\LazyParser;
use Mxc\Parsec\Qi\Binary\BigDWordParser;
use Mxc\Parsec\Qi\Binary\BigQWordParser;
use Mxc\Parsec\Qi\Binary\BinDoubleParser;
use Mxc\Parsec\Qi\Binary\BinFloatParser;
use Mxc\Parsec\Qi\Binary\ByteParser;
use Mxc\Parsec\Qi\Binary\DWordParser;
use Mxc\Parsec\Qi\Binary\LittleDWordParser;
use Mxc\Parsec\Qi\Binary\LittleQWordParser;
use Mxc\Parsec\Qi\Binary\QWordParser;
use Mxc\Parsec\Qi\Binary\WordParser;
use Mxc\Parsec\Qi\Char\CharClassParser;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Qi\Char\CharRangeParser;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Directive\ExpectDirective;
use Mxc\Parsec\Qi\Directive\HoldDirective;
use Mxc\Parsec\Qi\Directive\LexemeDirective;
use Mxc\Parsec\Qi\Directive\MatchesDirective;
use Mxc\Parsec\Qi\Directive\NoCaseDirective;
use Mxc\Parsec\Qi\Directive\NoSkipDirective;
use Mxc\Parsec\Qi\Directive\OmitDirective;
use Mxc\Parsec\Qi\Directive\PassThroughDirective;
use Mxc\Parsec\Qi\Directive\RawDirective;
use Mxc\Parsec\Qi\Directive\RepeatDirective;
use Mxc\Parsec\Qi\Directive\SkipDirective;
use Mxc\Parsec\Qi\NonTerminal\RuleParser;
use Mxc\Parsec\Qi\Numeric\BinaryParser;
use Mxc\Parsec\Qi\Numeric\BoolParser;
use Mxc\Parsec\Qi\Numeric\HexParser;
use Mxc\Parsec\Qi\Numeric\IntParser;
use Mxc\Parsec\Qi\Numeric\OctParser;
use Mxc\Parsec\Qi\Numeric\UIntParser;
use Mxc\Parsec\Qi\Operator\AlternativeOperator;
use Mxc\Parsec\Qi\Operator\AndPredicate;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Operator\ExpectOperator;
use Mxc\Parsec\Qi\Operator\KleeneOperator;
use Mxc\Parsec\Qi\Operator\ListOperator;
use Mxc\Parsec\Qi\Operator\NotPredicate;
use Mxc\Parsec\Qi\Operator\OptionalOperator;
use Mxc\Parsec\Qi\Operator\PermutationOperator;
use Mxc\Parsec\Qi\Operator\PlusOperator;
use Mxc\Parsec\Qi\Operator\SequenceOperator;
use Mxc\Parsec\Qi\String\SymbolsParser;
use Mxc\Parsec\Service\DomainFactory;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Mxc\Parsec\Encoding\Utf8Decoder;
use Mxc\Parsec\Encoding\CharacterClassifier;
use Mxc\Parsec\Encoding\Encoding;
use Mxc\Parsec\Service\EncodingFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

return
[
    'encodings' => [
        'abstract_factories' =>
        [
            EncodingFactory::class,
        ]
    ],
    'parsers' =>
    [
        'factories' =>
        [
            CharacterClassifier::class => InvokableFactory::class,
            Domain::class => DomainFactory::class,
            // auxiliary
            EolParser::class => ParserFactory::class,
            AttrParser::class => ParserFactory::class,
            EoiParser::class => ParserFactory::class,
            EpsilonParser::class => ParserFactory::class,
            LazyParser::class => ParserFactory::class,
            // binary
            BigDWordParser::class => ParserFactory::class,
            BigQWordParser::class => ParserFactory::class,
            BinDoubleParser::class => ParserFactory::class,
            BinFloatParser::class => ParserFactory::class,
            ByteParser::class => ParserFactory::class,
            DWordParser::class => ParserFactory::class,
            LittleDWordParser::class => ParserFactory::class,
            LittleQWordParser::class => ParserFactory::class,
            QWordParser::class => ParserFactory::class,
            WordParser::class => ParserFactory::class,
            // char
            CharClassParser::class => ParserFactory::class,
            CharParser::class => ParserFactory::class,
            CharRangeParser::class => ParserFactory::class,
            CharSetParser::class => ParserFactory::class,
            // directive
            ExpectDirective::class => ParserFactory::class,
            HoldDirective::class => ParserFactory::class,
            LexemeDirective::class => ParserFactory::class,
            MatchesDirective::class => ParserFactory::class,
            NoCaseDirective::class => ParserFactory::class,
            NoSkipDirective::class => ParserFactory::class,
            OmitDirective::class => ParserFactory::class,
            PassThroughDirective::class => ParserFactory::class,
            RawDirective::class => ParserFactory::class,
            RepeatDirective::class => ParserFactory::class,
            SkipDirective::class => ParserFactory::class,
            // nonterminal
            RuleParser::class => ParserFactory::class,
            // numeric
            BinaryParser::class => ParserFactory::class,
            BoolParser::class => ParserFactory::class,
            HexParser::class => ParserFactory::class,
            IntParser::class => ParserFactory::class,
            OctParser::class => ParserFactory::class,
            UIntParser::class => ParserFactory::class,
            //operator
            AlternativeOperator::class => ParserFactory::class,
            AndPredicate::class => ParserFactory::class,
            DifferenceOperator::class => ParserFactory::class,
            ExpectOperator::class => ParserFactory::class,
            KleeneOperator::class => ParserFactory::class,
            ListOperator::class => ParserFactory::class,
            NotPredicate::class => ParserFactory::class,
            OptionalOperator::class => ParserFactory::class,
            PermutationOperator::class => ParserFactory::class,
            PlusOperator::class => ParserFactory::class,
            SequenceOperator::class => ParserFactory::class,
            // string
            StringParser::class => ParserFactory::class,
            SymbolsParser::class => ParserFactory::class,
        ],
        'abstract_factories' =>
        [
            EncodingFactory::class
        ],
        'shared' =>
        [
            'UTF-8' => false,
        ]
    ],
];
