<?php

namespace Mxc\Test\Parsec\Encoding;

use PHPUnit\Framework\TestCase;
use Mxc\Parsec\Encoding\HoehrmannDecoder;

class HoehrmannDecoderTest extends TestCase
{
    protected $codepoints = [
        // 1  Boundary condition test cases

        // 1.1  First possible sequence of a certain length
        //
        //           cp          expected result
        '1.1.1' => [ 0x00000000, "\x00", true],
        '1.1.2' => [ 0x00000080, "\xC2\x80", true],
        '1.1.3' => [ 0x00000800, "\xE0\xA0\x80", true],
        '1.1.4' => [ 0x00010000, "\xF0\x90\x80\x80", true],
        '1.1.5' => [ 0x00200000, "\xF8\x88\x80\x80\x80", false],
        '1.1.6' => [ 0x04000000, "\xFC\x84\x80\x80\x80\x80", false],
        //
        // 1.2  Last possible sequence of a certain length
        //
        '1.2.1' => [ 0x0000007F, "\x7F", true],
        '1.2.2' => [ 0x000007FF, "\xDF\xBF", true],
        '1.2.3' => [ 0x0000FFFF, "\xEF\xBF\xBF", true],
        '1.2.4' => [ 0x001FFFFF, "\xF7\xBF\xBF\xBF", false],
        '1.2.5' => [ 0x03FFFFFF, "\xFB\xBF\xBF\xBF\xBF", false],
        '1.2.6' => [ 0x7FFFFFFF, "\xFD\xBF\xBF\xBF\xBF\xBF", false],
        //
        // 1.3  Other boundary conditions
        //
        '1.3.1' => [ 0x0000D7FF, "\xED\x9F\xBF", true],
        '1.3.2' => [ 0x0000E000, "\xEE\x80\x80", true],
        '1.3.3' => [ 0x0000FFFD, "\xEF\xBF\xBD", true],
        '1.3.4' => [ 0x0010FFFF, "\xF4\x8F\xBF\xBF", true],
        '1.3.5' => [ 0x00110000, "\xF4\x90\x80\x80", false],
        //
        // 2  Malformed sequences
        //
        // 2.1  Start character generation
        //      Generate sequence of n bytes then cut off n-1 bytes at the end

        // 2.1.1  All 32 first bytes of 2-byte sequences (0xc0-0xdf)
        '2.1.1.1'  => [ 0x00, "\xC0", false],
        '2.1.1.2'  => [ 0x40, "\xC1", false],
        '2.1.1.3'  => [ 0x80, "\xC2", false],
        '2.1.1.4'  => [ 0xC0, "\xC3", false],
        '2.1.1.5'  => [ 0x0100, "\xC4", false],
        '2.1.1.6'  => [ 0x0140, "\xC5", false],
        '2.1.1.7'  => [ 0x0180, "\xC6", false],
        '2.1.1.8'  => [ 0x01C0, "\xC7", false],
        '2.1.1.9'  => [ 0x0200, "\xC8", false],
        '2.1.1.10' => [ 0x0240, "\xC9", false],
        '2.1.1.11' => [ 0x0280, "\xCA", false],
        '2.1.1.12' => [ 0x02C0, "\xCB", false],
        '2.1.1.13' => [ 0x0300, "\xCC", false],
        '2.1.1.14' => [ 0x0340, "\xCD", false],
        '2.1.1.15' => [ 0x0380, "\xCE", false],
        '2.1.1.16' => [ 0x03C0, "\xCF", false],
        '2.1.1.17' => [ 0x0400, "\xD0", false],
        '2.1.1.18' => [ 0x0440, "\xD1", false],
        '2.1.1.19' => [ 0x0480, "\xD2", false],
        '2.1.1.20' => [ 0x04C0, "\xD3", false],
        '2.1.1.21' => [ 0x0500, "\xD4", false],
        '2.1.1.22' => [ 0x0540, "\xD5", false],
        '2.1.1.23' => [ 0x0580, "\xD6", false],
        '2.1.1.24' => [ 0x05C0, "\xD7", false],
        '2.1.1.25' => [ 0x0600, "\xD8", false],
        '2.1.1.27' => [ 0x0640, "\xD9", false],
        '2.1.1.28' => [ 0x0680, "\xDA", false],
        '2.1.1.29' => [ 0x06C0, "\xDB", false],
        '2.1.1.30' => [ 0x0700, "\xDC", false],
        '2.1.1.31' => [ 0x0740, "\xDD", false],
        '2.1.1.32' => [ 0x0780, "\xDE", false],
        '2.1.1.33' => [ 0x07C0, "\xDF", false],

        // 2.2.2  All 16 first bytes of 3-byte sequences (0xe0-0xef)
        '2.1.2.1' => [ 0x0800, "\xE0", false],
        '2.1.2.2' => [ 0x1800, "\xE1", false],
        '2.1.2.3' => [ 0x2800, "\xE2", false],
        '2.1.2.4' => [ 0x3800, "\xE3", false],
        '2.1.2.5' => [ 0x4800, "\xE4", false],
        '2.1.2.6' => [ 0x5800, "\xE5", false],
        '2.1.2.7' => [ 0x6800, "\xE6", false],
        '2.1.2.8' => [ 0x7800, "\xE7", false],
        '2.1.2.9' => [ 0x8800, "\xE8", false],
        '2.1.2.10' => [ 0x9800, "\xE9", false],
        '2.1.2.11' => [ 0xA800, "\xEA", false],
        '2.1.2.12' => [ 0xB800, "\xEB", false],
        '2.1.2.13' => [ 0xC800, "\xEC", false],
        '2.1.2.14' => [ 0xD800, "\xED", false],
        '2.1.2.15' => [ 0xE800, "\xEE", false],
        '2.1.2.16' => [ 0xF800, "\xEF", false],

        // 2.2.3  All 8 first bytes of 4-byte sequences (0xf0-0xf7)
        '2.1.3.1' => [ 0x00010000, "\xF0", false],
        '2.1.3.2' => [ 0x00050000, "\xF1", false],
        '2.1.3.3' => [ 0x00090000, "\xF2", false],
        '2.1.3.4' => [ 0x000D0000, "\xF3", false],
        '2.1.3.5' => [ 0x00110000, "\xF4", false],
        '2.1.3.6' => [ 0x00150000, "\xF5", false],
        '2.1.3.7' => [ 0x00190000, "\xF6", false],
        '2.1.3.8' => [ 0x001D0000, "\xF7", false],

        // 2.2.4  All 4 first bytes of 5-byte sequences (0xf8-0xfb)
        '2.1.4.1' => [ 0x00200000, "\xF8", false],
        '2.1.4.2' => [ 0x01200000, "\xF9", false],
        '2.1.4.3' => [ 0x02200000, "\xFA", false],
        '2.1.4.4' => [ 0x03200000, "\xFB", false],

        // 2.2.5  All 2 first bytes of 6-byte sequences (0xfc-0xfd)
        '2.1.5.1' => [ 0x04000000, "\xFC", false],
        '2.1.5.1' => [ 0x44000000, "\xFD", false],

        // 2.1.6 More cutoff tests

        '2.1.6.1.0' => [ 0x00000000, "\x00", true],

        '2.1.6.2.0' => [ 0x00000080, "\xC2\x80", true],
        '2.1.6.2.1' => [ 0x00000080, "\xC2", false],

        '2.1.6.3.0' => [ 0x00000800, "\xE0\xA0\x80", true],
        '2.1.6.3.1' => [ 0x00000800, "\xE0\xA0", false],
        '2.1.6.3.2' => [ 0x00000800, "\xE0", false],

        '2.1.6.4.0' => [ 0x00010000, "\xF0\x90\x80\x80", true],
        '2.1.6.4.1' => [ 0x00010000, "\xF0\x90\x80", false],
        '2.1.6.4.2' => [ 0x00010000, "\xF0\x90", false],
        '2.1.6.4.3' => [ 0x00010000, "\xF0", false],

        '2.1.6.5.0' => [ 0x00200000, "\xF8\x88\x80\x80\x80", false],
        '2.1.6.5.1' => [ 0x00200000, "\xF8\x88\x80\x80", false],
        '2.1.6.5.2' => [ 0x00200000, "\xF8\x88\x80", false],
        '2.1.6.5.3' => [ 0x00200000, "\xF8\x88", false],
        '2.1.6.5.4' => [ 0x00200000, "\xF8", false],

        '2.1.6.6.0' => [ 0x04000000, "\xFC\x84\x80\x80\x80\x80", false],
        '2.1.6.6.1' => [ 0x04000000, "\xFC\x84\x80\x80\x80", false],
        '2.1.6.6.2' => [ 0x04000000, "\xFC\x84\x80\x80", false],
        '2.1.6.6.3' => [ 0x04000000, "\xFC\x84\x80", false],
        '2.1.6.6.4' => [ 0x04000000, "\xFC\x84", false],
        '2.1.6.6.5' => [ 0x04000000, "\xFC", false],

        // 2.2  Sequences with last continuation byte missing
        //
        '2.2.1'  => [ 0x00000000, "\xC0", false],
        '2.2.2'  => [ 0x00000000, "\xE0\x80", false],
        '2.2.3'  => [ 0x00000000, "\xF0\x80\x80", false],
        '2.2.4'  => [ 0x00000000, "\xF8\x80\x80\x80", false],
        '2.2.5'  => [ 0x00000000, "\xFC\x80\x80\x80\x80", false],
        '2.2.6'  => [ 0x000007FF, "\xDF", false],
        '2.2.7'  => [ 0x0000FFFF, "\xEF\xBF", false],
        '2.2.8'  => [ 0x001FFFFF, "\xF7\xBF\xBF", false],
        '2.2.9'  => [ 0x03FFFFFF, "\xFB\xBF\xBF\xBF", false],
        '2.2.10' => [ 0x7FFFFFFF, "\xFD\xBF\xBF\xBF\xBF", false],

        // 3. Overlong sequences

        // 3.1  Examples of an overlong ASCII character
        //
        // With a safe UTF-8 decoder, all of the following five overlong
        // representations of the ASCII character slash ("/", 0x2F) should be rejected
        // like a malformed UTF-8 sequence, for instance by substituting it with
        // a replacement character. If you see a slash below, you do not have a
        // safe UTF-8 decoder!
        //
        // The encoder tested here must be able to create these overlong sequences
        //
        '3.1.1' => [ 0x2F, "\x2F", true],                       //U+002F
        '3.1.3' => [ 0x2F, "\xC0\xAF", false],                  //U+002F
        '3.1.4' => [ 0x2F, "\xE0\x80\xAF", false],              //U+002F
        '3.1.5' => [ 0x2F, "\xF0\x80\x80\xAF", false],          //U+002F
        '3.1.6' => [ 0x2F, "\xF8\x80\x80\x80\xAF", false],      //U+002F
        '3.1.7' => [ 0x2F, "\xFC\x80\x80\x80\x80\xAF", false],  //U+002F

        // 3.2  Maximum overlong sequences
        //
        // Below you see the highest Unicode value that is still resulting in an
        // overlong sequence if represented with the given number of bytes. This
        // is a boundary test for safe UTF-8 decoders. All five characters should
        // be rejected like malformed UTF-8 sequences.
        //
        // The encoder should accept a custom target length only if this custom
        // is bigger than the minimum bytes needed to encode
        //
        // The encoder tested here must be able to create these overlong sequences
        //
        '3.2.1' => [ 0x0000007F, "\x7F", true],
        '3.2.3' => [ 0x0000007F, "\xC1\xBF", false],
        '3.2.4' => [ 0x0000007F, "\xE0\x81\xBF", false],
        '3.2.5' => [ 0x0000007F, "\xF0\x80\x81\xBF", false],
        '3.2.6' => [ 0x0000007F, "\xF8\x80\x80\x81\xBF", false],
        '3.2.7' => [ 0x0000007F, "\xFC\x80\x80\x80\x81\xBF", false],

        '3.2.8' => [ 0x000007FF, "\xDF\xBF", true],
        '3.2.11' => [ 0x000007FF, "\xE0\x9F\xBF", false],
        '3.2.12' => [ 0x000007FF, "\xF0\x80\x9F\xBF", false],
        '3.2.13' => [ 0x000007FF, "\xF8\x80\x80\x9F\xBF", false],
        '3.2.14' => [ 0x000007FF, "\xFC\x80\x80\x80\x9F\xBF", false],

        '3.2.15' => [ 0x0000FFFF, "\xEF\xBF\xBF", true],
        '3.2.19' => [ 0x0000FFFF, "\xF0\x8F\xBF\xBF", false],
        '3.2.20' => [ 0x0000FFFF, "\xF8\x80\x8F\xBF\xBF", false],
        '3.2.21' => [ 0x0000FFFF, "\xFC\x80\x80\x8F\xBF\xBF", false],

        '3.2.22' => [ 0x001FFFFF, "\xF7\xBF\xBF\xBF", false],
        '3.2.26' => [ 0x001FFFFF, "\xF7\xBF\xBF\xBF", false],
        '3.2.27' => [ 0x001FFFFF, "\xF8\x87\xBF\xBF\xBF", false],
        '3.2.28' => [ 0x001FFFFF, "\xFC\x80\x87\xBF\xBF\xBF", false],

        '3.2.29' => [ 0x03FFFFFF, "\xFB\xBF\xBF\xBF\xBF", false],
        '3.2.34' => [ 0x03FFFFFF, "\xFB\xBF\xBF\xBF\xBF", false],
        '3.2.35' => [ 0x03FFFFFF, "\xFC\x83\xBF\xBF\xBF\xBF", false],

        '3.2.36' => [ 0x7FFFFFFF, "\xFD\xBF\xBF\xBF\xBF\xBF", false],
        '3.2.42' => [ 0x7FFFFFFF, "\xFD\xBF\xBF\xBF\xBF\xBF", false],

        // 3.3  Overlong representation of the NUL character
        //
        // The following five sequences should also be rejected like malformed
        // UTF-8 sequences and should not be treated like the ASCII NUL
        // character.
        //
        '3.3.1' => [ 0x00, "\xC0\x80", false],                      //U+0000
        '3.3.2' => [ 0x00, "\xE0\x80\x80", false],                  //U+0000
        '3.3.3' => [ 0x00, "\xF0\x80\x80\x80", false],              //U+0000
        '3.3.4' => [ 0x00, "\xF8\x80\x80\x80\x80", false],          //U+0000
        '3.3.5' => [ 0x00, "\xFC\x80\x80\x80\x80\x80", false],      //U+0000

        // 4. Illegal opcode positions
        //
        // The following UTF-8 sequences should be rejected like malformed
        // sequences, because they never represent valid ISO 10646 characters and
        // a UTF-8 decoder that accepts them might introduce security problems
        // comparable to overlong UTF-8 sequences.
        //
        // The encoder tested here must be able to create these overlong sequences
        //

        // 4.1 Single UTF-16 surrogates
        //
        '4.1.1' => [ 0xD800, "\xED\xA0\x80", false], // U+D800
        '4.1.2' => [ 0xDB7F, "\xED\xAD\xBF", false], // U+DB7F
        '4.1.3' => [ 0xDB80, "\xED\xAE\x80", false], // U+DB80
        '4.1.4' => [ 0xDBFF, "\xED\xAF\xBF", false], // U+DBFF
        '4.1.5' => [ 0xDC00, "\xED\xB0\x80", false], // U+DC00
        '4.1.6' => [ 0xDF80, "\xED\xBE\x80", false], // U+DF80
        '4.1.7' => [ 0xDFFF, "\xED\xBF\xBF", false], // U+DFFF

        // 4.3 Noncharacter code positions
        //
        // The following "noncharacters" are "reserved for internal use" by
        // applications, and according to older versions of the Unicode Standard
        // "should never be interchanged". Unicode Corrigendum #9 dropped the
        // latter restriction. Nevertheless, their presence in incoming UTF-8 data
        // can remain a potential security risk, depending on what use is made of
        // these codes subsequently. Examples of such internal use:
        //
        // - Some file APIs with 16-bit characters may use the integer value -1
        //   = U+FFFF to signal an end-of-file (EOF) or error condition.
        //
        // - In some UTF-16 receivers, code point U+FFFE might trigger a
        //   byte-swap operation (to convert between UTF-16LE and UTF-16BE).
        //
        // With such internal use of noncharacters, it may be desirable and safer
        // to block those code points in UTF-8 decoders, as they should never
        // occur legitimately in incoming UTF-8 data, and could trigger unsafe
        // behaviour in subsequent processing.
        //
        // An encoder should be able to create this noncharacters.
        //
        // Particularly problematic noncharacters in a 16-bit application
        //
        '4.3.1.1' => [ 0xFFFE, "\xEF\xBF\xBE", true], // U+FFFE
        '4.3.1.1' => [ 0xFFFF, "\xEF\xBF\xBF", true], // U+FFFF
        // U+FDD0 - U+FDEF
        '4.3.2.1' => [ 0xFDD0, "\xEF\xB7\x90", true],  '4.3.2.2' => [ 0xFDD1, "\xEF\xB7\x91", true],
        '4.3.2.3' => [ 0xFDD2, "\xEF\xB7\x92", true],  '4.3.2.4' => [ 0xFDD3, "\xEF\xB7\x93", true],
        '4.3.2.5' => [ 0xFDD4, "\xEF\xB7\x94", true],  '4.3.2.6' => [ 0xFDD5, "\xEF\xB7\x95", true],
        '4.3.2.7' => [ 0xFDD6, "\xEF\xB7\x96", true],  '4.3.2.8' => [ 0xFDD7, "\xEF\xB7\x97", true],
        '4.3.2.9' => [ 0xFDD8, "\xEF\xB7\x98", true], '4.3.2.10' => [ 0xFDD9, "\xEF\xB7\x99", true],
        '4.3.2.11' => [ 0xFDDA, "\xEF\xB7\x9A", true], '4.3.2.12' => [ 0xFDDB, "\xEF\xB7\x9B", true],
        '4.3.2.13' => [ 0xFDDC, "\xEF\xB7\x9C", true], '4.3.2.14' => [ 0xFDDD, "\xEF\xB7\x9D", true],
        '4.3.2.15' => [ 0xFDDE, "\xEF\xB7\x9E", true], '4.3.2.16' => [ 0xFDDF, "\xEF\xB7\x9F", true],
        '4.3.2.17' => [ 0xFDE0, "\xEF\xB7\xA0", true], '4.3.2.18' => [ 0xFDE1, "\xEF\xB7\xA1", true],
        '4.3.2.19' => [ 0xFDE2, "\xEF\xB7\xA2", true], '4.3.2.20' => [ 0xFDE3, "\xEF\xB7\xA3", true],
        '4.3.2.21' => [ 0xFDE4, "\xEF\xB7\xA4", true], '4.3.2.22' => [ 0xFDE5, "\xEF\xB7\xA5", true],
        '4.3.2.23' => [ 0xFDE6, "\xEF\xB7\xA6", true], '4.3.2.24' => [ 0xFDE7, "\xEF\xB7\xA7", true],
        '4.3.2.25' => [ 0xFDE8, "\xEF\xB7\xA8", true], '4.3.2.26' => [ 0xFDE9, "\xEF\xB7\xA9", true],
        '4.3.2.27' => [ 0xFDEA, "\xEF\xB7\xAA", true], '4.3.2.28' => [ 0xFDEB, "\xEF\xB7\xAB", true],
        '4.3.2.29' => [ 0xFDEC, "\xEF\xB7\xAC", true], '4.3.2.30' => [ 0xFDED, "\xEF\xB7\xAD", true],
        '4.3.2.31' => [ 0xFDEE, "\xEF\xB7\xAE", true], '4.3.2.32' => [ 0xFDEF, "\xEF\xB7\xAF", true],
        // U+nFFFE - U+nFFFF (n = 1 .. 0x10)
        '4.3.3.1' => [ 0x0001FFFE, "\xF0\x9F\xBF\xBE", true],  '4.3.3.2' => [ 0x0001FFFF, "\xF0\x9F\xBF\xBF", true],
        '4.3.3.3' => [ 0x0002FFFE, "\xF0\xAF\xBF\xBE", true],  '4.3.3.4' => [ 0x0002FFFF, "\xF0\xAF\xBF\xBF", true],
        '4.3.3.5' => [ 0x0003FFFE, "\xF0\xBF\xBF\xBE", true],  '4.3.3.6' => [ 0x0003FFFF, "\xF0\xBF\xBF\xBF", true],
        '4.3.3.7' => [ 0x0004FFFE, "\xF1\x8F\xBF\xBE", true],  '4.3.3.8' => [ 0x0004FFFF, "\xF1\x8F\xBF\xBF", true],
        '4.3.3.9' => [ 0x0005FFFE, "\xF1\x9F\xBF\xBE", true], '4.3.3.10' => [ 0x0005FFFF, "\xF1\x9F\xBF\xBF", true],
        '4.3.3.11' => [ 0x0006FFFE, "\xF1\xAF\xBF\xBE", true], '4.3.3.12' => [ 0x0006FFFF, "\xF1\xAF\xBF\xBF", true],
        '4.3.3.13' => [ 0x0007FFFE, "\xF1\xBF\xBF\xBE", true], '4.3.3.14' => [ 0x0007FFFF, "\xF1\xBF\xBF\xBF", true],
        '4.3.3.15' => [ 0x0008FFFE, "\xF2\x8F\xBF\xBE", true], '4.3.3.16' => [ 0x0008FFFF, "\xF2\x8F\xBF\xBF", true],
        '4.3.3.17' => [ 0x0009FFFE, "\xF2\x9F\xBF\xBE", true], '4.3.3.18' => [ 0x0009FFFF, "\xF2\x9F\xBF\xBF", true],
        '4.3.3.19' => [ 0x000AFFFE, "\xF2\xAF\xBF\xBE", true], '4.3.3.20' => [ 0x000AFFFF, "\xF2\xAF\xBF\xBF", true],
        '4.3.3.21' => [ 0x000BFFFE, "\xF2\xBF\xBF\xBE", true], '4.3.3.22' => [ 0x000BFFFF, "\xF2\xBF\xBF\xBF", true],
        '4.3.3.23' => [ 0x000CFFFE, "\xF3\x8F\xBF\xBE", true], '4.3.3.24' => [ 0x000CFFFF, "\xF3\x8F\xBF\xBF", true],
        '4.3.3.25' => [ 0x000DFFFE, "\xF3\x9F\xBF\xBE", true], '4.3.3.26' => [ 0x000DFFFF, "\xF3\x9F\xBF\xBF", true],
        '4.3.3.27' => [ 0x000EFFFE, "\xF3\xAF\xBF\xBE", true], '4.3.3.28' => [ 0x000EFFFF, "\xF3\xAF\xBF\xBF", true],
        '4.3.3.29' => [ 0x000FFFFE, "\xF3\xBF\xBF\xBE", true], '4.3.3.30' => [ 0x000FFFFF, "\xF3\xBF\xBF\xBF", true],
        '4.3.3.31' => [ 0x0010FFFE, "\xF4\x8F\xBF\xBE", true], '4.3.3.32' => [ 0x0010FFFF, "\xF4\x8F\xBF\xBF", true],
    ];

    protected $sequences = [

        // 2.3  Concatenation of incomplete sequences
        //
        // All the 10 sequences of 2.2 concatenated, you should see 10 malformed
        // sequences being signalled:
        //
        '2.3' => [
            "\xC0"
            . "\xE0\x80"
            . "\xF0\x80\x80"
            . "\xF8\x80\x80\x80"
            . "\xFD\x80\x80\x80\x80"
            . "\xC2"
            . "\xEF\xBF"
            . "\xF7\xBF\xBF"
            . "\xFB\xBF\xBF\xBF"
            . "\xFD\xBF\xBF\xBF\xBF"
            , false
        ],

        // 2.4.1  All 32 first bytes of 2-byte sequences (0xc0-0xdf),
        //        each followed by a space character:
        '2.4.1.1' => ["\xC0\x20", false], '2.4.1.2' => ["\xC1\x20", false],
        '2.4.1.3' => ["\xC2\x20", false], '2.4.1.4' => ["\xC3\x20", false],
        '2.4.1.5' => ["\xC4\x20", false], '2.4.1.6' => ["\xC5\x20", false],
        '2.4.1.7' => ["\xC6\x20", false], '2.4.1.8' => ["\xC7\x20", false],
        '2.4.1.9' => ["\xC8\x20", false], '2.4.1.10' => ["\xC9\x20", false],
        '2.4.1.11' => ["\xCA\x20", false], '2.4.1.12' => ["\xCB\x20", false],
        '2.4.1.13' => ["\xCC\x20", false], '2.4.1.14' => ["\xCD\x20", false],
        '2.4.1.15' => ["\xCE\x20", false], '2.4.1.16' => ["\xCF\x20", false],
        '2.4.1.17' => ["\xD0\x20", false], '2.4.1.18' => ["\xD1\x20", false],
        '2.4.1.19' => ["\xD2\x20", false], '2.4.1.20' => ["\xD3\x20", false],
        '2.4.1.21' => ["\xD4\x20", false], '2.4.1.22' => ["\xD5\x20", false],
        '2.4.1.23' => ["\xD6\x20", false], '2.4.1.24' => ["\xD7\x20", false],
        '2.4.1.25' => ["\xD8\x20", false], '2.4.1.26' => ["\xD9\x20", false],
        '2.4.1.27' => ["\xDA\x20", false], '2.4.1.28' => ["\xDB\x20", false],
        '2.4.1.29' => ["\xDC\x20", false], '2.4.1.30' => ["\xDD\x20", false],
        '2.4.1.31' => ["\xDE\x20", false], '2.4.1.32' => ["\xDF\x20", false],

        // 2.4.2  All 16 first bytes of 3-byte sequences (0xe0-0xef),
        //        each followed by a space character:
        '2.4.2.1' => ["\xE0\x20", false], '2.4.2.2' => ["\xE1\x20", false],
        '2.4.2.3' => ["\xE2\x20", false], '2.4.2.4' => ["\xE3\x20", false],
        '2.4.2.5' => ["\xE4\x20", false], '2.4.2.6' => ["\xE5\x20", false],
        '2.4.2.7' => ["\xE6\x20", false], '2.4.2.8' => ["\xE7\x20", false],
        '2.4.2.9' => ["\xE8\x20", false], '2.4.2.10' => ["\xE9\x20", false],
        '2.4.2.11' => ["\xEA\x20", false], '2.4.2.12' => ["\xEB\x20", false],
        '2.4.2.13' => ["\xEC\x20", false], '2.4.2.14' => ["\xED\x20", false],
        '2.4.2.15' => ["\xEE\x20", false], '2.4.2.16' => ["\xEF\x20", false],

        // 2.4.3  All 8 first bytes of 4-byte sequences (0xf0-0xf7),
        //        each followed by a space character:
        '2.4.3.1' => ["\xF0\x20", false], '2.4.3.2' => ["\xF1\x20", false],
        '2.4.3.3' => ["\xF2\x20", false], '2.4.3.4' => ["\xF3\x20", false],
        '2.4.3.5' => ["\xF4\x20", false], '2.4.3.6' => ["\xF5\x20", false],
        '2.4.3.7' => ["\xF6\x20", false], '2.4.3.8' => ["\xF7\x20", false],

        // 2.4.4  All 4 first bytes of 5-byte sequences (0xf8-0xfb),
        //        each followed by a space character:
        '2.4.4.1' => ["\xF8\x20", false], '2.4.4.2' => ["\xF9\x20", false],
        '2.4.4.3' => ["\xFA\x20", false], '2.4.4.3' => ["\xFB\x20", false],

        // 2.4.5  All 2 first bytes of 6-byte sequences (0xfc-0xfd),
        //        each followed by a space character:
        '2.4.5.1' => ["\xFC\x20", false], '2.4.5.2' => ["\xFD\x20", false],

        // 4.2 Paired UTF-16 surrogates
        //
        '4.2.1' => ["\xED\xA0\x80\xED\xB0\x80", false], // U+D800 U+DC00
        '4.2.2' => ["\xED\xA0\x80\xED\xBF\xBF", false], // U+D800 U+DFFF
        '4.2.3' => ["\xED\xAD\xBF\xED\xB0\x80", false], // U+DB7F U+DC00
        '4.2.4' => ["\xED\xAD\xBF\xED\xBF\xBF", false], // U+DB7F U+DFFF
        '4.2.5' => ["\xED\xAE\x80\xED\xB0\x80", false], // U+DB80 U+DC00
        '4.2.6' => ["\xED\xAE\x80\xED\xBF\xBF", false], // U+DB80 U+DFFF
        '4.2.7' => ["\xED\xAF\xBF\xED\xB0\x80", false], // U+DBFF U+DC00
        '4.2.8' => ["\xED\xAF\xBF\xED\xBF\xBF", false], // U+DBFF U+DFFF

    ];

    protected $decoder = null;

    const UTF8_TEST_POSITIVE = __DIR__ . '/Assets/utf8_sequence_0-0x10ffff'
        . '_including-unassigned_including-unprintable-asis.txt';

    private function readable($s)
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
                $len += 2;
            }
            $result .= sprintf("0x%0${len}X", $s);
            return $result;
        } elseif (is_bool($s)) {
            return $s ? 'true' : 'false';
        }
    }

    private function getMessage(string $func, string $input, bool $expected) : string
    {
        return sprintf(
            "Called %s(%s) Expected: %s",
            $func,
            $this->readable($input),
            $this->readable($expected)
        );
    }

    /** @dataProvider provideCodepoints */
    public function testvalidateCodepoints(int $codepoint, string $input, bool $expected)
    {
        $this->assertSame(
            $expected,
            $this->decoder->validate($input),
            $this->getMessage('validate', $input, $expected)
        );
    }

    /** @dataProvider provideSequences */
    public function testvalidateSequences(string $input, bool $expected)
    {
        $this->assertSame(
            $expected,
            $this->decoder->validate($input),
            $this->getMessage('validate', $input, $expected)
        );
    }

    public function testvalidateFile()
    {
        $s = file_get_contents(self::UTF8_TEST_POSITIVE);
        $this->assertTrue(
            $this->decoder->validate($s),
            'validate failed on sample file.'
        );
    }

    public function testIteratorFile()
    {
        $outFile = '';
        $inFile = file_get_contents(self::UTF8_TEST_POSITIVE);
        foreach ($this->decoder->getIterator($inFile) as $codepoint) {
            $this->assertNotNull($codepoint, 'Unexpected null codepoint.');
            $outFile .= \IntlChar::chr($codepoint);
        }
        $this->assertSame($inFile, $outFile, 'Failed to regenerate sample file'
            . ' from codepoints.');
    }

    public function provideSequences()
    {
        return $this->sequences;
    }

    public function provideCodepoints()
    {
        return $this->codepoints;
    }

    public function setUp()
    {
        $this->decoder = new HoehrmannDecoder();
    }
}
