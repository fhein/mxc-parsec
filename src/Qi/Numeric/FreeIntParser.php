<?php

namespace Mxc\Parsec\Qi\Numeric;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Qi\Char\CharSetParser;
use Mxc\Parsec\Qi\Numeric\Detail\DecimalIntPolicy;
use Mxc\Parsec\Qi\Numeric\Detail\IntegerPolicy;
use Mxc\Parsec\Qi\PreSkipper;

// provides int_, lit, bin, oct, hex, uint_, ushort,

class FreeIntParser extends PreSkipper
{

    protected $minDigits;
    protected $maxDigits;
    protected $outOfRange;

    public function __construct(Domain $domain, IntegerPolicy $policy = null, int $minDigits = 1, $maxDigits = -1)
    {
        if (($minDigits < 1) || (($maxDigits > 0) && ($minDigits > $maxDigits))) {
            throw new InvalidArgumentException("IntParser: Invalid arguments.");
        }

        // default policy is signed decimal int with '+' and '-' signs
        $policy = $policy ?: new DecimalIntPolicy(true);
        $this->minDigits = $minDigits;
        $this->maxDigits = $maxDigits;

        $this->digitsParser = new CharSetParser($domain, $policy->getCharSet(IntegerPolicy::CS_DIGITS));
        $signs = $policy->getCharSet(IntegerPolicy::CS_SIGNS);
        if ($signs !== null) {
            $this->signsParser = new CharSetParser($domain, $signs);
        }
        $this->policy = $policy;
    }

    protected function parse($iterator, $expectedValue = null, string $attributeType = 'integer', $skipper = null)
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
        $policy = &$this->policy;
        $radix = &$this->radix;
        $result = '';
        $mul = 1;

        if ($signsParser !== null && $signsParser->parse($iterator)) {
            $sign = $signsParser->getAttribute();
        }

        if (! $iterator->valid()) {
            return false;
        }

        while ($got < $min) {
            if (! $digitsParser->parse($iterator)) {
                print("Dying.\n");
                return false;
            }
            $policy->accumulate($digitsParser->getAttribute());
            $got++;
        }

        while ($notmax($got) && $digitsParser->parse($iterator)) {
            $policy->accumulate($digitsParser->getAttribute());
            $got++;
        }
        if ($notmax($got)) {
            $this->assignTo($sign.$digitsParser->getAttribute(), $attributeType);
            return true;
        }
        return false;
    }
}
