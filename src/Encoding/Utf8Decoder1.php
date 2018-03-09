<?php

namespace Mxc\Parsec\Encoding;

use Mxc\Parsec\Exception\InvalidArgumentException;

class Utf8Decoder1 extends Scanner /*implements DecoderInterface */
{

    public function __construct(string $s, $first = null, $last = null, bool $lowerCase = false, $binary = false)
    {
        $this->data = $s;

        $first = $first?: 0;
        $last = $last?: strlen($s);
        $this->lowerCase = $lowerCase;
        $this->binary = $binary;


        if (($last > strlen($s)) || ($last < 0) || ($first < 0) || (($first !== 0) && ($first >= $last))) {
            throw new InvalidArgumentException('Utf8Decoder: Invalid arguments.');
        }

        $this->first = $first;
        $this->last = $last;
        $this->lowerCase = $lowerCase;

        $this->iterator = $this->generator($this->data, $this->first, $this->last, $this->lowerCase, $this->binary);
    }

    public function validate(string $s, int $pos = 0, int $last = 0)
    {
        if ($last === 0) {
            $last = strlen($s);
        } elseif ($last < strlen($s)) {
            $sub = true;
        }
        if ($pos !== 0 && $pos >= $last) {
            return false;
        }
        if ($sub || $pos != 0) {
            $s = substr($s, $pos, $last);
        }
        return 1 === preg_match('!!u', $s);
    }

    public function generator(string &$s, int &$pos, int &$last, &$lowercase, &$binary) : \Generator
    {

        $l0 = $last - 4;
        while ($pos < $l0) {
            if ($binary) {
                yield $s[$pos++];
                continue;
            }

            $t = $s[$pos++];
            $codepoint = \IntlChar::ord($t);
            if ($codepoint !== null) {
                yield $lowercase ? \IntlChar::tolower($t) : $t;
                continue;
            }

            $t .= $s[$pos++];
            $codepoint = \IntlChar::ord($t);
            if ($codepoint !== null) {
                yield $lowercase ? \IntlChar::tolower($t) : $t;
                continue;
            }

            $t .= $s[$pos++];
            $codepoint = \IntlChar::ord($t);
            if ($codepoint !== null) {
                yield $lowercase ? \IntlChar::tolower($t) : $t;
                continue;
            }

            $t .= $s[$pos++];
            $codepoint = \IntlChar::ord($t);
            if ($codepoint !== null) {
                yield $lowercase ? \IntlChar::tolower($t) : $t;
                continue;
            }
            print ('Yield null.'.PHP_EOL);
            // resync on next byte
            $pos -= 3;
            continue;
        }
        // explicitly check index validity
        // only for the last 4 bytes
        while ($pos < $last) {
            $t = $s[$pos++];
            $codepoint = \IntlChar::ord($t);
            if ($codepoint !== null) {
                yield $lowercase ? \IntlChar::tolower($t) : $t;
                continue;
            }

            if ($pos < $last) {
                $t .= $s[$pos++];
                $codepoint = \IntlChar::ord($t);
                if ($codepoint !== null) {
                    yield $lowercase ? \IntlChar::tolower($t) : $t;
                    continue;
                }
            }

            if ($pos < $last) {
                $t .= $s[$pos++];
                $codepoint = \IntlChar::ord($t);
                if ($codepoint !== null) {
                    yield $lowercase ? \IntlChar::tolower($t) : $t;
                    continue;
                }
            }

            if ($pos < $last) {
                $t .= $s[$pos++];
                $codepoint = \IntlChar::ord($t);
                if ($codepoint !== null) {
                    yield $lowercase ? \IntlChar::tolower($t) : $t;
                    continue;
                }
            }
            print ('Yield null.'.PHP_EOL);
            yield $lowercase ? \IntlChar::tolower($t) : $t;
            return false;
        }
        return true;
    }
}
