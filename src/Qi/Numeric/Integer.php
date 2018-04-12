<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Domain;
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
    protected $notmax;

    public function __construct(
        Domain $domain,
        IntegerPolicy $policy,
        int $minDigits = 1,
        int $maxDigits = 0
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

        $this->defaultType = 'integer';

        $this->minDigits = $minDigits;
        $this->notmax = $maxDigits < $minDigits ? function ($i) {
            return true;
        } : function ($i) use ($maxDigits) {
            return $i <= $maxDigits;
        };

        $this->policy = $policy;
        $this->digitsParser = new CharsetParser($this->domain, $policy->getDigits());
        $this->signsParser = new CharsetParser($this->domain, $policy->getSigns());
        $this->toDecimal = $policy->getToDecimal();
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        $got = 0;

        if ($this->signsParser->doParse($iterator)) {
            $sign = $this->signsParser->getAttribute();
        }

        while ($got < $this->minDigits) {
            if (! $this->digitsParser->doParse($iterator)) {
                return false;
            }
            $got++;
        }

        while (($this->notmax)($got) && $this->digitsParser->doParse($iterator)) {
            $got++;
        }

        // @todo: integer overflow
        //     // check overflow condition
        //     if ($str === ($sgn.($this->toString)($this->castTo('integer', ($this->toDecimal)($str))))) {
        //     throw new OverflowException(sprintf('Integer overflow on %s. Try \'string\' attribute type.', $str));

        return
            // got no more than max digits
            ($this->notmax)($got)
            // and got value matches expected value ($expectedValue === 0 => any value)
            && $this->validate(
                $expectedValue,
                ($this->toDecimal)($sign . $this->digitsParser->getAttribute()),
                $attributeType
            );
    }
}
