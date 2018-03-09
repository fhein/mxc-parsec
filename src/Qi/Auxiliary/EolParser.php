<?php

namespace Mxc\Parsec\Qi\Auxiliary;

use Mxc\Parsec\Qi\PrimitiveParser;

class EolParser extends PrimitiveParser
{

    protected function parse($iterator, $expectedValue, $attributeType, $skipper)
    {
        // @todo: Parsec assumes that eol is
        // 0x0A or 0x0D 0x0A
        //
        // but
        //  U+0085 (NEL)
        //  U+2028 (Line Separator)
        //  U+2029 (Paragraph Separator)
        //
        // get ignored by Parsec currently
        //
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
