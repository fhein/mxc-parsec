<?php

namespace Mxc\Benchmark\Parsec;

class Utf8Decoder
{

    // list of members to benchmark
    const BENCHMARK = [
        'validatePregmatch',
        'validateMbCheckEncoding',
        'validateJsonEncode',
//         'decodeMbStringNaive',
        'decodeMbStringShort',
        'decodeIntlchar',
    ];

    public function validatePregmatch(&$s)
    {
        return preg_match('!!u', $s) === 1;
    }

    public function validateMbCheckEncoding(&$s)
    {
        return mb_check_encoding($s, 'UTF-8');
    }

    public function validateJsonEncode(&$s)
    {
        return json_encode($s) !== false;
    }

    public function decodeMbStringNaive(&$s)
    {
        $save = mb_internal_encoding();
        mb_internal_encoding('UTF-8');
        $len = mb_strlen($s, 'UTF-8');
        for ($i = 0; $i < $len; $i++) {
            $c = mb_substr($s, $i, 1);
        }

        mb_internal_encoding($save);
    }

    public function decodeMbStringShort(&$s)
    {
        $save = mb_internal_encoding();
        mb_internal_encoding('UTF-8');
        $s = "\x80";
        $len = strlen($s);
        for ($i = 0; $i < $len;) {
            $substr = mb_substr($s, $i, 1);
            var_dump($substr);
            $i += strlen($substr);
        }

        mb_internal_encoding($save);
    }

    public function decodeIntlchar(&$s)
    {
        $pos = 0;
        $last = strlen($s);

        $l0 = $last - 4;
        while ($pos < $l0) {
            $t = $s[$pos++];
            $codepoint = \IntlChar::ord($t);
            if ($codepoint !== null) {
                // success in t
                continue;
            }

            $t .= $s[$pos++];
            $codepoint = \IntlChar::ord($t);
            if ($codepoint !== null) {
                // success in t
                continue;
            }

            $t .= $s[$pos++];
            $codepoint = \IntlChar::ord($t);
            if ($codepoint !== null) {
                // success in t
                continue;
            }

            $t .= $s[$pos++];
            $codepoint = \IntlChar::ord($t);
            if ($codepoint !== null) {
                // success in t
                continue;
            } else {
                return false;
            }
        }
        // explicitly check index validity
        // only for the last 4 bytes
        while ($pos < $last) {
            $t = $s[$pos++];
            $codepoint = \IntlChar::ord($t);
            if ($codepoint !== null) {
                // success in t
                continue;
            }
            if ($pos < $last) {
                $t .= $s[$pos++];
                $codepoint = \IntlChar::ord($t);
                if ($codepoint !== null) {
                    // success in t
                    continue;
                }
            }
            if ($pos < $last) {
                $t .= $s[$pos++];
                $codepoint = \IntlChar::ord($t);
                if ($codepoint !== null) {
                    // success in t
                    continue;
                }
            }
            if ($pos < $last) {
                $t .= $s[$pos++];
                $codepoint = \IntlChar::ord($t);
                if ($codepoint !== null) {
                    // success in t
                    continue;
                }
            }
            if ($pos < $last) {
                $t .= $s[$pos++];
                $codepoint = \IntlChar::ord($t);
                if ($codepoint !== null) {
                    // success in t
                    continue;
                }
            }
            return false;
        }
        return true;
    }
}
