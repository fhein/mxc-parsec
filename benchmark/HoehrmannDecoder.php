<?php
// Copyright (c) 2008-2010 Bjoern Hoehrmann <bjoern@hoehrmann.de>
// See http://bjoern.hoehrmann.de/utf-8/decoder/dfa/ for details.

namespace Mxc\Benchmark\Parsec;

class HoehrmannDecoder
{

    // Constant defining the failure state of the automaton
    // for the algorithms <ext> = org (see below for details).
    const UTF8_REJECT_ORG = 1;

    // This is the class and state map as originally introduced by Björn
    // Hoehrmann 2009. Used by algorithm methods <ext> = org.
    //
    const UTF8O = [
        // The first part of the table maps bytes to character classes
        // to reduce the size of the transition table and create bitmasks.
        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0, // 00..1f
        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0, // 20..3f
        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0, // 40..5f
        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0, // 60..7f
        1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9, // 80..9f
        7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7, // a0..bf
        8,8,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2, // c0..df
        0xa,0x3,0x3,0x3,0x3,0x3,0x3,0x3,0x3,0x3,0x3,0x3,0x3,0x4,0x3,0x3, // e0..ef
        0xb,0x6,0x6,0x6,0x5,0x8,0x8,0x8,0x8,0x8,0x8,0x8,0x8,0x8,0x8,0x8, // f0..ff
        // The second part is a transition table that maps a combination
        // of a state of the automaton and a character class to a state.
        0x0,0x1,0x2,0x3,0x5,0x8,0x7,0x1,0x1,0x1,0x4,0x6,0x1,0x1,0x1,0x1, // s0..s0
        1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1,0,1,0,1,1,1,1,1,1, // s1..s2
        1,2,1,1,1,1,1,2,1,2,1,1,1,1,1,1,1,1,1,1,1,1,1,2,1,1,1,1,1,1,1,1, // s3..s4
        1,2,1,1,1,1,1,1,1,2,1,1,1,1,1,1,1,1,1,1,1,1,1,3,1,3,1,1,1,1,1,1, // s5..s6
        1,3,1,1,1,1,1,3,1,3,1,1,1,1,1,1,1,3,1,1,1,1,1,1,1,1,1,1,1,1,1,1, // s7..s8
    ];

    // Constants defining the success and failure states of the automaton.
    // Used by all algorithms but <ext> = org (see below).
    const UTF8_ACCEPT = 0;
    const UTF8_REJECT = 12;

    // This is an enhanced version version of UTF8O. Rich Felker pointed
    // out that the state values in the transition table can be pre-
    // multiplied with 16 which saves a shift instruction for every
    // byte. By doing so, filler values previously in the table could
    // be thrown away making the table 36 bytes shorter, too.
    // Used by algorithms <ext> = enh (see below).
    //
    const UTF8F = [
        // The first part of the table maps bytes to character classes
        // to reduce the size of the transition table and create bitmasks.
        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
        1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,  9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,
        7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,  7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,
        8,8,2,2,2,2,2,2,2,2,2,2,2,2,2,2,  2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,
        10,3,3,3,3,3,3,3,3,3,3,3,3,4,3,3, 11,6,6,6,5,8,8,8,8,8,8,8,8,8,8,8,
        // The second part is a transition table that maps a combination
        // of a state of the automaton and a character class to a state.
        0,12,24,36,60,96,84,12,12,12,48,72, 12,12,12,12,12,12,12,12,12,12,12,12,
        12, 0,12,12,12,12,12, 0,12, 0,12,12, 12,24,12,12,12,12,12,24,12,24,12,12,
        12,12,12,12,12,12,12,24,12,12,12,12, 12,24,12,12,12,12,12,12,12,24,12,12,
        12,12,12,12,12,12,12,36,12,36,12,12, 12,36,12,12,12,12,12,36,12,36,12,12,
        12,36,12,12,12,12,12,12,12,12,12,12,

    ];

    // UTF8C and UTF8S store the information from UTF8F in separate tables.
    // UTF8C holds the character classification (first part of UTF8F).
    //
    // This a PHP related optimization. This saves one addition for every
    // byte. Used by algorithms <ext> = enhSplit (see below).
    //
    const UTF8C = [
        // This first part of the table maps bytes to character classes that
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

    // UTF8C and UTF8S store the information from UTF8F in separate tables.
    // UTF8C holds the character classification (first part of UTF8F).
    //
    // UTF8S holds the state and transition information (second part of UTF8F).
    // Used by algorithms <ext> = enhSplit, enhSplitNa (see below).
    //
    const UTF8S = [
        // This is the second part of UTF8F defined as constant to save
        // one addition in the code.
        0,12,24,36,60,96,84,12,12,12,48,72, 12,12,12,12,12,12,12,12,12,12,12,12,
        12, 0,12,12,12,12,12, 0,12, 0,12,12, 12,24,12,12,12,12,12,24,12,24,12,12,
        12,12,12,12,12,12,12,24,12,12,12,12, 12,24,12,12,12,12,12,12,12,24,12,12,
        12,12,12,12,12,12,12,36,12,36,12,12, 12,36,12,12,12,12,12,36,12,36,12,12,
        12,36,12,12,12,12,12,12,12,12,12,12,
    ];

    // UTF8CNA is the first part of UTF8F with the ASCII code positions
    // stripped. This reduces the table sides but introduces an additional
    // decision step in the algorithms.
    //
    // Used by algorithms <ext> = enhSplitNa (see below).
    //
    const UTF8CNA = [
        // This is the first part of UTF8F with ASCII code positions strip
        // Array keys of UTF8F are kept to avoid subtractions in the code
        0x80 => 1,
          1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,  9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,
        7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,  7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,
        8,8,2,2,2,2,2,2,2,2,2,2,2,2,2,2,  2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,
        10,3,3,3,3,3,3,3,3,3,3,3,3,4,3,3, 11,6,6,6,5,8,8,8,8,8,8,8,8,8,8,8,
    ];

    // list of members to benchmark
    const BENCHMARK = [
        'utf8ValidateOrg',
        'utf8ValidateEnh',
        'utf8ValidateEnhSplit',
        'utf8ValidateEnhSplitNa',
        'utf8ValidateOrgInline',
        'utf8ValidateEnhInline',
        'utf8ValidateEnhSplitNaInline',
        'utf8ValidateEnhSplitInline',
        'utf8DecodeOrg',
        'utf8DecodeEnh',
        'utf8DecodeEnhSplit',
        'utf8DecodeEnhSplitNa',
        'utf8DecodeOrgInline',
        'utf8DecodeEnhInline',
        'utf8DecodeEnhSplitInline',
        'utf8DecodeEnhSplitNaInline',
    ];

////////////////////////////////////////////////////////////////////////////////////
//  Implementations are provided for
//
//  - the original automaton by Boehrmann
//      uses UTF8O
//          <ext> := org
//
//  - the enhanced automaton as proposed by Rich Felker
//      uses a pre-multiplied table for classification, states
//      and transitions
//          <ext> := enh
//
//  - the enhanced automaton for PHP
//      splits UTF8F into two tables UTF8C for classification and
//      UTF8S for states and transitions
//          <ext> := enhSplit
//
//  - the enhanced automaton for PHP with reduced classification table size
//      removes ASCII codes (0x00 - 0x7f) from UTF8C (UTF8CNA) for
//      byte classification, UTF8S for states and transitions
//          <ext> := enhSplitNa
//
//  For each automaton the following members are implemented:
//      - utf8DecodeByte_<ext>        : calc next state and build codepoint
//                                        for current byte
//      - utf8Decode_<ext>             : decode string calling
//                                        utf8DecodeByte_<ext>
//      - utf8Decode_<ext>Inline      : decode string inlining
//                                        the code from utf8DecodeByte_<ext>
//
//      - utf8ValidateByte_<ext>      : calc next state for current byte
//      - utf8Validate_<ext>           : validate utf8-string calling
//                                        utf8ValidateByte_<ext>
//      - utf8Validate_<ext>Inline    : validate string inlining
//                                        the code from utf8ValidateByte_<ext>
////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////
// Some information on how this decoder works:
//
// UTF-8 is a variable length character encoding. To decode a character one or more
// bytes have to be read from a string. The utf8DecodeByte_<ext> functions
// implement a single step in this process. They take three parameters maintaining
// current state, current build step of the codepoint and @author frank.hein
// state achieved after processing the byte.
//
// Specifically, they return the value UTF8_ACCEPT (0) if enough bytes have been
// read for a character, UTF8_REJECT_ORG (1) (<ext> = org) or UTF8_REJECT (12) (all
// other algorithms) if the byte is not allowed to occur at its position, and some
// other positive value if more bytes have to be read.
//
// When decoding the first byte of a string, the caller must set the state variable
// to UTF8_ACCEPT. If, after decoding one or more bytes the state UTF8_ACCEPT is
// reached again, then the decoded Unicode character value is available through the
// codepoint parameter (utf8Decode_... members only). If the state UTF8_REJECT
// or UTF8_REJECT_ORG respectively is entered, that state will never be exited
// unless the caller intervenes.
//
// This class was created for benchmarking purposes only. All algorithms for
// string decoding and validation return true if the input string is a valid
// utf8-string and false if not.
//
// The string decoding functions compute the unicode codepoints but do no further
// processing on those. In particular they do not return them. The benchmark
// is designed to measure the overhead of codepoint computation. Further
// processing of the results once they are achieved is not in the scope of this
// implementation.
//
// The string validating functions omit the computation of codepoints. They just
// detect if a string is a valid utf8-string or not.
////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////
//  The orginal algorithm using UTF8O
//

    // The original algorithm by Bjoern Hoehrmann from 2009
    public function utf8DecodeByteOrg($state, &$codepoint, $byte)
    {
        $type = self::UTF8O[$byte];

        // Optimizing c compilers remove the codepoint calculation
        // if the code point does not get used by the caller.
        // PHP can not optimize that way, therefore a distinct
        // utf8ValidateByteOrg is provided. See below.
        $codepoint = ($state !== self::UTF8_ACCEPT) ?
            ($byte & 0x3f) | ($codepoint << 6) :
            (0xff >> $type) & ($byte);

        // Experiments were made to lower the member call overhead
        // as far as possible.
        //  - no parameters, $state and $codepoint as member vars
        //  - no return value, $state and $codepoint by reference
        // Passing $state by value, modifying $codepoint (by ref)
        // on the fly and returning the new state tested fastest.
        //
        return self::UTF8O[256 + ($state << 4) + $type];
    }

    // Same as utf8DecodeByteOrg but without codepoint calculation.
    public function utf8ValidateByteOrg($state, $byte)
    {
        return self::UTF8O[256 + ($state << 4) + self::UTF8O[$byte]];
    }

    public function utf8DecodeOrg(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        $codepoint = null;
        for ($i = 0; $i < $len;) {
            $state = $this->utf8DecodeByteOrg($state, $codepoint, ord($s[$i++]));
            if ($state === self::UTF8_REJECT_ORG) {
                return false;
            }
        }
        return $state === self::UTF8_ACCEPT;
    }

    public function utf8DecodeOrgInline(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        $codepoint = null;
        for ($i = 0; $i < $len;) {
            $byte = ord($s[$i++]);
            $type = self::UTF8O[$byte];
            $cp = ($state !== self::UTF8_ACCEPT) ?
                ($byte & 0x3f) | ($cp << 6) :
                (0xff >> $type) & ($byte);
            $state = self::UTF8O[256 + ($state << 4) + $type];
            if ($state === self::UTF8_REJECT_ORG) {
                return false;
            }
        }
        return $state === self::UTF8_ACCEPT;
    }

    public function utf8ValidateOrg(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        for ($i = 0; $i < $len;) {
            $state = $this->utf8ValidateByteOrg($state, ord($s[$i++]));
            if ($state === self::UTF8_REJECT_ORG) {
                return false;
            }
        }
        return $state === self::UTF8_ACCEPT;
    }

    public function utf8ValidateOrgInline(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        for ($i = 0; $i < $len;) {
            $state = self::UTF8O[256 + ($state << 4) + self::UTF8O[ord($s[$i++])]];
            if ($state === self::UTF8_REJECT_ORG) {
                return false;
            }
        }
        return ($state === self::UTF8_ACCEPT);
    }

//  The orginal algorithm using UTF8O and variations
////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////
//  The enhanced algorithm using UTF8F pre-multiplied table
//

    public function utf8DecodeByteEnh($state, &$codepoint, $byte)
    {
        $type = self::UTF8F[$byte];

        $codepoint = ($state !== self::UTF8_ACCEPT) ?
            ($byte & 0x3f) | ($codepoint << 6) :
            (0xff >> $type) & ($byte);

        return self::UTF8F[256 + $state + $type];
    }

    // Same as utf8DecodeByteEnh but without codepoint calculation.
    public function utf8ValidateByteEnh($state, $byte)
    {
        return self::UTF8F[256 + $state + self::UTF8F[$byte]];
    }

    public function utf8DecodeEnh(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        $codepoint = null;
        for ($i = 0; $i < $len;) {
            $state = $this->utf8DecodeByteEnh($state, $cp, ord($s[$i++]));
            if ($state === self::UTF8_REJECT) {
                return false;
                ;
            }
        }
        return $state === self::UTF8_ACCEPT;
    }

    public function utf8DecodeEnhInline(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        $codepoint = null;
        for ($i = 0; $i < $len;) {
            $byte = ord($s[$i++]);
            $type = self::UTF8F[$byte];
            $codepoint = ($state !== self::UTF8_ACCEPT) ?
                ($byte & 0x3f) | ($codepoint << 6) :
                (0xff >> $type) & ($byte);
            $state = self::UTF8F[256 + $state + $type];
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return $state === self::UTF8_ACCEPT;
    }

    public function utf8ValidateEnh(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        $i = 0;
        for ($i = 0; $i < $len;) {
            $state = $this->utf8ValidateByteEnh($state, ord($s[$i++]));
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return $state === self::UTF8_ACCEPT;
    }

    public function utf8ValidateEnhInline(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        for ($i = 0; $i < $len;) {
            $state = self::UTF8F[256 + $state + self::UTF8F[ord($s[$i++])]];
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return ($state === self::UTF8_ACCEPT);
    }

//
//  The enhanced algorithm using UTF8F pre-multiplied table
////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////
//  The enhanced algorithm using UTF8C char classification table
//  and UTF8S state and transition table (pre-multiplied)
//

    public function utf8DecodeByteEnhSplit($state, &$codepoint, $byte)
    {
        $type = self::UTF8C[$byte];

        $codepoint = ($state !== self::UTF8_ACCEPT) ?
            ($byte & 0x3f) | ($codepoint << 6) :
            (0xff >> $type) & ($byte);

        return self::UTF8S[$state + $type];
    }

    // Same as utf8DecodeByteEnh but without codepoint calculation.
    public function utf8ValidateByteEnhSplit($state, $byte)
    {
        return self::UTF8S[$state + self::UTF8C[$byte]];
    }

    public function utf8DecodeEnhSplit(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        $codepoint = null;
        for ($i = 0; $i < $len;) {
            $state = $this->utf8DecodeByteEnhSplit($state, $cp, ord($s[$i++]));
            if ($state === self::UTF8_REJECT) {
                return false;
                ;
            }
        }
        return $state === self::UTF8_ACCEPT;
    }

    public function utf8DecodeEnhSplitInline(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        $codepoint = null;
        for ($i = 0; $i < $len;) {
            $byte = ord($s[$i++]);
            $type = self::UTF8C[$byte];
            $codepoint = ($state !== self::UTF8_ACCEPT) ?
                ($byte & 0x3f) | ($codepoint << 6) :
                (0xff >> $type) & ($byte);
            $state = self::UTF8S[$state + $type];
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return $state === self::UTF8_ACCEPT;
    }

    public function utf8ValidateEnhSplit(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        $i = 0;
        for ($i = 0; $i < $len;) {
            $state = $this->utf8ValidateByteEnhSplit($state, ord($s[$i++]));
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return $state === self::UTF8_ACCEPT;
    }

    public function utf8ValidateEnhSplitInline(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        for ($i = 0; $i < $len;) {
            $state = self::UTF8S[$state + self::UTF8C[ord($s[$i++])]];
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return ($state === self::UTF8_ACCEPT);
    }

//
//  The enhanced algorithm using UTF8C char classification table
//  and UTF8S state and transition table (pre-multiplied)
////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////
//  The enhanced algorithm using UTF8CNA char classification table (ASCII codes not
//  included) and UTF8S state and transition table (pre-multiplied)
//

    public function utf8DecodeByteEnhSplitNa($state, &$codepoint, $byte)
    {
        if (($byte & 0x80)) {
            $type = self::UTF8CNA[$byte];

            $codepoint = ($state !== self::UTF8_ACCEPT) ?
                ($byte & 0x3f) + ($codepoint << 6) :
                (0xff >> $type) & $byte;

            return self::UTF8S[$state + $type];
        }
        $codepoint = $byte;
        return  self::UTF8S[$state];
    }

    // Same as utf8DecodeByteEnh but without codepoint calculation.
    public function utf8ValidateByteEnhSplitNa($state, $byte)
    {
        if ($byte & 0x80) {
            return self::UTF8S[$state + self::UTF8CNA[$byte]];
        }
        return  self::UTF8S[$state];
    }

    public function utf8DecodeEnhSplitNa(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        $codepoint = null;
        for ($i = 0; $i < $len;) {
            $state = $this->utf8DecodeByteEnhSplitNa($state, $codepoint, ord($s[$i++]));
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return ($state === self::UTF8_ACCEPT);
    }

    public function utf8DecodeEnhSplitNaInline(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        $codepoint = 0;
        for ($i = 0; $i < $len;) {
            $byte = ord($s[$i++]);
            if ($byte & 0x80) {
                $type = self::UTF8CNA[$byte];
                $codepoint = ($state !== self::UTF8_ACCEPT) ?
                    ($byte & 0x3f) + ($codepoint << 6) :
                    (0xff >> $type) & $byte;
                $state = self::UTF8S[$state + $type];
            } else {
                $codepoint = $byte;
                $state = self::UTF8S[$state];
            };
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return $state == self::UTF8_ACCEPT;
    }

    public function utf8ValidateEnhSplitNa(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        for ($i = 0; $i < $len;) {
            $state = $this->utf8ValidateByteEnhSplitNa($state, ord($s[$i++]));
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return ($state === self::UTF8_ACCEPT);
    }

    public function utf8ValidateEnhSplitNaInline(string &$s) : bool
    {
        $len = strlen($s);
        $state = self::UTF8_ACCEPT;
        for ($i = 0; $i < $len;) {
            $byte = ord($s[$i++]);
            $state = ($byte & 0x80) ? self::UTF8S[$state + self::UTF8CNA[$byte]] : self::UTF8S[$state];
            if ($state === self::UTF8_REJECT) {
                return false;
            }
        }
        return $state === self::UTF8_ACCEPT;
    }

//  The enhanced algorithm using UTF8CNA char classification table (ASCII codes not
//  included) and UTF8S state and transition table (pre-multiplied)
////////////////////////////////////////////////////////////////////////////////////
}
