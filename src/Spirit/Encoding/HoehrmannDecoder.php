<?php

namespace Mxc\Parsec\Encoding;

class HoehrmannDecoder extends Scanner /*implements DecoderInterface*/
{

    // UTF8C and UTF8S store the information for character classification
    // and state transition information in separate tables.
    //
    // UTF8C holds the character classification
    //
    const UTF8C = [
        // This first part of the table maps bytes to character classes. The
        // number of most significant bits to mask out is encoded as well
        // to reduce the size of the transition table and create bitmasks.
        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
        1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,  9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,
        7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,  7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,
        8,8,2,2,2,2,2,2,2,2,2,2,2,2,2,2,  2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,
        10,3,3,3,3,3,3,3,3,3,3,3,3,4,3,3, 11,6,6,6,5,8,8,8,8,8,8,8,8,8,8,8,
    ];

    // UTF8S holds the state and transition information
    //
    const UTF8S = [
        // This is the second part of UTF8F defined as constant to save
        // one addition in the implementation.
         0,12,24,36,60,96,84,12,12,12,48,72, 12,12,12,12,12,12,12,12,12,12,12,12,
        12, 0,12,12,12,12,12, 0,12, 0,12,12, 12,24,12,12,12,12,12,24,12,24,12,12,
        12,12,12,12,12,12,12,24,12,12,12,12, 12,24,12,12,12,12,12,12,12,24,12,12,
        12,12,12,12,12,12,12,36,12,36,12,12, 12,36,12,12,12,12,12,36,12,36,12,12,
        12,36,12,12,12,12,12,12,12,12,12,12,
    ];

    // Constants defining the success and failure states of the automaton.
    const UTF8_ACCEPT = 0;
    const UTF8_REJECT = 12;

    protected $pos = 0;

    public function validate(string &$s, int $pos = 0, int $last = 0)
    {
        if ($last === 0) {
            $last = strlen($s);
        };
        if ($pos !== 0 && $pos >= $last) {
            return false;
        }
        $state = self::UTF8_ACCEPT;

        while ($pos < $last) {
            $state = self::UTF8S[$state + self::UTF8C[ord($s[$pos++])]];
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return $state === self::UTF8_ACCEPT;
    }

    public function getIterator(string &$s, int &$pos = 0, int &$last = 0) : \Generator
    {
        if ($last === 0) {
            $last = strlen($s);
        };
        if ($pos !== 0 && $pos >= $last) {
            return false;
        }

        $state = self::UTF8_ACCEPT;
        $codepoint = null;

        while ($pos < $last) {
            $byte = ord($s[$pos++]);
            $type = self::UTF8C[$byte];

            $codepoint = ($state !== self::UTF8_ACCEPT) ?
            ($byte & 0x3f) | ($codepoint << 6) :
            (0xff >> $type) & ($byte);

            $state = self::UTF8S[$state + $type];

            if ($state === self::UTF8_ACCEPT) {
                yield $codepoint;
            } elseif ($state === self::UTF8_REJECT) {
                $codepoint = null;
                yield $codepoint;
                $state = self::UTF8_ACCEPT;
            }
        }
        return ($state === self::UTF8_ACCEPT);
    }
}
