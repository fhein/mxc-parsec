<?php

namespace Mxc\Parsec\Service;

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
use Mxc\Parsec\Encoding\Utf8Decoder;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Exception\ServiceNotFoundException;

class ParserManager
{
    protected $encodings =
    [
        'UTF-8' => Utf8Decoder::class,
    ];

    protected $services = [];

    protected $shared_by_default = true;

    protected $parsers =
    [
        // auxiliary
        EolParser::class,
        AttrParser::class,
        EoiParser::class,
        EpsilonParser::class,
        LazyParser::class,
        // binary
        BigDWordParser::class,
        BigQWordParser::class,
        BinDoubleParser::class,
        BinFloatParser::class,
        ByteParser::class,
        DWordParser::class,
        LittleDWordParser::class,
        LittleQWordParser::class,
        QWordParser::class,
        WordParser::class,
        // char
        CharClassParser::class,
        CharParser::class,
        CharRangeParser::class,
        CharSetParser::class,
        // directive
        ExpectDirective::class,
        HoldDirective::class,
        LexemeDirective::class,
        MatchesDirective::class,
        NoCaseDirective::class,
        NoSkipDirective::class,
        OmitDirective::class,
        PassThroughDirective::class,
        RawDirective::class,
        RepeatDirective::class,
        SkipDirective::class,
        // nonterminal
        RuleParser::class,
        // numeric
        BinaryParser::class,
        BoolParser::class,
        HexParser::class,
        IntParser::class,
        OctParser::class,
        UIntParser::class,
        //operator
        AlternativeOperator::class,
        AndPredicate::class,
        DifferenceOperator::class,
        ExpectOperator::class,
        KleeneOperator::class,
        ListOperator::class,
        NotPredicate::class,
        OptionalOperator::class,
        PermutationOperator::class,
        PlusOperator::class,
        SequenceOperator::class,
        // string
        StringParser::class,
        SymbolsParser::class,
    ];

    protected $factories = [];

    protected $aliases =
    [
        // auxiliary
        'eol' => EolParser::class,
        'attr' => AttrParser::class,
        'eoi' => EoiParser::class,
        'eps' => EpsilonParser::class,
        'lazy' => LazyParser::class,
        // binary
        'big_dword' => BigDWordParser::class,
        'big_qword' => BigQWordParser::class,
        'bin_double' => BinDoubleParser::class,
        'bin_float' => BinFloatParser::class,
        'byte' => ByteParser::class,
        'dword' => DWordParser::class,
        'little_dword' => LittleDWordParser::class,
        'little_qword' => LittleQWordParser::class,
        'qword' => QWordParser::class,
        'word' => WordParser::class,
        // char
        'char_class' => CharClassParser::class,
        'char' => CharParser::class,
        'char_range' => CharRangeParser::class,
        'char_set' => CharSetParser::class,
        // directive
        'expect' => ExpectDirective::class,
        'hold' => HoldDirective::class,
        'lexeme' => LexemeDirective::class,
        'matches' => MatchesDirective::class,
        'no_case' => NoCaseDirective::class,
        'no_skip' => NoSkipDirective::class,
        'omit' => OmitDirective::class,
        'passthrough' => PassThroughDirective::class,
        'raw' => RawDirective::class,
        'repeat' => RepeatDirective::class,
        'skip' => SkipDirective::class,
        // nonterminal
        'rule' => RuleParser::class,
        // numeric
        'binary' => BinaryParser::class,
        'bool' => BoolParser::class,
        'hex' => HexParser::class,
        'int' => IntParser::class,
        'oct' => OctParser::class,
        'uint' => UIntParser::class,
        //operator
        '|' => AlternativeOperator::class,
        '&' => AndPredicate::class,
        '-' => DifferenceOperator::class,
        '>' => ExpectOperator::class,
        '*' => KleeneOperator::class,
        '%' => ListOperator::class,
        '!' => NotPredicate::class,
        '-1' => OptionalOperator::class,
        '^' => PermutationOperator::class,
        '+' => PlusOperator::class,
        '>>' => SequenceOperator::class,
        // string
        'string' => StringParser::class,
        'symbols' => SymbolsParser::class,
    ];

    protected $sharedByDefault = false;

    protected $shared =
    [
        'p' => true,
    ];

    public function get($name)
    {
        // We start by checking if we have cached the requested service (this
        // is the fastest method).
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        // Determine if the service should be shared
        $sharedService = ($this->sharedByDefault && ! isset($this->shared[$name])
            || (isset($this->shared[$name]) && $this->shared[$name]));

        $resolvedName = isset($this->resolvedAliases[$name]) ? $this->resolvedAliases[$name] : $name;

        // Can only become true, if the requested service is an alias
        $serviceAvailable = isset($this->services[$resolvedName]);

        // If the alias is configured as shared service, we are done.
        if ($serviceAvailable && $sharedService) {
            $this->services[$name] = $this->services[$resolvedName];
            return $this->services[$resolvedName];
        }

        // At this point, we can have a shared service available
        // which fits an alias but latter is not configured as shared
        // Or we have a request which should be satisfiable by a
        // factory.

        // We need to find out if we have a factory. We use the
        // resolved name for that. If we have, we create the instance.
        // Otherwise (catch) we check, if this is a non-shared alias
        // without a factory but with a shared service available
        try {
            $object = $this->doCreate($resolvedName);
        } catch (ServiceNotFoundException $e) {
            if ($serviceAvailable) {
                // At this point we have a non-shared alias without
                // a matching factory but with a matching shared
                // service which we use to clone from.
                return clone $this->services[$resolvedName];
            }
            // There is a configuration issue.
            throw($e);
        }

        // Cache the object for later, if it is supposed to be shared.
        if (($this->sharedByDefault && ! isset($this->shared[$resolvedName])
            || (isset($this->shared[$resolvedName]) && $this->shared[$resolvedName]))) {
                $this->services[$resolvedName] = $object;
        }

            // Also do so for aliases, this allows sharing based on service name used.
        if (($resolvedName !== $name) && $sharedService) {
            $this->services[$name] = $object;
        }

            return $object;
    }

    public function __construct(string $inputEncoding = 'UTF-8', string $internalEncoding = 'UTF-8')
    {
        if (! (isset($this->encodings[$inputEncoding]) && isset($this->encoding[$internalEncoding]))) {
            throw new InvalidArgumentException();
        }
        $encoding = $this->encodings[$inputEncoding];
        $this->services[$inputEncoding] = new $encoding;
        $this->aliases['p'] = $inputEncoding;
        $internalEncoding = $this->encodings[$internalEncoding];
        if ($internalEncoding !== $encoding) {
            $this->services[$internalEncoding] = new $encoding;
        }
        $this->aliases['t'] = $internalEncoding;
    }


    public function getInternalIterator(string $string)
    {
        $iterator = $this->get('t');
        $iterator->setData($string);
        return $iterator;
    }

    public function getInputIterator()
    {
        return $this->get('p');
    }
}
