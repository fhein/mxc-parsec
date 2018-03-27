<?php

namespace Mxc\Parsec\Service;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Encoding\CharacterClassifier;
use Mxc\Parsec\Encoding\Utf8Decoder;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Qi\Auxiliary\AttrParser;
use Mxc\Parsec\Qi\Auxiliary\EoiParser;
use Mxc\Parsec\Qi\Auxiliary\EolParser;
use Mxc\Parsec\Qi\Auxiliary\EpsParser;
use Mxc\Parsec\Qi\Auxiliary\LazyParser;
use Mxc\Parsec\Qi\Binary\BigBinDoubleParser;
use Mxc\Parsec\Qi\Binary\BigBinFloatParser;
use Mxc\Parsec\Qi\Binary\BigDWordParser;
use Mxc\Parsec\Qi\Binary\BigQWordParser;
use Mxc\Parsec\Qi\Binary\BigWordParser;
use Mxc\Parsec\Qi\Binary\BinDoubleParser;
use Mxc\Parsec\Qi\Binary\BinFloatParser;
use Mxc\Parsec\Qi\Binary\ByteParser;
use Mxc\Parsec\Qi\Binary\DWordParser;
use Mxc\Parsec\Qi\Binary\LittleBinDoubleParser;
use Mxc\Parsec\Qi\Binary\LittleBinFloatParser;
use Mxc\Parsec\Qi\Binary\LittleDWordParser;
use Mxc\Parsec\Qi\Binary\LittleQWordParser;
use Mxc\Parsec\Qi\Binary\LittleWordParser;
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
use Mxc\Parsec\Qi\String\StringParser;
use Mxc\Parsec\Qi\String\SymbolsParser;
use Mxc\Parsec\Qi\Unused;
use Mxc\Parsec\Service\ParserFactory;
use Zend\ServiceManager\ServiceManager;

class ParserManager extends ServiceManager
{
    protected $encodings =
    [
        'UTF-8' => Utf8Decoder::class,
    ];

    protected $services = [
        'config' => [
            'input_encoding'     => 'UTF-8',
            'internal_encoding'  => 'UTF-8',
            'output_encoding'    => 'UTF-8',
        ],
    ];

    protected $factories =
    [
        // auxiliary
        EolParser::class                => ParserFactory::class,
        AttrParser::class               => ParserFactory::class,
        EoiParser::class                => ParserFactory::class,
        EpsParser::class                => ParserFactory::class,
        LazyParser::class               => ParserFactory::class,
        // binary
        ByteParser::class               => ParserFactory::class,
        WordParser::class               => ParserFactory::class,
        DWordParser::class              => ParserFactory::class,
        QWordParser::class              => ParserFactory::class,
        BigWordParser::class            => ParserFactory::class,
        BigDWordParser::class           => ParserFactory::class,
        BigQWordParser::class           => ParserFactory::class,
        LittleWordParser::class         => ParserFactory::class,
        LittleDWordParser::class        => ParserFactory::class,
        LittleQWordParser::class        => ParserFactory::class,
        BinDoubleParser::class          => ParserFactory::class,
        BigBinDoubleParser::class       => ParserFactory::class,
        LittleBinDoubleParser::class    => ParserFactory::class,
        BinFloatParser::class           => ParserFactory::class,
        BigBinFloatParser::class        => ParserFactory::class,
        LittleBinFloatParser::class     => ParserFactory::class,
        // char
        CharClassParser::class          => ParserFactory::class,
        CharParser::class               => ParserFactory::class,
        CharRangeParser::class          => ParserFactory::class,
        CharSetParser::class            => ParserFactory::class,
        // directive
        ExpectDirective::class          => ParserFactory::class,
        HoldDirective::class            => ParserFactory::class,
        LexemeDirective::class          => ParserFactory::class,
        MatchesDirective::class         => ParserFactory::class,
        NoCaseDirective::class          => ParserFactory::class,
        NoSkipDirective::class          => ParserFactory::class,
        OmitDirective::class            => ParserFactory::class,
        PassThroughDirective::class     => ParserFactory::class,
        RawDirective::class             => ParserFactory::class,
        RepeatDirective::class          => ParserFactory::class,
        SkipDirective::class            => ParserFactory::class,
        // nonterminal
        RuleParser::class               => ParserFactory::class,
        // numeric
        BinaryParser::class             => ParserFactory::class,
        BoolParser::class               => ParserFactory::class,
        HexParser::class                => ParserFactory::class,
        IntParser::class                => ParserFactory::class,
        OctParser::class                => ParserFactory::class,
        UIntParser::class               => ParserFactory::class,
        //operator
        AlternativeOperator::class      => ParserFactory::class,
        AndPredicate::class             => ParserFactory::class,
        DifferenceOperator::class       => ParserFactory::class,
        ExpectOperator::class           => ParserFactory::class,
        KleeneOperator::class           => ParserFactory::class,
        ListOperator::class             => ParserFactory::class,
        NotPredicate::class             => ParserFactory::class,
        OptionalOperator::class         => ParserFactory::class,
        PermutationOperator::class      => ParserFactory::class,
        PlusOperator::class             => ParserFactory::class,
        SequenceOperator::class         => ParserFactory::class,
        // string
        StringParser::class             => ParserFactory::class,
        SymbolsParser::class            => ParserFactory::class,
        // non parsers
        Domain::class                   => DomainFactory::class,
    ];

    protected $invokables = [
        CharacterClassifier::class  => CharacterClassifier::class,
        Unused::class => Unused::class,
    ];

    protected $aliases =
    [
        // auxiliary
        'eol'               => EolParser::class,
        'attr'              => AttrParser::class,
        'eoi'               => EoiParser::class,
        'eps'               => EpsParser::class,
        'lazy'              => LazyParser::class,
        // binary
        'byte'              => ByteParser::class,
        'word'              => WordParser::class,
        'dword'             => DWordParser::class,
        'qword'             => QWordParser::class,
        'big_word'          => BigWordParser::class,
        'big_dword'         => BigDWordParser::class,
        'big_qword'         => BigQWordParser::class,
        'little_word'       => LittleWordParser::class,
        'little_dword'      => LittleDWordParser::class,
        'little_qword'      => LittleQWordParser::class,
        'bin_double'        => BinDoubleParser::class,
        'bin_float'         => BinFloatParser::class,
        'big_bin_double'    => BigBinDoubleParser::class,
        'big_bin_float'     => BigBinFloatParser::class,
        'little_bin_double' => LittleBinDoubleParser::class,
        'little_bin_float'  => LittleBinFloatParser::class,

        // char
        'char_class'        => CharClassParser::class,
        'char'              => CharParser::class,
        'char_range'        => CharRangeParser::class,
        'char_set'          => CharSetParser::class,
        // directive
        'expect'            => ExpectDirective::class,
        'hold'              => HoldDirective::class,
        'lexeme'            => LexemeDirective::class,
        'matches'           => MatchesDirective::class,
        'no_case'           => NoCaseDirective::class,
        'no_skip'           => NoSkipDirective::class,
        'omit'              => OmitDirective::class,
        'passthrough'       => PassThroughDirective::class,
        'raw'               => RawDirective::class,
        'repeat'            => RepeatDirective::class,
        'skip'              => SkipDirective::class,
        // nonterminal
        'rule'              => RuleParser::class,
        // numeric
        'binary'            => BinaryParser::class,
        'bool'              => BoolParser::class,
        'hex'               => HexParser::class,
        'int'               => IntParser::class,
        'oct'               => OctParser::class,
        'uint'              => UIntParser::class,
        //operator
        '|'                 => AlternativeOperator::class,
        '&'                 => AndPredicate::class,
        '-'                 => DifferenceOperator::class,
        '>'                 => ExpectOperator::class,
        '*'                 => KleeneOperator::class,
        '%'                 => ListOperator::class,
        '!'                 => NotPredicate::class,
        '-1'                => OptionalOperator::class,
        '^'                 => PermutationOperator::class,
        '+'                 => PlusOperator::class,
        '>>'                => SequenceOperator::class,
        // string
        'string'            => StringParser::class,
        'symbols'           => SymbolsParser::class,
    ];

    protected $abstractFactories = [
        IteratorFactory::class
    ];

    protected $sharedByDefault = false;

    protected $shared =
    [
        Unused::class => true,
        CharacterClassifier::class => true,
    ];

    public function __construct(array $options = [])
    {
        if (! empty($options)) {
            $this->services['config'] = $options;
        }
        $config = $this->services['config'];
        $inputEncoding = $config['input_encoding'] ?? null;
        $internalEncoding = $config['internal_encoding'] ?? null;
        if ($inputEncoding === null) {
            throw new InvalidArgumentException();
        }
        if ($internalEncoding === null) {
            throw new InvalidArgumentException();
        }
        $inputEncoding = $this->encodings[$config['input_encoding']];
        parent::__construct();

        $this->services['input_encoding'] = $this->get($config['input_encoding']);
        $this->services['internal_encoding'] = $this->get($config['internal_encoding']);
    }
}
