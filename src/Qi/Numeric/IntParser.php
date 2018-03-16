<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Numeric\Detail\DecimalIntPolicy;
use Mxc\Parsec\Qi\Numeric\Detail\IntegerPolicy;
use Mxc\Parsec\Qi\PreSkipper;
use Mxc\Parsec\Exception\OverflowException;

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

        $this->minDigits = $minDigits;
        $this->maxDigits = $maxDigits;
        $this->noThrow = $noThrow;

        // default policy is signed decimal int with '+' and '-' signs
        $policy = $policy ?: new DecimalIntPolicy(true);
        $this->digitsParser = new CharSetParser($domain, $policy->getDigits());
        $this->signsParser = new CharSetParser($domain, $policy->getSigns());
        $this->toString = $policy->getToString();
        $this->toDecimal = $policy->getToDecimal();
        $this->tolower = $this->toString === 'dechex';
    }

    protected function doParse($iterator, $expectedValue = null, string $attributeType = 'integer', $skipper = null)
    {
        $got = 0;
        $min = &$this->minDigits;
        $max = $this->maxDigits;
        $notmax = $max === -1 ? function ($i) {
            return true;
        } : function ($i) use ($max) {
            return $i <= $max;
        };
        $signsParser = &$this->signsParser;
        $digitsParser = &$this->digitsParser;

        if ($signsParser->doParse($iterator)) {
            $sign = $signsParser->getAttribute();
        }

        if (! $iterator->valid()) {
            return false;
        }

        // by calling $digitsParser->doParse we
        // prevent both preskipping to occur
        // and digitsParser attribute reset
        while ($got < $min) {
            if (! $digitsParser->doParse($iterator)) {
                return false;
            }
            $got++;
        }

        while ($notmax($got) && $digitsParser->doParse($iterator)) {
            $got++;
        }

        if ($notmax($got)) {
            $str = $sign.$digitsParser->getAttribute();
            // if attributetype is string or null there are no bounds
            // such as PHP_INT_MIN or PHP_INT_MAX
            if ($attributeType === 'string' || $attributeType === 'NULL') {
                $this->assignTo($str, $attributeType);
                return true;
            }

            // strtolower ok here, because all characters are ascii
            if ($this->tolower) {
                $str = strtolower($str);
            }

            $sgn = $sign === '-' ? '' : $sign;

            if ($str === ($sgn.($this->toString)($this->castTo('integer', ($this->toDecimal)($str))))) {
                $this->assignTo(($this->toDecimal)($str), $attributeType);
                return true;
            }

            if ($this->noThrow) {
                $this->assignTo($sign.$digitsParser->getAttribute(), 'string');
                return true;
            }

            throw new OverflowException(sprintf('Integer overflow on %s. Try \'string\' attribute type.', $str));
        }
        return false;
    }
}
