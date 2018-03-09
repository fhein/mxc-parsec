<?php

namespace Mxc\Parsec\Encoding;

class Utf8 extends CharacterClassification
{
    public function getName()
    {
        return 'UTF-8';
    }

    public function toUtf32(int $codepoint)
    {
        // interpret param as byte sequence
        // this basically checks that $codepoint is
        // a valid UTF-8 sequence
        return \IntlChar::chr($codepoint);
    }

    public function fromUtf32(int $codepoint)
    {
        // interpret param as byte sequence
        // this basically checks that $codepoint is
        // a valid UTF-8 sequence
        return \IntlChar::chr($codepoint);
    }

    public function ord(string $string)
    {
        return \IntlChar::ord($string);
    }

    public function chr(int $ord)
    {
        return \IntlChar::chr($ord);
    }
}
