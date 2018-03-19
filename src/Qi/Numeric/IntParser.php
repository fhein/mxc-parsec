<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Numeric\Detail\DecimalIntPolicy;
use Mxc\Parsec\Qi\Numeric\Detail\IntegerPolicy;
use Mxc\Parsec\Qi\PreSkipper;

// provides int_, lit, bin, oct, hex, uint_, ushort,

class IntParser extends PreSkipper
{

    protected $minDigits;
    protected $maxDigits;
    protected $toDecimal;
    protected $toString;

    public function __construct(
        Domain $domain,
        IntegerPolicy $policy = null,
        int $minDigits = 1,
        $maxDigits = -1,
        bool $noThrow = false
    ) {
        if (($minDigits < 1) || (($maxDigits > 0) && ($minDigits > $maxDigits))) {
            throw new InvalidArgumentException($this->what() . ": Invalid arguments.");
        }

        parent::__construct($domain);

        $this->defaultType = 'integer';

        $this->minDigits = $minDigits;
        $this->maxDigits = $maxDigits;
        $this->noThrow = $noThrow;

        // default policy is signed decimal int with '+' and '-' signs
        $this->setPolicy($policy ?? new DecimalIntPolicy(true));
    }

    public function setPolicy(IntegerPolicy $policy)
    {
        $this->digitsParser = new CharsetParser($this->domain, $policy->getDigits());
        $this->signsParser = new CharsetParser($this->domain, $policy->getSigns());
        $this->toString = $policy->getToString();
        $this->toDecimal = $policy->getToDecimal();
        $this->toLower = $this->toString === 'dechex';
    }

    protected function doParse($iterator, $expectedValue = null, string $attributeType = null, $skipper = null)
    {
        $attributeType = $attributeType ?? 'integer';
        $got = 0;
        $min = $this->minDigits;
        $max = $this->maxDigits;
        $notmax = $max === -1 ? function ($i) {
            return true;
        } : function ($i) use ($max) {
            return $i <= $max;
        };

        if ($this->signsParser->doParse($iterator)) {
            $sign = $this->signsParser->getAttribute();
        }

        if (! $iterator->valid()) {
            return false;
        }

        while ($got < $min) {
            if (! $this->digitsParser->doParse($iterator)) {
                return false;
            }
            $got++;
        }

        while ($notmax($got) && $this->digitsParser->doParse($iterator)) {
            $got++;
        }

        if ($notmax($got)) {
            $str = $sign.$this->digitsParser->getAttribute();
            if ($this->toLower) {
                $str = strtolower($str);
            }
            $this->attribute = $str;
            return true;
        }
        return false;
    }

//     // if attributetype is string or null there are no bounds
//     // such as PHP_INT_MIN or PHP_INT_MAX
//     if ($attributeType === 'string' || $attributeType === 'NULL') {
//         $this->attribute = $str;
//         //$this->assignTo($str, $attributeType);
//         return true;
//     }

//     // strtolower ok here, because all characters are ascii
//     if ($this->tolower) {
//         $str = strtolower($str);
//     }

//     $sgn = $sign === '-' ? '' : $sign;

//     // check overflow condition
//     if ($str === ($sgn.($this->toString)($this->castTo('integer', ($this->toDecimal)($str))))) {
//         //$this->assignTo(($this->toDecimal)($str), $attributeType);
//         $this->attribute = $str;
//         return true;
//     }

//     if ($this->noThrow) {
//         $this->attribute = $sign.$this->digitsParser->getAttribute();
//         //$this->assignTo($sign.$this->digitsParser->getAttribute(), 'string');
//         return true;
//     }

//     throw new OverflowException(sprintf('Integer overflow on %s. Try \'string\' attribute type.', $str));

}
