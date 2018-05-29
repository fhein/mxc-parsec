<?php
namespace Mxc\Test\Parsec\Qi\Assets;

class AttributeConstructibleClass
{
    protected $attribute;

    public function __construct($attribute)
    {
        $this->attribute = $attribute;
    }

    public function getAttribute()
    {
        return $this->attribute;
    }
}
