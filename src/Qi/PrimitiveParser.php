<?php

namespace Mxc\Parsec\Qi;

abstract class PrimitiveParser extends Parser
{

    public function parseImpl($iterator, $expectedValue = null, $attributeType = null, $skipper = null)
    {

        if (! $iterator->valid()) {
            return false;
        }

        $iterator->try();

        // to avoid code duplication between skipping
        // and non-skipping parsers
        if ($this->skip) {
            $this->skipOver($iterator, $skipper);
        }

        if ($this->parse($iterator, $expectedValue, $attributeType, $skipper)) {
            // if the parser is successful and not particular value
            // was asked for (e.g. bool_, int_) we accept result
            if ($expectedValue === null) {
                $iterator->accept();
                return true;
            }

            // When the attribute gets casted to the attributeType
            // the original attribute is stored in rawAttribute
            // We cast the rawAttribute to the type of the value
            // we expect and compare them. If true,
            // we accept. $iterator->done is a fluent bool interface.


// if ($this->castTo($this->getType($expectedValue), $this->getRawAttribute()) !== $expectedValue) {
// printf("\nAttribute type: %s\n", $attributeType );
// printf("Expected value type: %s\n", $this->getType($expectedValue));
// printf("Raw Attribute: %s\n",var_export($this->getRawAttribute(), true));
// printf("Cast to: %s\n", var_export($this->castTo($this->getType($expectedValue), $this->attribute), true));
// printf("Expected value: %s\n", var_export($expectedValue, true));
// printf("Cast to === expectedValue: %s\n",
//      var_export($this->castTo($this->getType($expectedValue),
//      $this->getRawAttribute()) === $expectedValue, true));
//              }

            return $iterator->done($this->castTo($attributeType, $expectedValue) === $this->getRawAttribute());
        }
        $iterator->reject();
        return false;
    }
}
