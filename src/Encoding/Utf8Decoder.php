<?php

namespace Mxc\Parsec\Encoding;

use Mxc\Parsec\Exception\InvalidArgumentException;

class Utf8Decoder extends Scanner /*implements DecoderInterface */
{
    /**
     * Check if the substring of beginning at $pos
     * @param string $s
     * @param int $pos
     * @param int $last
     * @return boolean
     */

    public function validate(string $s, int $first = 0, int $len = 0)
    {
        if ($first === 0 && $len === 0) {
            return 1 === preg_match('!!u', $s);
        } elseif ($first >= 0 && $len > 0) {
            $s = substr($s, $first, $len);
            return 1 === preg_match('!!u', $s);
        }
        throw new InvalidArgumentException('Invalid arguments supplied to validate.');
    }

    /**
     * Appends the byte at current buffer position to the current codepoint
     * evaluation buffer and advances buffer position. If the evaluation buffer
     * represents a valid codepoint, the size of the codepoint in bytes gets
     * stored and true gets returned. Otherwise returns false.
     *
     *
     * @param string $t     current codepoint evaluation buffer reference
     * @param unknown $f    current iterator position
     * @return boolean
     */
    private function isChar(&$t, &$f)
    {
        $t .= $this->data[$f++];
        $codepoint = $this->ord($t);
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
