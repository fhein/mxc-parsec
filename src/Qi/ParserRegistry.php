<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Qi\String\StringParser;
use Mxc\Parsec\Qi\String\SymbolsParser;
use Mxc\Parsec\Qi\Auxiliary\EoiParser;
use Mxc\Parsec\Qi\Auxiliary\EolParser;
use Mxc\Parsec\Qi\Auxiliary\EpsilonParser;
use Mxc\Parsec\Qi\Directive\MatchesDirective;
use Mxc\Parsec\Qi\Directive\LexemeDirective;
use Mxc\Parsec\Qi\Directive\NoSkipDirective;
use Mxc\Parsec\Qi\Directive\RawDirective;
use Mxc\Parsec\Qi\Directive\OmitDirective;
use Mxc\Parsec\Qi\Directive\RepeatDirective;
use Mxc\Parsec\Qi\Directive\SkipDirective;
use Mxc\Parsec\Qi\Directive\NoCaseDirective;
use Mxc\Parsec\Qi\Directive\ExpectDirective;
use Mxc\Parsec\Qi\Operator\ExpectOperator;
use Mxc\Parsec\Qi\Operator\KleeneOperator;
use Mxc\Parsec\Qi\Operator\ListOperator;
use Mxc\Parsec\Qi\Operator\NotPredicate;
use Mxc\Parsec\Qi\Operator\OptionalOperator;
use Mxc\Parsec\Qi\Operator\PermutationOperator;
use Mxc\Parsec\Qi\Operator\PlusOperator;
use Mxc\Parsec\Qi\Operator\SequenceOperator;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Operator\AndPredicate;
use Mxc\Parsec\Qi\Operator\AlternativeOperator;
use Mxc\Parsec\Qi\Numeric\BoolParser;
use Mxc\Parsec\Qi\Directive\HoldDirective;
use Mxc\Parsec\Qi\Auxiliary\AttrParser;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Qi\Binary\WordParser;
use Mxc\Parsec\Qi\Binary\DWordParser;
use Mxc\Parsec\Qi\Binary\QWordParser;
use Mxc\Parsec\Qi\Binary\LittleWordParser;
use Mxc\Parsec\Qi\Binary\LittleDWordParser;
use Mxc\Parsec\Qi\Binary\LittleQWordParser;
use Mxc\Parsec\Qi\Binary\BigWordParser;
use Mxc\Parsec\Qi\Binary\BigDWordParser;
use Mxc\Parsec\Qi\Binary\BigQWordParser;
use Mxc\Parsec\Qi\Binary\ByteParser;
use Mxc\Parsec\Qi\Numeric\UShortParser;
use Mxc\Parsec\Qi\Numeric\BinaryParser;
use Mxc\Parsec\Qi\Numeric\HexParser;
use Mxc\Parsec\Qi\Numeric\OctParser;
use Mxc\Parsec\Qi\Char\CharRangeParser;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Char\CharClassParser;

return
[
    'word'          => WordParser::class,
    'dword'         => DWordParser::class,
    'qword'         => QWordParser::class,
    'little_word'   => LittleWordParser::class,
    'little_dword'  => LittleDWordParser::class,
    'little_qword'  => LittleQWordParser::class,
    'big_word'      => BigWordParser::class,
    'big_dword'     => BigDWordParser::class,
    'big_qword'     => BigQWordParser::class,
    'byte'          => ByteParser::class,
    'bin'           => BinaryParser::class,
    'string'        => StringParser::class,
    'lit'           => StringParser::class,     // w/o attribute
    'symbols'       => SymbolsParser::class,
    'eoi'           => EoiParser::class,
    'eol'           => EolParser::class,
    'eps'           => EpsilonParser::class,
    'attr'          => AttrParser::class,
    'char'          => CharParser::class,
    'char_class'    => CharClassParser::class,
    'char_range'    => CharRangeParser::class,
    'char_set'      => CharSetParser::class,
    '|'             => AlternativeOperator::class,
    '&'             => AndPredicate::class,
    '-2'            => DifferenceOperator::class,
    '>'             => ExpectOperator::class,
    '*'             => KleeneOperator::class,
    '%'             => ListOperator::class,
    '!'             => NotPredicate::class,
    '-'             => OptionalOperator::class,
    '^'             => PermutationOperator::class,
    '+'             => PlusOperator::class,
    '>>'            => SequenceOperator::class,
    'bool'          => BoolParser::class,
    'hex'           => HexParser::class,
    'oct'           => OctParser::class,
    'ushort'        => UShortParser::class,
    'lexeme'        => LexemeDirective::class,
    'no_skip'       => NoSkipDirective::class,
    'no_case'       => NoCaseDirective::class,
    'omit'          => OmitDirective::class,
    'raw'           => RawDirective::class,
    'repeat'        => RepeatDirective::class,
    'matches'       => MatchesDirective::class,
    'skip'          => SkipDirective::class,
    'hold'          => HoldDirective::class,
    'expect'        => ExpectDirective::class,
//    'as'        => as<T>,
    'as_string' => AsStringDirective::class,
    'as_wString' => AsWStringDirective::class,
];
