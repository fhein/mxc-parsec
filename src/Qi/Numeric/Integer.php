<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Numeric\Detail\IntegerPolicy;
use Mxc\Parsec\Qi\PreSkipper;

// provides int_, lit, bin, oct, hex, uint_, ushort,
/**
 * Generic parsing for all integer parsers
 * IntParser, UIntParser, BinaryParser, OctParser, HexParser
 *
 */
class Integer extends PreSkipper
{
    protected $minDigits;
    protected $toDecimal;
    protected $expectedValue;
    protected $notmax;

    public function __construct(
        Domain $domain,
        IntegerPolicy $policy,
        int $expectedValue = null,
        int $minDigits = 1,
        int $maxDigits = 0,
        int $minValue = null,
        int $maxValue = null
    ) {
        if (($minDigits < 1) || (($maxDigits > 0) && ($minDigits > $maxDigits))) {
            $msg = sprintf(
                '%s: Invalid arguments: minDigits: %d , maxDigits: %d',
                $this->what(),
                $minDigits,
                $maxDigits
            );
            throw new InvalidArgumentException($msg);
        }

        parent::__construct($domain);
        $this->expectedValue = $expectedValue;
        $this->minDigits = $minDigits;
        $this->notmax = $maxDigits < $minDigits ? function ($i) {
            return true;
        } : function ($i) use ($maxDigits) {
            return $i <= $maxDigits;
        };

        $this->policy = $policy;
        $this->digitsParser = new CharsetParser($domain, $policy->getDigits());
        $this->signsParser = new CharsetParser($domain, $policy->getSigns());
        $this->toDecimal = $policy->getToDecimal();
    }

    public function doParse($skipper)
    {
        $got = 0;
        $sign = '';
        if ($this->signsParser->doParse($this->iterator)) {
            $sign = $this->signsParser->getAttribute();
        }

        while ($got < $this->minDigits) {
            if (! $this->digitsParser->doParse($this->iterator)) {
                return false;
            }
            $got++;
        }

        while (($this->notmax)($got) && $this->digitsParser->doParse($this->iterator)) {
            $got++;
        }

        // @todo: integer overflow
        //     // check overflow condition
        //     if ($str === ($sgn.($this->toString)($this->castTo('integer', ($this->toDecimal)($str))))) {
        //     throw new OverflowException(sprintf('Integer overflow on %s. Try \'string\' attribute type.', $str));
        $this->attribute = ($this->toDecimal)($sign . $this->digitsParser->getAttribute());
//         if (get_class($this) === BinaryParser::class)
//             print($this->attribute."\n");
        return ($this->notmax)($got) && ($this->expectedValue === null || $this->attribute === $this->expectedValue);
    }
}
