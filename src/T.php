<?php

namespace Mxc\Parsec;

use Mxc\Parsec\Qi\String\StringParser;
use Mxc\Parsec\Qi\String\SymbolsParser;
use Mxc\Parsec\Domain;
use Mxc\Parsec\Qi\Operator\AlternativeOperator;
use Mxc\Parsec\Qi\Directive\NoCaseDirective;
use Mxc\Parsec\Qi\Char\CharRangeParser;
use Mxc\Parsec\Qi\Numeric\BoolParser;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Numeric\IntParser;
use Mxc\Parsec\Qi\Char\CharClassParser;
use Mxc\Parsec\Qi\Binary\BigWordParser;
use Mxc\Parsec\Qi\Directive\NoSkipDirective;
use Mxc\Parsec\Qi\Binary\BinFloatParser;
use Mxc\Parsec\Qi\Binary\BinDoubleParser;
use Mxc\Parsec\Qi\Binary\BigQWordParser;
use Mxc\Parsec\Qi\Operator\SequenceOperator;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Qi\Numeric\Detail\BinaryIntPolicy;
use Mxc\Parsec\Qi\Numeric\Detail\DecimalIntPolicy;
use Mxc\Parsec\Qi\Numeric\Detail\OctalIntPolicy;
use Mxc\Parsec\Qi\Numeric\Detail\HexIntPolicy;
use Zend\ServiceManager\ServiceManager;
use Mxc\Parsec\Qi\TypedDelegator;

include __DIR__.'/../autoload.php';

$c = "true 5678 9098 7654321";// Ich B8truein Ein klei ner König0.01";

$arr = include '../config/config.php';

$sm = new ServiceManager($arr['parsers']);
$domain = $sm->build(Domain::class);
$domain->setSource($c);

$iterator = $domain->getInputIterator();
$skipper = new CharClassParser($domain, 'space');

$c = new SequenceOperator(
    $domain,
    [
            new SequenceOperator(
                $domain,
                [
                    new SequenceOperator(
                        $domain,
                        [
                            new CharParser($domain),
                            new CharParser($domain)
                        ],
                        true
                    ),
                    new CharParser($domain)
                ],
                true
            ),
            new CharParser($domain)
        ],
    true
);
$sequence = new TypedDelegator($c, 'boolean');

function expandWhat($what)
{
    if (is_array($what)) {
        return expandWhat($what[0]). '(' . expandWhat($what[1]).')';
    }
    return $what;
}

function parse($parser, $iterator, $expectedValue = null, $skipper = null)
{
    $result = [
        true => "Success.",
        false => "Failure.",
    ];
    printf(
        "%s: Trying [%u] '%s' -> ",
        expandWhat($parser->what()),
        $iterator->key(),
        substr($iterator->getData(), $iterator->key())
    );
    $r = $parser->parse($iterator, $expectedValue, $skipper);
    printf("%s\n", $result[$r]);
    if ($r === true) {
        $a = $parser->getAttribute();
        printf("Attribute type: %s, value: ", gettype($a));
        var_export($parser->getAttribute());
        print("\n\n");
    }
}

// // $b =  new BigQWordParser($domain);
// // parse($b, $iterator, $skipper);

// $b =  new TypedDelegator(new BinFloatParser($domain), 'float');
// parse($b, $iterator, $skipper);

// $b =  new TypedDelegator(new BinDoubleParser($domain), 'float');
// parse($b, $iterator, $skipper);

// $b = new TypedDelegator(new BigWordParser($domain), 'boolean');
// parse($b, $iterator);
// $i = new IntParser($domain, new HexIntPolicy());
// $i = new TypedDelegator($i, 'integer');
// parse($i, $iterator, 0x1234, $skipper);

// $cc = new CharSetParser($domain, '0-9A-Za-t');
// parse($cc, $iterator, $skipper);


$b = $sm->get(BoolParser::class);
$b = new BoolParser($domain);
$b = new TypedDelegator($b, 'array');
parse($b, $iterator, true, $skipper);

// $b = new NoCaseDirective($domain, new StringParser($domain, 'In'));
// parse($b, $iterator, $skipper);

// $alternative = new AlternativeOperator(
//      $domain,
//        true,
//        [ new StringParser($domain, 'Ein'), new StringParser($domain, 'klei')]);
// $b = new TypedDelegator($alternative, 'unused');
// parse($b, $iterator, $skipper);

// $s = new SymbolsParser($domain);
// $no_case = new NoCaseDirective($domain, $s);
// $no_skip = new NoSkipDirective($domain, $no_case);

// $s->add('ner könig',0);
// $s->add('si',0);
// $s->add('sache',0);
// $s->add('kleiner',0);
// $s->add('sachen',0);
// $s->add('sachte',0);
// $s->add('klei',0);


// $cc = new CharRangeParser($domain, 'A', 'B');
// //var_dump($cc->parse($iterator));

// parse($s, $iterator, $skipper);
// parse($no_case, $iterator, $skipper);
// parse($no_skip, $iterator, $skipper);
// parse($sequence, $iterator, $skipper);
