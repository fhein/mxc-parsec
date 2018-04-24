<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PrimitiveParser;

class EolParser extends PrimitiveParser
{

    public function doParse($skipper)
    {
        if ($this->iterator->current() === "\r") {
            $this->iterator->next();
            if ($this->iterator->current() === "\n") {
                return true;
            } else {
                $this->iterator->reject();
                $this->iterator->try();
            }
        }

        switch ($this->iterator->current()) {
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
