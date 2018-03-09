<?php

namespace Mxc\Parsec\Encoding;

use Mxc\Parsec\Exception\InvalidArgumentException;
use IntlChar;

class Utf8Decoder extends Scanner /*implements DecoderInterface */
{

    public function __construct(string $s = '', $first = null, $last = null, bool $noCase = false, $binary = false)
    {
        $this->data = $s;

        $first = $first?: 0;
        $last = $last?: strlen($s);
        $this->noCase = $noCase;
        $this->binary = $binary;

        if (($last > strlen($s)) || ($last < 0) || ($first < 0) || (($first !== 0) && ($first >= $last))) {
            throw new InvalidArgumentException('Utf8Decoder: Invalid arguments.');
        }

        $this->first = $first;
        $this->last = $last;
    }

    public function currentCase()
    {
        return $this->noCase ? IntlChar::tolower($this->cache[$this->first]) : $this->cache[$this->first];
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

    public function valid()
    {
        return $this->first < $this->last;
    }

    public function next()
    {
        $this->first += $this->lastSize;
    }

    public function key()
    {
        return $this->first;
    }

    public function parseString($string, &$attr)
    {
        $attr = '';
        while ($string->valid()) {
            if ($this->noCase) {
                $s = IntlChar::tolower($string->current());
                $c = IntlChar::tolower($this->current());
            } else {
                $s = $string->current();
                $c = $this->current();
            }
            if ($s !== $c) {
                $attr = null;
                break;
            }
            $attr .= $this->current();
            $string->next();
            $this->next();
        }
        return (! $string->valid());
    }

    public function currentNoCase()
    {
        return $this->noCase ? IntlChar::tolower($this->current()) : $this->current();
    }

    public function isChar(&$t, &$f)
    {
        $t .= $this->data[$f++];
        $codepoint = IntlChar::ord($t);
        if ($codepoint !== null) {
            $this->lastSize = strlen($t);
            return true;
        }
        return false;
    }

    public function current()
    {
        if ($this->first < $this->invalidCache && isset($this->cache[$this->first])) {
            print ("Request served from cache. ");
            return $this->cache[$this->first];
        }
        $l = $this->last;
        $l0 = $l - 4;
        $f = $this->first;
        if ($f < $l0) {
            if ($this->binary) {
                $result = substr($this->data, $f, $this->binarySize);
                $this->cache[$this->first] = $result;
                $this->lastSize = $this->binarySize;
                return $result;
            }

            $t = '';
            for ($idx = $this->first; $idx < $this->first + 4;) {
                if ($this->isChar($t, $idx)) {
                    $this->cache[$this->first] = $t;
                    return $t;
                }
            }
            // this happens in utf8 mode if there is no valid
            // character at the buffer position
            $this->cache[$this->first] = chr($this->data[$this->first]);
            $this->lastSize = 1;
            return $this->cache;
        }

        // check index validity
        // only for the last 4 bytes
        $f = $this->first;

        if ($f < $l) {
            if ($this->binary) {
                $result = substr($this->data, $f, $this->binarySize);
                $this->cache[$this->first] = $result;
                $this->lastSize = $this->binarySize;
                return $result;
            }

            $t = '';
            if ($this->isChar($t, $f)) {
                $this->cache[$this->first] = $t;
                return $t;
            }
        }

        for ($idx = $this->first + 1; $idx < $this->first + 4;) {
            if ($idx < $l && $this->isChar($t, $idx)) {
                $this->cache[$this->first] = $t;
                return $t;
            }
        }
        // this happens in utf8 mode if there is no valid
        // character at the buffer position
        $result = chr($this->data[$this->first]);
        $this->cache[$this->first] = $result;
        $this->lastSize = 1;
        return $result;
    }
}
