<?php
/**
 * This this test of the Utf8Encoder was inspired by the
 * 'UTF-8 decoder capability and stress test' file by
 * Markus Kuhn <http://www.cl.cam.ac.uk/~mgk25/>
 *
 * https://www.cl.cam.ac.uk/~mgk25/ucs/examples/UTF-8-test.txt
 *
 * All tests addressing decoder's behaviour on sequences
 * of (malformed) codepoints were removed. Remaining tests
 * were renumbered.
 *
 * Tests addressing the behaviour of the encoder regarding
 * the parameters given to utf8Encode were added.
 *
 */

namespace Mxc\Test\Encoding;

use PHPUnit\Framework\TestCase;
use Mxc\Parsec\Encoding\Utf8Encoder;

class Utf8EncoderTest extends TestCase
{

    protected $utf8Encoder;

    protected $testData = [
        // 1  Boundary condition test cases

        // 1.1  First possible sequence of a certain length
        //
        //                                               cp        len cut expected result
        '1.1.1' => [ 0x00000000, 0, 0, "\x00"],
        '1.1.2' => [ 0x00000080, 0, 0, "\xC2\x80"],
        '1.1.3' => [ 0x00000800, 0, 0, "\xE0\xA0\x80"],
        '1.1.4' => [ 0x00010000, 0, 0, "\xF0\x90\x80\x80"],
        '1.1.5' => [ 0x00200000, 0, 0, "\xF8\x88\x80\x80\x80"],
        '1.1.6' => [ 0x04000000, 0, 0, "\xFC\x84\x80\x80\x80\x80"],
        //
        // 1.2  Last possible sequence of a certain length
        //
        '1.2.1' => [ 0x0000007F, 0, 0, "\x7F"],
        '1.2.2' => [ 0x000007FF, 0, 0, "\xDF\xBF"],
        '1.2.3' => [ 0x0000FFFF, 0, 0, "\xEF\xBF\xBF"],
        '1.2.4' => [ 0x001FFFFF, 0, 0, "\xF7\xBF\xBF\xBF"],
        '1.2.5' => [ 0x03FFFFFF, 0, 0, "\xFB\xBF\xBF\xBF\xBF"],
        '1.2.6' => [ 0x7FFFFFFF, 0, 0, "\xFD\xBF\xBF\xBF\xBF\xBF"],
        //
        // 1.3  Other boundary conditions
        //
        '1.3.1' => [ 0x0000D7FF, 0, 0, "\xED\x9F\xBF"],
        '1.3.2' => [ 0x0000E000, 0, 0, "\xEE\x80\x80"],
        '1.3.3' => [ 0x0000FFFD, 0, 0, "\xEF\xBF\xBD"],
        '1.3.4' => [ 0x0010FFFF, 0, 0, "\xF4\x8F\xBF\xBF"],
        '1.3.5' => [ 0x00110000, 0, 0, "\xF4\x90\x80\x80"],
        //
        // 2  Malformed sequences
        //
        // 2.1  Start character generation
        //      Generate sequence of n bytes then cut off n-1 bytes at the end

        // 2.1.1  All 32 first bytes of 2-byte sequences (0xc0-0xdf)
        '2.1.1.1'  => [ 0x00, 2, 1, "\xC0"],
        '2.1.1.2'  => [ 0x40, 2, 1, "\xC1"],
        '2.1.1.3'  => [ 0x80, 2, 1, "\xC2"],
        '2.1.1.4'  => [ 0xC0, 2, 1, "\xC3"],
        '2.1.1.5'  => [ 0x0100, 2, 1, "\xC4"],
        '2.1.1.6'  => [ 0x0140, 2, 1, "\xC5"],
        '2.1.1.7'  => [ 0x0180, 2, 1, "\xC6"],
        '2.1.1.8'  => [ 0x01C0, 2, 1, "\xC7"],
        '2.1.1.9'  => [ 0x0200, 2, 1, "\xC8"],
        '2.1.1.10' => [ 0x0240, 2, 1, "\xC9"],
        '2.1.1.11' => [ 0x0280, 2, 1, "\xCA"],
        '2.1.1.12' => [ 0x02C0, 2, 1, "\xCB"],
        '2.1.1.13' => [ 0x0300, 2, 1, "\xCC"],
        '2.1.1.14' => [ 0x0340, 2, 1, "\xCD"],
        '2.1.1.15' => [ 0x0380, 2, 1, "\xCE"],
        '2.1.1.16' => [ 0x03C0, 2, 1, "\xCF"],
        '2.1.1.17' => [ 0x0400, 2, 1, "\xD0"],
        '2.1.1.18' => [ 0x0440, 2, 1, "\xD1"],
        '2.1.1.19' => [ 0x0480, 2, 1, "\xD2"],
        '2.1.1.20' => [ 0x04C0, 2, 1, "\xD3"],
        '2.1.1.21' => [ 0x0500, 2, 1, "\xD4"],
        '2.1.1.22' => [ 0x0540, 2, 1, "\xD5"],
        '2.1.1.23' => [ 0x0580, 2, 1, "\xD6"],
        '2.1.1.24' => [ 0x05C0, 2, 1, "\xD7"],
        '2.1.1.25' => [ 0x0600, 2, 1, "\xD8"],
        '2.1.1.27' => [ 0x0640, 2, 1, "\xD9"],
        '2.1.1.28' => [ 0x0680, 2, 1, "\xDA"],
        '2.1.1.29' => [ 0x06C0, 2, 1, "\xDB"],
        '2.1.1.30' => [ 0x0700, 2, 1, "\xDC"],
        '2.1.1.31' => [ 0x0740, 2, 1, "\xDD"],
        '2.1.1.32' => [ 0x0780, 2, 1, "\xDE"],
        '2.1.1.33' => [ 0x07C0, 2, 1, "\xDF"],

        // 2.2.2  All 16 first bytes of 3-byte sequences (0xe0-0xef)
        '2.1.2.1' => [ 0x0800, 3, 2, "\xE0"],
        '2.1.2.2' => [ 0x1800, 3, 2, "\xE1"],
        '2.1.2.3' => [ 0x2800, 3, 2, "\xE2"],
        '2.1.2.4' => [ 0x3800, 3, 2, "\xE3"],
        '2.1.2.5' => [ 0x4800, 3, 2, "\xE4"],
        '2.1.2.6' => [ 0x5800, 3, 2, "\xE5"],
        '2.1.2.7' => [ 0x6800, 3, 2, "\xE6"],
        '2.1.2.8' => [ 0x7800, 3, 2, "\xE7"],
        '2.1.2.9' => [ 0x8800, 3, 2, "\xE8"],
        '2.1.2.10' => [ 0x9800, 3, 2, "\xE9"],
        '2.1.2.11' => [ 0xA800, 3, 2, "\xEA"],
        '2.1.2.12' => [ 0xB800, 3, 2, "\xEB"],
        '2.1.2.13' => [ 0xC800, 3, 2, "\xEC"],
        '2.1.2.14' => [ 0xD800, 3, 2, "\xED"],
        '2.1.2.15' => [ 0xE800, 3, 2, "\xEE"],
        '2.1.2.16' => [ 0xF800, 3, 2, "\xEF"],

        // 2.2.3  All 8 first bytes of 4-byte sequences (0xf0-0xf7)
        '2.1.3.1' => [ 0x00010000, 4, 3, "\xF0"],
        '2.1.3.2' => [ 0x00050000, 4, 3, "\xF1"],
        '2.1.3.3' => [ 0x00090000, 4, 3, "\xF2"],
        '2.1.3.4' => [ 0x000D0000, 4, 3, "\xF3"],
        '2.1.3.5' => [ 0x00110000, 4, 3, "\xF4"],
        '2.1.3.6' => [ 0x00150000, 4, 3, "\xF5"],
        '2.1.3.7' => [ 0x00190000, 4, 3, "\xF6"],
        '2.1.3.8' => [ 0x001D0000, 4, 3, "\xF7"],

        // 2.2.4  All 4 first bytes of 5-byte sequences (0xf8-0xfb)
        '2.1.4.1' => [ 0x00200000, 5, 4, "\xF8"],
        '2.1.4.2' => [ 0x01200000, 5, 4, "\xF9"],
        '2.1.4.3' => [ 0x02200000, 5, 4, "\xFA"],
        '2.1.4.4' => [ 0x03200000, 5, 4, "\xFB"],

        // 2.2.5  All 2 first bytes of 6-byte sequences (0xfc-0xfd)
        '2.1.5.1' => [ 0x04000000, 6, 5, "\xFC"],
        '2.1.5.1' => [ 0x44000000, 6, 5, "\xFD"],

        // 2.1.6 More cutoff tests

        '2.1.6.1.0' => [ 0x00000000, 0, 0, "\x00"],
        '2.1.6.1.1' => [ 0x00000000, 0, 1, false],
        '2.1.6.1.2' => [ 0x00000000, 0, 2, false],
        '2.1.6.1.3' => [ 0x00000000, 0, 3, false],
        '2.1.6.1.4' => [ 0x00000000, 0, 4, false],
        '2.1.6.1.5' => [ 0x00000000, 0, 5, false],
        '2.1.6.1.6' => [ 0x00000000, 0, 6, false],

        '2.1.6.2.0' => [ 0x00000080, 0, 0, "\xC2\x80"],
        '2.1.6.2.1' => [ 0x00000080, 0, 1, "\xC2"],
        '2.1.6.2.2' => [ 0x00000080, 0, 2, false],
        '2.1.6.2.3' => [ 0x00000080, 0, 3, false],
        '2.1.6.2.4' => [ 0x00000080, 0, 4, false],
        '2.1.6.2.5' => [ 0x00000080, 0, 5, false],
        '2.1.6.2.6' => [ 0x00000080, 0, 6, false],

        '2.1.6.3.0' => [ 0x00000800, 0, 0, "\xE0\xA0\x80"],
        '2.1.6.3.1' => [ 0x00000800, 0, 1, "\xE0\xA0"],
        '2.1.6.3.2' => [ 0x00000800, 0, 2, "\xE0"],
        '2.1.6.3.3' => [ 0x00000800, 0, 3, false],
        '2.1.6.3.4' => [ 0x00000800, 0, 4, false],
        '2.1.6.3.5' => [ 0x00000800, 0, 5, false],
        '2.1.6.3.6' => [ 0x00000800, 0, 6, false],

        '2.1.6.4.0' => [ 0x00010000, 0, 0, "\xF0\x90\x80\x80"],
        '2.1.6.4.1' => [ 0x00010000, 0, 1, "\xF0\x90\x80"],
        '2.1.6.4.2' => [ 0x00010000, 0, 2, "\xF0\x90"],
        '2.1.6.4.3' => [ 0x00010000, 0, 3, "\xF0"],
        '2.1.6.4.4' => [ 0x00010000, 0, 4, false],
        '2.1.6.4.5' => [ 0x00010000, 0, 5, false],
        '2.1.6.4.6' => [ 0x00010000, 0, 6, false],

        '2.1.6.5.0' => [ 0x00200000, 0, 0, "\xF8\x88\x80\x80\x80"],
        '2.1.6.5.1' => [ 0x00200000, 0, 1, "\xF8\x88\x80\x80"],
        '2.1.6.5.2' => [ 0x00200000, 0, 2, "\xF8\x88\x80"],
        '2.1.6.5.3' => [ 0x00200000, 0, 3, "\xF8\x88"],
        '2.1.6.5.4' => [ 0x00200000, 0, 4, "\xF8"],
        '2.1.6.5.5' => [ 0x00200000, 0, 5, false],
        '2.1.6.5.6' => [ 0x00200000, 0, 6, false],

        '2.1.6.6.0' => [ 0x04000000, 0, 0, "\xFC\x84\x80\x80\x80\x80"],
        '2.1.6.6.1' => [ 0x04000000, 0, 1, "\xFC\x84\x80\x80\x80"],
        '2.1.6.6.2' => [ 0x04000000, 0, 2, "\xFC\x84\x80\x80"],
        '2.1.6.6.3' => [ 0x04000000, 0, 3, "\xFC\x84\x80"],
        '2.1.6.6.4' => [ 0x04000000, 0, 4, "\xFC\x84"],
        '2.1.6.6.5' => [ 0x04000000, 0, 5, "\xFC"],
        '2.1.6.6.6' => [ 0x04000000, 0, 6, false],

        //
        // 2.3  Sequences with last continuation byte missing
        //
        '2.2.1'  => [ 0x00000000, 2, 1, "\xC0"],
        '2.2.2'  => [ 0x00000000, 3, 1, "\xE0\x80"],
        '2.2.3'  => [ 0x00000000, 4, 1, "\xF0\x80\x80"],
        '2.2.4'  => [ 0x00000000, 5, 1, "\xF8\x80\x80\x80"],
        '2.2.5'  => [ 0x00000000, 6, 1, "\xFC\x80\x80\x80\x80"],
        '2.2.6'  => [ 0x000007FF, 2, 1, "\xDF"],
        '2.2.7'  => [ 0x0000FFFF, 3, 1, "\xEF\xBF"],
        '2.2.8'  => [ 0x001FFFFF, 4, 1, "\xF7\xBF\xBF"],
        '2.2.9'  => [ 0x03FFFFFF, 5, 1, "\xFB\xBF\xBF\xBF"],
        '2.2.10' => [ 0x7FFFFFFF, 6, 1, "\xFD\xBF\xBF\xBF\xBF"],

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
        '3.1.1' => [ 0x2F, 0, 0, "\x2F"],                      //U+002F
        '3.1.2' => [ 0x2F, 1, 0, "\x2F"],                      //U+002F
        '3.1.3' => [ 0x2F, 2, 0, "\xC0\xAF"],                  //U+002F
        '3.1.4' => [ 0x2F, 3, 0, "\xE0\x80\xAF"],              //U+002F
        '3.1.5' => [ 0x2F, 4, 0, "\xF0\x80\x80\xAF"],          //U+002F
        '3.1.6' => [ 0x2F, 5, 0, "\xF8\x80\x80\x80\xAF"],      //U+002F
        '3.1.7' => [ 0x2F, 6, 0, "\xFC\x80\x80\x80\x80\xAF"],  //U+002F

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
        '3.2.1' => [ 0x0000007F, 0, 0, "\x7F"],
        '3.2.2' => [ 0x0000007F, 1, 0, "\x7F"],
        '3.2.3' => [ 0x0000007F, 2, 0, "\xC1\xBF"],
        '3.2.4' => [ 0x0000007F, 3, 0, "\xE0\x81\xBF"],
        '3.2.5' => [ 0x0000007F, 4, 0, "\xF0\x80\x81\xBF"],
        '3.2.6' => [ 0x0000007F, 5, 0, "\xF8\x80\x80\x81\xBF"],
        '3.2.7' => [ 0x0000007F, 6, 0, "\xFC\x80\x80\x80\x81\xBF"],

        '3.2.8' => [ 0x000007FF, 0, 0, "\xDF\xBF"],
        '3.2.9' => [ 0x000007FF, 1, 0, false],
        '3.2.10' => [ 0x000007FF, 2, 0, "\xDF\xBF"],
        '3.2.11' => [ 0x000007FF, 3, 0, "\xE0\x9F\xBF"],
        '3.2.12' => [ 0x000007FF, 4, 0, "\xF0\x80\x9F\xBF"],
        '3.2.13' => [ 0x000007FF, 5, 0, "\xF8\x80\x80\x9F\xBF"],
        '3.2.14' => [ 0x000007FF, 6, 0, "\xFC\x80\x80\x80\x9F\xBF"],

        '3.2.15' => [ 0x0000FFFF, 0, 0, "\xEF\xBF\xBF"],
        '3.2.16' => [ 0x0000FFFF, 1, 0, false],
        '3.2.17' => [ 0x0000FFFF, 2, 0, false],
        '3.2.18' => [ 0x0000FFFF, 3, 0, "\xEF\xBF\xBF"],
        '3.2.19' => [ 0x0000FFFF, 4, 0, "\xF0\x8F\xBF\xBF"],
        '3.2.20' => [ 0x0000FFFF, 5, 0, "\xF8\x80\x8F\xBF\xBF"],
        '3.2.21' => [ 0x0000FFFF, 6, 0, "\xFC\x80\x80\x8F\xBF\xBF"],

        '3.2.22' => [ 0x001FFFFF, 0, 0, "\xF7\xBF\xBF\xBF"],
        '3.2.23' => [ 0x001FFFFF, 1, 0, false],
        '3.2.24' => [ 0x001FFFFF, 2, 0, false],
        '3.2.25' => [ 0x001FFFFF, 3, 0, false],
        '3.2.26' => [ 0x001FFFFF, 4, 0, "\xF7\xBF\xBF\xBF"],
        '3.2.27' => [ 0x001FFFFF, 5, 0, "\xF8\x87\xBF\xBF\xBF"],
        '3.2.28' => [ 0x001FFFFF, 6, 0, "\xFC\x80\x87\xBF\xBF\xBF"],

        '3.2.29' => [ 0x03FFFFFF, 0, 0, "\xFB\xBF\xBF\xBF\xBF"],
        '3.2.30' => [ 0x03FFFFFF, 1, 0, false],
        '3.2.31' => [ 0x03FFFFFF, 2, 0, false],
        '3.2.32' => [ 0x03FFFFFF, 3, 0, false],
        '3.2.33' => [ 0x03FFFFFF, 4, 0, false],
        '3.2.34' => [ 0x03FFFFFF, 5, 0, "\xFB\xBF\xBF\xBF\xBF"],
        '3.2.35' => [ 0x03FFFFFF, 6, 0, "\xFC\x83\xBF\xBF\xBF\xBF"],

        '3.2.36' => [ 0x7FFFFFFF, 0, 0, "\xFD\xBF\xBF\xBF\xBF\xBF"],
        '3.2.37' => [ 0x7FFFFFFF, 1, 0, false],
        '3.2.38' => [ 0x7FFFFFFF, 2, 0, false],
        '3.2.39' => [ 0x7FFFFFFF, 3, 0, false],
        '3.2.40' => [ 0x7FFFFFFF, 4, 0, false],
        '3.2.41' => [ 0x7FFFFFFF, 5, 0, false],
        '3.2.42' => [ 0x7FFFFFFF, 6, 0, "\xFD\xBF\xBF\xBF\xBF\xBF"],

        // 3.3  Overlong representation of the NUL character
        //
        // The following five sequences should also be rejected like malformed
        // UTF-8 sequences and should not be treated like the ASCII NUL
        // character.
        //
        // The encoder tested here must be able to create these overlong sequences
        //
        '3.3.1' => [ 0x00, 2, 0, "\xC0\x80"],                      //U+0000
        '3.3.2' => [ 0x00, 3, 0, "\xE0\x80\x80"],                  //U+0000
        '3.3.3' => [ 0x00, 4, 0, "\xF0\x80\x80\x80"],              //U+0000
        '3.3.4' => [ 0x00, 5, 0, "\xF8\x80\x80\x80\x80"],          //U+0000
        '3.3.5' => [ 0x00, 6, 0, "\xFC\x80\x80\x80\x80\x80"],      //U+0000

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
        '4.1.1' => [ 0xD800, 0, 0, "\xED\xA0\x80"], // U+D800
        '4.1.2' => [ 0xDB7F, 0, 0, "\xED\xAD\xBF"], // U+DB7F
        '4.1.3' => [ 0xDB80, 0, 0, "\xED\xAE\x80"], // U+DB80
        '4.1.4' => [ 0xDBFF, 0, 0, "\xED\xAF\xBF"], // U+DBFF
        '4.1.5' => [ 0xDC00, 0, 0, "\xED\xB0\x80"], // U+DC00
        '4.1.6' => [ 0xDF80, 0, 0, "\xED\xBE\x80"], // U+DF80
        '4.1.7' => [ 0xDFFF, 0, 0, "\xED\xBF\xBF"], // U+DFFF

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
        '4.3.1.1' => [ 0xFFFE, 0, 0, "\xEF\xBF\xBE"], // U+FFFE
        '4.3.1.1' => [ 0xFFFF, 0, 0, "\xEF\xBF\xBF"], // U+FFFF
        // U+FDD0 - U+FDEF
         '4.3.2.1' => [ 0xFDD0, 0, 0, "\xEF\xB7\x90"],  '4.3.2.2' => [ 0xFDD1, 0, 0, "\xEF\xB7\x91"],
         '4.3.2.3' => [ 0xFDD2, 0, 0, "\xEF\xB7\x92"],  '4.3.2.4' => [ 0xFDD3, 0, 0, "\xEF\xB7\x93"],
         '4.3.2.5' => [ 0xFDD4, 0, 0, "\xEF\xB7\x94"],  '4.3.2.6' => [ 0xFDD5, 0, 0, "\xEF\xB7\x95"],
         '4.3.2.7' => [ 0xFDD6, 0, 0, "\xEF\xB7\x96"],  '4.3.2.8' => [ 0xFDD7, 0, 0, "\xEF\xB7\x97"],
         '4.3.2.9' => [ 0xFDD8, 0, 0, "\xEF\xB7\x98"], '4.3.2.10' => [ 0xFDD9, 0, 0, "\xEF\xB7\x99"],
        '4.3.2.11' => [ 0xFDDA, 0, 0, "\xEF\xB7\x9A"], '4.3.2.12' => [ 0xFDDB, 0, 0, "\xEF\xB7\x9B"],
        '4.3.2.13' => [ 0xFDDC, 0, 0, "\xEF\xB7\x9C"], '4.3.2.14' => [ 0xFDDD, 0, 0, "\xEF\xB7\x9D"],
        '4.3.2.15' => [ 0xFDDE, 0, 0, "\xEF\xB7\x9E"], '4.3.2.16' => [ 0xFDDF, 0, 0, "\xEF\xB7\x9F"],
        '4.3.2.17' => [ 0xFDE0, 0, 0, "\xEF\xB7\xA0"], '4.3.2.18' => [ 0xFDE1, 0, 0, "\xEF\xB7\xA1"],
        '4.3.2.19' => [ 0xFDE2, 0, 0, "\xEF\xB7\xA2"], '4.3.2.20' => [ 0xFDE3, 0, 0, "\xEF\xB7\xA3"],
        '4.3.2.21' => [ 0xFDE4, 0, 0, "\xEF\xB7\xA4"], '4.3.2.22' => [ 0xFDE5, 0, 0, "\xEF\xB7\xA5"],
        '4.3.2.23' => [ 0xFDE6, 0, 0, "\xEF\xB7\xA6"], '4.3.2.24' => [ 0xFDE7, 0, 0, "\xEF\xB7\xA7"],
        '4.3.2.25' => [ 0xFDE8, 0, 0, "\xEF\xB7\xA8"], '4.3.2.26' => [ 0xFDE9, 0, 0, "\xEF\xB7\xA9"],
        '4.3.2.27' => [ 0xFDEA, 0, 0, "\xEF\xB7\xAA"], '4.3.2.28' => [ 0xFDEB, 0, 0, "\xEF\xB7\xAB"],
        '4.3.2.29' => [ 0xFDEC, 0, 0, "\xEF\xB7\xAC"], '4.3.2.30' => [ 0xFDED, 0, 0, "\xEF\xB7\xAD"],
        '4.3.2.31' => [ 0xFDEE, 0, 0, "\xEF\xB7\xAE"], '4.3.2.32' => [ 0xFDEF, 0, 0, "\xEF\xB7\xAF"],
        // U+nFFFE - U+nFFFF (n = 1 .. 0x10)
         '4.3.3.1' => [ 0x0001FFFE, 0, 0, "\xF0\x9F\xBF\xBE"],  '4.3.3.2' => [ 0x0001FFFF, 0, 0, "\xF0\x9F\xBF\xBF"],
         '4.3.3.3' => [ 0x0002FFFE, 0, 0, "\xF0\xAF\xBF\xBE"],  '4.3.3.4' => [ 0x0002FFFF, 0, 0, "\xF0\xAF\xBF\xBF"],
         '4.3.3.5' => [ 0x0003FFFE, 0, 0, "\xF0\xBF\xBF\xBE"],  '4.3.3.6' => [ 0x0003FFFF, 0, 0, "\xF0\xBF\xBF\xBF"],
         '4.3.3.7' => [ 0x0004FFFE, 0, 0, "\xF1\x8F\xBF\xBE"],  '4.3.3.8' => [ 0x0004FFFF, 0, 0, "\xF1\x8F\xBF\xBF"],
         '4.3.3.9' => [ 0x0005FFFE, 0, 0, "\xF1\x9F\xBF\xBE"], '4.3.3.10' => [ 0x0005FFFF, 0, 0, "\xF1\x9F\xBF\xBF"],
        '4.3.3.11' => [ 0x0006FFFE, 0, 0, "\xF1\xAF\xBF\xBE"], '4.3.3.12' => [ 0x0006FFFF, 0, 0, "\xF1\xAF\xBF\xBF"],
        '4.3.3.13' => [ 0x0007FFFE, 0, 0, "\xF1\xBF\xBF\xBE"], '4.3.3.14' => [ 0x0007FFFF, 0, 0, "\xF1\xBF\xBF\xBF"],
        '4.3.3.15' => [ 0x0008FFFE, 0, 0, "\xF2\x8F\xBF\xBE"], '4.3.3.16' => [ 0x0008FFFF, 0, 0, "\xF2\x8F\xBF\xBF"],
        '4.3.3.17' => [ 0x0009FFFE, 0, 0, "\xF2\x9F\xBF\xBE"], '4.3.3.18' => [ 0x0009FFFF, 0, 0, "\xF2\x9F\xBF\xBF"],
        '4.3.3.19' => [ 0x000AFFFE, 0, 0, "\xF2\xAF\xBF\xBE"], '4.3.3.20' => [ 0x000AFFFF, 0, 0, "\xF2\xAF\xBF\xBF"],
        '4.3.3.21' => [ 0x000BFFFE, 0, 0, "\xF2\xBF\xBF\xBE"], '4.3.3.22' => [ 0x000BFFFF, 0, 0, "\xF2\xBF\xBF\xBF"],
        '4.3.3.23' => [ 0x000CFFFE, 0, 0, "\xF3\x8F\xBF\xBE"], '4.3.3.24' => [ 0x000CFFFF, 0, 0, "\xF3\x8F\xBF\xBF"],
        '4.3.3.25' => [ 0x000DFFFE, 0, 0, "\xF3\x9F\xBF\xBE"], '4.3.3.26' => [ 0x000DFFFF, 0, 0, "\xF3\x9F\xBF\xBF"],
        '4.3.3.27' => [ 0x000EFFFE, 0, 0, "\xF3\xAF\xBF\xBE"], '4.3.3.28' => [ 0x000EFFFF, 0, 0, "\xF3\xAF\xBF\xBF"],
        '4.3.3.29' => [ 0x000FFFFE, 0, 0, "\xF3\xBF\xBF\xBE"], '4.3.3.30' => [ 0x000FFFFF, 0, 0, "\xF3\xBF\xBF\xBF"],
        '4.3.3.31' => [ 0x0010FFFE, 0, 0, "\xF4\x8F\xBF\xBE"], '4.3.3.32' => [ 0x0010FFFF, 0, 0, "\xF4\x8F\xBF\xBF"],

        // 5 Other parameter tests

        // 5.1 cut < len
        '5.1.1' => [ 0x80, 3, 4, false],
        '5.1.2' => [ 0x80, 2, 2, false],

        // 5.2 arguments >= 0
        '5.2.1' => [ 0x80, -1, 0, false],
        '5.2.2' => [ 0x80, 0, -1, false],
        '5.2.3' => [ 0x80, -1, -1, false],

        // 5.3 maximum length 6
        '5.3.1' => [ 0x80, 7, 0, false],
    ];

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

    public function getMessage(int $input, int $len, int $cut, string $expected, string $actual) : string
    {
        return sprintf(
            "Called utf8Encode(0x%08X, %u, %u) Expected: %s  Actual: %s",
            $input,
            $len,
            $cut,
            $this->readable($expected),
            $this->readable($actual)
        );
    }

    /** @dataProvider provideTestData */
    public function testencode($input, $len, $cut, $expected)
    {

        $actual = $this->utf8Encoder->encode($input, $len, $cut);
        $this->assertSame(
            $expected,
            $actual,
            $this->getMessage($input, $len, $cut, $expected, $actual)
        );
    }

    public function provideTestData()
    {
        return $this->testData;
    }

    public function setUp()
    {
        $this->utf8Encoder = new Utf8Encoder();
    }
}
