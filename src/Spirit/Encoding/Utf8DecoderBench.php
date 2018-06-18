<?php

namespace Mxc\Parsec\Encoding;

class Utf8DecoderBench
{

    const UTF8D = [
        // The first part of the table maps bytes to character classes that
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

    const UTF8DNA = [
        1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,  9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,
        7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,  7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,
        8,8,2,2,2,2,2,2,2,2,2,2,2,2,2,2,  2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,
        10,3,3,3,3,3,3,3,3,3,3,3,3,4,3,3, 11,6,6,6,5,8,8,8,8,8,8,8,8,8,8,8,
    ];

    const UTF8S = [
        0, 12,24,36,60,96,84,12,12,12,48,72, 12,12,12,12,12,12,12,12,12,12,12,12,
        12, 0,12,12,12,12,12, 0,12, 0,12,12, 12,24,12,12,12,12,12,24,12,24,12,12,
        12,12,12,12,12,12,12,24,12,12,12,12, 12,24,12,12,12,12,12,12,12,24,12,12,
        12,12,12,12,12,12,12,36,12,36,12,12, 12,36,12,12,12,12,12,36,12,36,12,12,
        12,36,12,12,12,12,12,12,12,12,12,12,
    ];

    const ILLEGAL_START_BYTES = [
        0x80 => 1, 0x81 => 1, 0x82 => 1, 0x83 => 1, 0x84 => 1, 0x85 => 1, 0x86 => 1, 0x87 => 1,
        0x88 => 1, 0x89 => 1, 0x8A => 1, 0x8B => 1, 0x8C => 1, 0x8D => 1, 0x8E => 1, 0x8F => 1,
        0x90 => 1, 0x91 => 1, 0x92 => 1, 0x93 => 1, 0x94 => 1, 0x95 => 1, 0x96 => 1, 0x97 => 1,
        0x98 => 1, 0x99 => 1, 0x9A => 1, 0x9B => 1, 0x9C => 1, 0x9D => 1, 0x9E => 1, 0x9F => 1,
        0xA0 => 1, 0xA1 => 1, 0xA2 => 1, 0xA3 => 1, 0xA4 => 1, 0xA5 => 1, 0xA6 => 1, 0xA7 => 1,
        0xA8 => 1, 0xA9 => 1, 0xAA => 1, 0xAB => 1, 0xAC => 1, 0xAD => 1, 0xAE => 1, 0xAF => 1,
        0xB0 => 1, 0xB1 => 1, 0xB2 => 1, 0xB3 => 1, 0xB4 => 1, 0xB5 => 1, 0xB6 => 1, 0xB7 => 1,
        0xB8 => 1, 0xB9 => 1, 0xBA => 1, 0xBB => 1, 0xBC => 1, 0xBD => 1, 0xBE => 1, 0xBF => 1,
        0xC0 => 1, 0xC1 => 1, 0xF5 => 1, 0xF6 => 1, 0xF7 => 1, 0xF8 => 1, 0xF9 => 1, 0xFA => 1,
        0xFB => 1, 0xFC => 1, 0xFE => 1, 0xFF => 1,
    ];

    const LEGAL_SECOND_BYTES = [
        0x80 => 1, 0x81 => 1, 0x82 => 1, 0x83 => 1, 0x84 => 1, 0x85 => 1, 0x86 => 1, 0x87 => 1,
        0x88 => 1, 0x89 => 1, 0x8A => 1, 0x8B => 1, 0x8C => 1, 0x8D => 1, 0x8E => 1, 0x8F => 1,
        0x90 => 1, 0x91 => 1, 0x92 => 1, 0x93 => 1, 0x94 => 1, 0x95 => 1, 0x96 => 1, 0x97 => 1,
        0x98 => 1, 0x99 => 1, 0x9A => 1, 0x9B => 1, 0x9C => 1, 0x9D => 1, 0x9E => 1, 0x9F => 1,
        0xA0 => 1, 0xA1 => 1, 0xA2 => 1, 0xA3 => 1, 0xA4 => 1, 0xA5 => 1, 0xA6 => 1, 0xA7 => 1,
        0xA8 => 1, 0xA9 => 1, 0xAA => 1, 0xAB => 1, 0xAC => 1, 0xAD => 1, 0xAE => 1, 0xAF => 1,
        0xB0 => 1, 0xB1 => 1, 0xB2 => 1, 0xB3 => 1, 0xB4 => 1, 0xB5 => 1, 0xB6 => 1, 0xB7 => 1,
        0xB8 => 1, 0xB9 => 1, 0xBA => 1, 0xBB => 1, 0xBC => 1, 0xBD => 1, 0xBE => 1, 0xBF => 1,
        0xC0 => 1, 0xC1 => 1, 0xF5 => 1, 0xF6 => 1, 0xF7 => 1, 0xF8 => 1, 0xF9 => 1, 0xFA => 1,
        0xFB => 1, 0xFC => 1, 0xFE => 1, 0xFF => 1,
    ];

    // follow  80-BF: 1000 0000 - 1011 1111 mask = 1011 1111  (if ($byte & $mask) >= $lowMask)
    // bitmask 80-8F: 1000 0000 - 1000 1111 mask = 1000 1111  (if ($byte ^ 0b1000 << 4) === $byte & 0xF)
    // bitmask 80-9F: 1000 0000 - 1001 1111 mask = 1001 1111  (if ($byte ^ 0b1001 << 4) === $byte & 0xF)
    // bitmask A0-BF: 1010 0000 - 1011 1111 mask = 1011 1111  (if ($byte ^ 0b1011 << 4) === $byte & 0xF)
    // bitmaks 90-BF: 1001 0000 - 1011 1111 mask = ???

    // lownibble mask = 1111

    protected $VALID_CONTINUATION_BYTES = [
        0x00 => ['expect' => 1, 'min' => 0x00, 'max' => 0xFD],
        0xE0 => ['expect' => 2, 'min' => 0xA0, 'max' => 0xBF],
        0xE1 => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xE2 => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xE3 => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xE4 => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xE5 => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xE6 => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xE7 => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xE8 => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xE9 => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xEA => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xEB => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xEC => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xED => ['expect' => 2, 'min' => 0x80, 'max' => 0x9F],
        0xEE => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xEF => ['expect' => 2, 'min' => 0x00, 'max' => 0xFD],
        0xF0 => ['expect' => 3, 'min' => 0x90, 'max' => 0xBF],
        0xF4 => ['expect' => 3, 'min' => 0x80, 'max' => 0x8F],
    ];

    const UTF8_ACCEPT = 0;
    const UTF8_REJECT = 12;

    protected function seqout(array $ar)
    {
        foreach ($ar as $val) {
            printf("0x%02X ", $val);
        }
        print(PHP_EOL);
    }

    public function utf8DecodeCmp(string&$s)
    {
        $l = strlen($s);
        $expect = 0;
        $min = 0;
        $max = 0;
        for ($i = 0; $i < $l;) {
            $byte = ord($s[$i++]);
            if ($byte < 128) {
                continue;
            } elseif (isset(self::ILLEGAL_START_BYTES[$byte])) {
                return false;
            } elseif (isset($this->VALID_CONTINUATION_BYTES[$byte])) {
                extract($this->VALID_CONTINUATION_BYTES[$byte]);
            } else {
                extract($this->VALID_CONTINUATION_BYTES[0]);
            }
            $check = true;
            while (($i < $l) && $expect--) {
                $byte = ord($s[$i++]);
                if (($byte >> 6) ^ 0b10) {
                    return false;
                }
                if ($check) {
                    $check = false;
                    if (($byte < $min) || ($byte > $max)) {
                        return false;
                    }
                }
            }
            $expect = 0;
        }
        return true;
    }

    public function utf8DecodeComp(string &$s)
    {
        $l = strlen($s);
        $expect = 0;
        $state = self::UTF8_ACCEPT;
        // we start at a valid start point
        for ($i = 0; $i < $l;) {
            $sequence = [];
            $byte = ord($s[$i++]);
            $sequence[] = $byte;
            // $byte < 0x80 (
            if (! ($byte >> 7)) {
                $codepoint = $byte;
                continue;
            // $byte >= 0xC0 && $byte <= 0xDF
            } elseif (! (($byte >> 5) ^ 0b110)) {
                if ($byte < 0xC2) {
                    // start byte of two byte sequence encoding
                    // 0x00 - 0x7F -> invalid
                    return false;
                }
                $expect = 1;
            // $byte >= E0 && $byte <= 0xEF
            } elseif (! (($byte >> 4) ^ 0b1110)) {
                $codepoint = (0xFF >> 4) & $byte;
                if ($byte === 0xE0) {
                    $state = 2;
                } elseif ($byte === 0xED) {
                    $state = 3;
                }
                $expect = 2;
            // $byte >= 0xF0 && $byte <= 0xF7
            } elseif (! (($byte >> 3) ^ 0b11110)) {
                if (($byte === 0xF1) || ($byte === 0xf2)) {
                    // U+40000 ... U+7FFFF
                    // currently no valid characters in this area
                    return false;
                } elseif ($byte === 0xf0) {
                    $state = 1;
                } elseif ($byte === 0xf4) {
                    $state = 4;
                }
                $expect = 3;
                $codepoint = (0xFF >> 3) & $byte;
            } else {
                // 0xF8 - 0xFB : start of five byte sequence
                // invalid (see RFC 3629)

                // 0xFC - 0xFD : start of six byte sequence
                // invalid (see RFC 3629)

                // 0xFE - 0xFF: Invalid by original UTF-8 spec
                return false;
            }

            while ($i < $l && $expect > 0) {
                $byte = ord($s[$i++]);
                $sequence[] = $byte;
                if (($byte >> 6) ^ 0b10) {
                    return false;
                }
                if ($state === 1) {
                    if (($byte < 0x90) || ($byte >= 0xC0)) {
                        return false;
                    }
                } elseif (($state === 2) && ($byte >= 0x80) && ($byte <= 0x9f)) {
                    return false;
                } elseif (($state === 3) && ($byte >= 0xA0) && ($byte <= 0xBF)) {
                    return false;
                } elseif (($state === 4) && (($byte < 0x80) || ($byte > 0x8F))) {
                    return false;
                }
                $state = self::UTF8_ACCEPT;
                $codepoint = ($byte & 0x3F) + ($codepoint << 6);
                $expect--;
            }
        }

        $this->seqout($sequence);
        return $codepoint;
    }

    public function decodeNa(&$state, &$codepoint, $byte)
    {
        if ($byte < 0x80) {
            $codepoint = $byte;
            return self::UTF8S[$state];
        } else {
            $type = self::UTF8DNA[$byte - 0x80];
            $codepoint = ($state !== self::UTF8_ACCEPT) ?
            ($byte & 0x3f) + ($codepoint << 6) :
            (0xff >> $type) & $byte;
            return self::UTF8S[$state + $type];
        }
    }

    public function decode(&$state, &$codepoint, $byte)
    {
        $type = self::UTF8D[$byte];
        $codepoint = ($state !== self::UTF8_ACCEPT) ?
            ($byte & 0x3f) + ($codepoint << 6) :
            (0xff >> $type) & $byte;
        return self::UTF8S[$state + $type];
    }

    public function printCodePoints(string $s)
    {
        $result = '';
        $state = self::UTF8_ACCEPT;
        for ($i = 0; $i < strlen($s);) {
            switch ($state = $this->decodeNa($state, $codepoint, ord($s[$i++]))) {
                case self::UTF8_ACCEPT:
                    $result .= \IntlChar::chr($codepoint);
                    break;
                case self::UTF8_REJECT:
                    $result .= "\u{FFFD}";
                    break;
            }
        }
        return $result;
    }

    public function isUtf8(string &$s)
    {
        $l = strlen($s);
        $state = self::UTF8_ACCEPT;
        for ($i = 0; $i < $l;) {
            $state = $this->decode($state, $codepoint, ord($s[$i++]));
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return true;
    }

    public function isUtf8Na(string &$s)
    {
        $l = strlen($s);
        $state = self::UTF8_ACCEPT;
        for ($i = 0; $i < $l;) {
            $state = $this->decodeNa($state, $codepoint, ord($s[$i++]));
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return true;
    }

    public function isUtf8V1(string &$s)
    {
        $l = strlen($s);
        $state = self::UTF8_ACCEPT;
        for ($i = 0; $i < $l;) {
            $byte = ord($s[$i++]);
            $type = self::UTF8D[$byte];
            $codepoint = ($state !== self::UTF8_ACCEPT) ?
                ($byte & 0x3f) + ($codepoint << 6) :
                (0xff >> $type) & $byte;
            $state = self::UTF8S[$state + $type];
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return true;
    }

    public function isUtf8V2(string &$s)
    {
        $l = strlen($s);
        $state = self::UTF8_ACCEPT;
        for ($i = 0; $i < $l;) {
            $type = self::UTF8D[ord($s[$i++])];
            $state = self::UTF8S[$state + $type];
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return true;
    }

    public function isUtf8V2Na(string &$s)
    {
        $l = strlen($s);
        $state = self::UTF8_ACCEPT;
        for ($i = 0; $i < $l;) {
            $byte = ord($s[$i++]);
            $state = ($byte & 0x80) ? self::UTF8S[$state + self::UTF8DNA[$byte & 0x7F]] : self::UTF8S[$state];
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return true;
    }
}

function scanCode(string &$s)
{
    $bytestream = new class() implements \IteratorAggregate
    {
        /** @var string */
        private $string = "";

        public function setString(string $string)
        {
            $this->string = $string;
        }

        public function getIterator() : \Generator
        {
            $string = $this->string;
            $length = strlen($string);
            for ($i = 0; $i < $length; $i++) {
                yield $string[$i];
            }
        }
    };

    $bytestream->setString($s);
    $expect = 0;

    foreach ($bytestream as $ch) {
        $byte = ord($ch);
        if ($expect == 0) {
            $codepoint = $ch;
            if (! ($byte >> 7)) {
                continue;
            } elseif (! (($byte >> 5) ^ 0b110)) {
                $expect = 1;
            } elseif (! (($byte >> 4) ^ 0b1110)) {
                $expect = 2;
            } elseif (! (($byte >> 3) ^ 0b11110)) {
                $expect = 3;
            } else {
                return false;
            }
        } else {
            if (($byte >> 6) ^ 0b10) {
                return false;
            } else {
                $expect--;
                $codepoint .= $ch;
            }
        }
        if ($expect === 0 && \IntlChar::chr($codepoint) === null) {
            return false;
        }
    }
    return true;
}

function scanCode2(string &$s) : bool
{
    $l = strlen($s);
    $expect = 0;
    for ($i = 0; $i < $l;) {
        $codepoint = $s[$i++];
        $byte = ord($codepoint);

        if (! ($byte >> 7)) {
            continue;
        } elseif (! (($byte >> 5) ^ 0b110)) {
            $expect = 1;
        } elseif (! (($byte >> 4) ^ 0b1110)) {
            $expect = 2;
        } elseif (! (($byte >> 3) ^ 0b11110)) {
            $expect = 3;
        } else {
            return false;
        }

        while ($i < $l && $expect > 0) {
            $byte = $s[$i++];
            if ((ord($byte) >> 6) ^ 0b10) {
                return false;
            }
            $codepoint .= $byte;
            $expect--;
        }
        if (\IntlChar::chr($codepoint) === null) {
            return false;
        }
    }

    return true;
}

function scanCode3(string &$s) : bool
{
    $l = strlen($s);
    $expect = 0;
    for ($i = 0; $i < $l;) {
        $codepoint = $s[$i++];
        $byte = ord($codepoint);
        //         printf("%08s : ", decbin($byte));

        if (! ($byte & 0b10000000)) {
            //             print("Single byte codepoint.\n");
            continue;
        } elseif (($byte & 0b11100000) === 0b11000000) {
            $expect = 1;
            //             print("Expect 1\n");
        } elseif (($byte & 0b11110000) === 0b11100000) {
            $expect = 2;
            //             print("Expect 2\n");
        } elseif (($byte & 0b11111000) === 0b11110000) {
            $expect = 3;
            //             print("Expect 3\n");
        } else {
            //             print("Bitmask: No valid codepoint.\n");
            return false;
        }

        while ($i < $l && $expect > 0) {
            $c = $s[$i++];
            $byte = ord($c);
            if (! ($byte & 0b10000000)) {
                //                 printf("Invalid continuation byte [ %08s ].\n", decbin($byte));
                return false;
            }
            //             printf("Continuation [ %08s ] added.\n", decbin($byte));
            $codepoint .= $c;
            $expect--;
        }
        if (\IntlChar::chr($codepoint) == null) {
            //             print("IntlChar: No valid codepoint.\n");
            return false;
        }
    }
    //     print("String is utf-8.\n");
    return true;
}

function detectUtf8($string)
{
    return preg_match(
        '%(?:
        [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )+%xs',
        $string
    );
}

function intlChar(&$string)
{
    $l = strlen($string);
    for ($i = 0; $i < $l;) {
        for ($k = 1; $k < 5 && $k + $i - 1 < $l;) {
            $c = substr($string, $i, $k);
            $c = \IntlChar::ord($c);
            if ($c) {
                $i += $k;
                goto next;
            }
            $k++;
        }
        return false;
        next:
            ;
    };
    return true;
}

function intlChar2(&$string)
{
    $l = strlen($string);
    $l0 = $l - 4;
    $i = 0;
    while ($i < $l0) {
        $t = $string[$i++];
        if (\IntlChar::ord($t)) {
            goto next;
        }

        $t .= $string[$i++];
        if (\IntlChar::ord($t)) {
            goto next;
        }

        $t .= $string[$i++];
        if (\IntlChar::ord($t)) {
            goto next;
        }

        $t .= $string[$i++];
        if (\IntlChar::ord($t)) {
            goto next;
        } else {
            return false;
        }
        next:
            ;
    };
    // explicitly check len for each byte
    // only for the last 4 bytes
    while ($i < $l) {
        $t = $string[$i++];

        if (\IntlChar::ord($t)) {
            goto next2;
        }
        if ($i < l) {
            $t .= $string[$i++];
            if (\IntlChar::ord($t)) {
                goto next2;
            }
        }
        if ($i < l) {
            $t .= $string[$i++];
            if (\IntlChar::ord($t)) {
                goto next2;
            }
        }
        if ($i < l) {
            $t .= $string[$i++];
            if (\IntlChar::ord($t)) {
                goto next2;
            }
        }
        if ($i < $l) {
            $t .= $string[$i++];
            if (\IntlChar::ord($t)) {
                goto next2;
            }
        }
        return false;
        next2:
            ;
    };
    return true;
}

$ultimate = file_get_contents(__DIR__ . '/Asset/UTF-8-demo.txt');

$tests = [
    [
        "\x81",
        "Line 1: Ill-formed UTF-8 sequence 0x81."
    ],
    [
        "\xC0\xAF",
        "Line 1: Ill-formed UTF-8 sequence 0xC0 0xAF."
    ],
    [
        "\xE0\x9F\x80",
        "Line 1: Ill-formed UTF-8 sequence 0xE0 0x9F 0x80."
    ],
    [
        "\xED\xA0\x80",
        "Line 1: Ill-formed UTF-8 sequence 0xED 0xA0 0x80."
    ],
    [
        "\xF0\x8F\x80\x80",
        "Line 1: Ill-formed UTF-8 sequence 0xF0 0x8F 0x80 0x80."
    ],
    [
        "\xF4\xA0\x80\x80",
        "Line 1: Ill-formed UTF-8 sequence 0xF4 0xA0 0x80 0x80."
    ],
    [
        "\xC0\x80",
        "Line 1: Ill-formed UTF-8 sequence 0xC0 0x80."
    ],
    [
        "\xE0\x00",
        "Line 1: Ill-formed UTF-8 sequence 0xE0."
    ]
];

$ud = new Utf8DecoderBench();
$test = '';
for ($i = 0; $i < 1000; $i++) {
    $test .= $ultimate;
}


$t = -microtime(true);
$result = $ud->utf8DecodeCmp($test);
$t += microtime(true);
print('utf8DecodeCmp($test) Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = intlchar($test);
$t += microtime(true);
print('intlchar($test) Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = intlchar2($test);
$t += microtime(true);
print('intlchar($test) Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = preg_match('//u', $test);
$t += microtime(true);
print('preg_match(\'//u\', $test) Time: ' . $t . PHP_EOL);
assert($result === 1);

$t = -microtime(true);
$result = preg_match('!!u', $test);
$t += microtime(true);
print('preg_match(\'!!u\', $test) Time: ' . $t . PHP_EOL);
assert($result === 1);

$t = -microtime(true);
$result = mb_check_encoding($test, 'UTF-8');
$t += microtime(true);
print('mb_check_encoding($test, \'UTF-8\') Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = json_encode($test) !== false;
$t += microtime(true);
print('json_encode($test) Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = $ud->isUtf8($test);
$t += microtime(true);
print('isUtf8 Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = $ud->isUtf8Na($test);
$t += microtime(true);
print('isUtf8Na with codepoint Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = $ud->isUtf8V1($test);
$t += microtime(true);
print('isUtf8 with codepoint Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = $ud->isUtf8V2($test);
$t += microtime(true);
print('isUtf8 w/o codepoint Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = $ud->isUtf8V2Na($test);
$t += microtime(true);
print('isUtf8Na w/o codepoint Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = detectUtf8($test);
print('detectUtf8 Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = scanCode($test);
$t += microtime(true);
print('scanCode Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = scanCode2($test);
$t += microtime(true);
print('scanCode2 Time: ' . $t . PHP_EOL);
assert($result === true);

$t = -microtime(true);
$result = scanCode3($test);
$t += microtime(true);
print('scanCode3 Time: ' . $t . PHP_EOL);
assert($result === true);

foreach ($tests as $test) {
    print('TEST----------'.PHP_EOL);
    assert($ud->utf8DecodeCmp($test[0]) === false);
    assert(intlchar($test[0]) === false);
    assert(intlchar2($test[0]) === false);
    assert(detectUtf8($test[0]) == false);
    assert($ud->isUtf8($test[0]) === false);
    assert($ud->isUtf8Na($test[0]) === false);
    assert($ud->isUtf8V2($test[0]) === false);
    assert($ud->isUtf8V2Na($test[0]) === false);
    assert(preg_match('//u', $test[0]) == false);
    assert(preg_match('!!u', $test[0]) == false);
    assert(mb_check_encoding($test[0], 'UTF-8') === false);
    assert(json_encode($test[0]) === false);
    assert(scanCode($test[0]) === false);
    assert(scanCode2($test[0]) === false);
    assert(scanCode3($test[0]) === false);
}
