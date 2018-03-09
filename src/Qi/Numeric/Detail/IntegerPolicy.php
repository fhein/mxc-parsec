<?php

namespace Mxc\Parsec\Qi\Numeric\Detail;

class IntegerPolicy
{

    const CS_DIGITS = 0;
    const CS_SIGNS = 1;

    protected $digits;
    protected $signs;
    protected $radix;
    protected $sign;
    protected $value;
    protected $toDecimal;
    protected $toString;

    public function __construct(array $digits, array $signs = null)
    {
        $this->signs = $signs;
        $this->digits = $digits;
        $this->radix = count($digits);
        $this->sign = 1;
        $this->value = 0;
    }

    public function getRadix()
    {
        return $this->radix;
    }

    public function setSign($c)
    {
        $this->sign = $this->signs[$c];
        print($this->sign."\n");
    }

    public function accumulate($digit)
    {
        print('Hallo'. $digit."   ". $this->digits[$digit]."\n");
        $this->value = (($this->value * $this->radix) + ($this->sign * $this->digits[$digit]));
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getSigns()
    {
        return $this->signs;
    }

    public function getDigits()
    {
        return $this->digits;
    }

    public function getToString()
    {
        return $this->toString;
    }

    public function getToDecimal()
    {
        return $this->toDecimal;
    }
}
