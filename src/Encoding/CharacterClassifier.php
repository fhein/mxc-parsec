<?php

namespace Mxc\Parsec\Encoding;

class CharacterClassifier
{
        /**
         * Test characters for specified conditions (using \IntlChar)
         *
         * @method isvalid
         * @method isalnum
         * @method isalpha
         * @method isdigit
         * @method isxdigit
         * @method isupper
         * @method islower
         * @method isspace
         * @method isblank
         * @method isprint
         * @method isgraph
         * @method ispunct
         * @method iscntrl
         */
    public function isvalid($codepoint)
    {
        return (null != \IntlChar::chr($codepoint));
    }

    public function isalnum($codepoint)
    {
        return \IntlChar::isalnum($codepoint);
    }

    public function isalpha($codepoint)
    {
        return \IntlChar::isalpha($codepoint);
    }

    public function isdigit($codepoint)
    {
        return \IntlChar::isdigit($codepoint);
    }

    public function isxdigit($codepoint)
    {
        return \IntlChar::isxdigit($codepoint);
    }

    public function iscntrl($codepoint)
    {
        return \IntlChar::iscntrl($codepoint);
    }

    public function isgraph($codepoint)
    {
        return \IntlChar::isgraph($codepoint);
    }

    public function islower($codepoint)
    {
        return \IntlChar::isULowercase($codepoint);
    }

    public function isupper($codepoint)
    {
        return \IntlChar::isUUppercase($codepoint);
    }

    public function isprint($codepoint)
    {
        return \IntlChar::isprint($codepoint);
    }

    public function ispunct($codepoint)
    {
        return \IntlChar::ispunct($codepoint);
    }

    public function isspace($codepoint)
    {
        return \IntlChar::isUWhiteSpace($codepoint);
    }

    public function isblank($codepoint)
    {
        return \IntlChar::isblank($codepoint);
    }

        /**
         * Simple character conversions
         *
         * @method tolower
         * @method toupper
         * @method toUcs4
         * @method fromUcs4
         */
    public function toUcs4(int $codepoint)
    {
        return $this->isvalid($codepoint) ? $codepoint : null;
    }

    public function fromUcs4(int $codepoint)
    {
        return $this->isvalid($codepoint) ? $codepoint : null;
    }

    public function tolower($codepoint)
    {
        return \IntlChar::tolower($codepoint);
    }

    public function toupper($codepoint)
    {
        return \IntlChar::toupper($codepoint);
    }

    public function ord(string $utf8)
    {
        return \IntlChar::ord($utf8);
    }

    public function chr(int $codepoint)
    {
        return \IntlChar::chr($codepoint);
    }
}
