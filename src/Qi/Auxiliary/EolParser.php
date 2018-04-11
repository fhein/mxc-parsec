<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PrimitiveParser;

class EolParser extends PrimitiveParser
{

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if ($iterator->current() === "\r") {
            $iterator->next();
            if ($iterator->current() === "\n") {
                return true;
            } else {
                $iterator->reject();
                $iterator->try();
            }
        }

        switch ($iterator->current()) {
            // @todo: Critical where charset's \n
            // does not mean a line separator
            case "\n":
                /***/
            case \IntlChar::chr(0x0085):
                /***/
            case \IntlChar::chr(0x2028):
                /***/
            case \IntlChar::chr(0x2029):
                return true;
        }

        return false;
    }
}
