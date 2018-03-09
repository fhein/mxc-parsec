<?php

namespace Mxc\Parsec\Encoding;

class Utf8Encoder
{

    protected $strict;
    /**
     * Construct an Utf8Encoder object
     *
     * @param bool $strict  true: accept only valid unicode codepoints
     *                      false: accept any value < 0x80000000
     *                      If $strict == true, the optional parameters
     *                      of utf8Encode are disabled and default to
     *                      to their default values 0
     */
    public function __construct(bool $strict = false)
    {
        $this->strict = $strict;
    }

    /**
     * encode
     *
     * Create readable string representations of UTF8 encoding of codepoints
     * Specifiy length to create overlong sequences.
     * Specifiy cut to cut off bytes at the end of the string creating a result
     * with bytes missing.
     *
     * @param number $c         codepoint
     * @param number $len       target length
     * @param number $cut       how many bytes to cut at the end
     * @return boolean|string   result or false
     */
    public function encode(int $b, int $len = 0, int $cut = 0)
    {
        if ($b > 0x7FFFFFFF) {
            return false;
        }

        if ($b < 0x80) {
            $min = 1;
        } elseif ($b < 0x0800) {
            $min = 2;
        } elseif ($b < 0x010000) {
            $min = 3;
        } elseif ($b < 0x200000) {
            $min = 4;
        } elseif ($b < 0x4000000) {
            $min = 5;
        } else {
            $min = 6;
        }

        $len = $len !== 0 ? $len : $min;

        if ($len > 6 || $len < $min || ($cut >= $len && $cut !== 0) || $len < 0 || $cut < 0) {
            return false;
        }

        if ($len === 1) {
            $result = chr($b);
        } elseif ($len === 2) {
            $result = chr($b >> 6 & 0x1F | 0xC0)
            . chr($b >> 0 & 0x3F | 0x80);
        } elseif ($len === 3) {
            $result = chr($b >> 12 & 0x0F | 0xE0)
            . chr($b >> 6 & 0x3F | 0x80)
            . chr($b >> 0 & 0x3F | 0x80);
        } elseif ($len === 4) {
            $result = chr($b >> 18 & 0x07 | 0xF0)
            . chr($b >> 12 & 0x3F | 0x80)
            . chr($b >> 6 & 0x3F | 0x80)
            . chr($b >> 0 & 0x3F | 0x80);
        } elseif ($len === 5) {
            $result = chr($b >> 24 & 0x03 | 0xF8)
            . chr($b >> 18 & 0x3F | 0x80)
            . chr($b >> 12 & 0x3F | 0x80)
            . chr($b >> 6 & 0x3F | 0x80)
            . chr($b >> 0 & 0x3F | 0x80);
        } elseif ($len === 6) {
            $result = chr($b >> 30 & 0x01 | 0xFC)
            . chr($b >> 24 & 0x3F | 0x80)
            . chr($b >> 18 & 0x3F | 0x80)
            . chr($b >> 12 & 0x3F | 0x80)
            . chr($b >> 6 & 0x3F | 0x80)
            . chr($b >> 0 & 0x3F | 0x80);
        }
        if ($cut > 0) {
            $len = ($len - $cut);
            $result = substr($result, 0, $len);
        }
        return $result;
    }

    public function hexString($s) : string
    {
        if (is_string($s)) {
            $len = strlen($s);
            $result = '"';
            for ($i = 0; $i < $len;) {
                $result .= sprintf("\\x%02X", ord($s[$i++]));
            }
            $result .= '"';
            return $result;
        } elseif (is_int($s)) {
            $sh = $s;
            $len = 2;
            while ($sh = $sh >> 8) {
                $len *= 2;
            }
            $result .= sprintf("0x%0${len}X", $s);
            return $result;
        }
    }
}
