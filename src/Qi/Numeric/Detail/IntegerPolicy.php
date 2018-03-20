<?php

namespace Mxc\Parsec\Qi\Numeric\Detail;

class IntegerPolicy
{
    const CS_DIGITS = 0;
    const CS_SIGNS  = 1;

    protected $digits;
    protected $signs;
    protected $toDecimal;
    protected $toString;

    public function __construct(array $digits, array $signs = null)
    {
        $this->signs = $signs;
        $this->digits = $digits;
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
