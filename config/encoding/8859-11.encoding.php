<?php
// Heading notes from source file 8859-11.TXT:
//
//  8859-11.TXT
//  Date: 2015-12-02 21:55:00 GMT [KW]
//  © 2015 Unicode®, Inc.
//  For terms of use, see http://www.unicode.org/terms_of_use.html
//
// 	Name:             ISO/IEC 8859-11:2001 to Unicode
// 	Unicode version:  3.2
// 	Table version:    2.0
// 	Table format:     Format A
// 	Date:             2002 October 7 (header updated: 2015 December 02)
// 	Authors:          Ken Whistler <ken@unicode.org>
//
// 	General notes:
//
// 	This table contains the data the Unicode Consortium has on how
//        ISO/IEC 8859-11:2001 characters map into Unicode.
//
// 	ISO/IEC 8859-11:2001 is equivalent to TIS 620-2533 (1990) with
// 	the addition of 0xA0 NO-BREAK SPACE.
//
// 	Format:  Three tab-separated columns
// 		 Column #1 is the ISO/IEC 8859-11 code (in hex as 0xXX)
// 		 Column #2 is the Unicode (in hex as 0xXXXX)
// 		 Column #3 the Unicode name (follows a comment sign, '#')
//
// 	The entries are in ISO/IEC 8859-11 order.
//
// 	Version history:
// 		2002 October 7  Created
//    2.0 version: updates to copyright notice and terms of use; no
//        changes to character mappings
//
// 	Updated versions of this file may be found in:
// 		http://www.unicode.org/Public/MAPPINGS/
//
// 	Any comments or problems, contact us at:
//        http://www.unicode.org/reporting.html
//
return [
    "name" => "8859-11",
    "codepage_to_unicode" => [
        0x00 => 0x0000,    // 	NULL
        0x01 => 0x0001,    // 	START OF HEADING
        0x02 => 0x0002,    // 	START OF TEXT
        0x03 => 0x0003,    // 	END OF TEXT
        0x04 => 0x0004,    // 	END OF TRANSMISSION
        0x05 => 0x0005,    // 	ENQUIRY
        0x06 => 0x0006,    // 	ACKNOWLEDGE
        0x07 => 0x0007,    // 	BELL
        0x08 => 0x0008,    // 	BACKSPACE
        0x09 => 0x0009,    // 	HORIZONTAL TABULATION
        0x0A => 0x000A,    // 	LINE FEED
        0x0B => 0x000B,    // 	VERTICAL TABULATION
        0x0C => 0x000C,    // 	FORM FEED
        0x0D => 0x000D,    // 	CARRIAGE RETURN
        0x0E => 0x000E,    // 	SHIFT OUT
        0x0F => 0x000F,    // 	SHIFT IN
        0x10 => 0x0010,    // 	DATA LINK ESCAPE
        0x11 => 0x0011,    // 	DEVICE CONTROL ONE
        0x12 => 0x0012,    // 	DEVICE CONTROL TWO
        0x13 => 0x0013,    // 	DEVICE CONTROL THREE
        0x14 => 0x0014,    // 	DEVICE CONTROL FOUR
        0x15 => 0x0015,    // 	NEGATIVE ACKNOWLEDGE
        0x16 => 0x0016,    // 	SYNCHRONOUS IDLE
        0x17 => 0x0017,    // 	END OF TRANSMISSION BLOCK
        0x18 => 0x0018,    // 	CANCEL
        0x19 => 0x0019,    // 	END OF MEDIUM
        0x1A => 0x001A,    // 	SUBSTITUTE
        0x1B => 0x001B,    // 	ESCAPE
        0x1C => 0x001C,    // 	FILE SEPARATOR
        0x1D => 0x001D,    // 	GROUP SEPARATOR
        0x1E => 0x001E,    // 	RECORD SEPARATOR
        0x1F => 0x001F,    // 	UNIT SEPARATOR
        0x20 => 0x0020,    // 	SPACE
        0x21 => 0x0021,    // 	EXCLAMATION MARK
        0x22 => 0x0022,    // 	QUOTATION MARK
        0x23 => 0x0023,    // 	NUMBER SIGN
        0x24 => 0x0024,    // 	DOLLAR SIGN
        0x25 => 0x0025,    // 	PERCENT SIGN
        0x26 => 0x0026,    // 	AMPERSAND
        0x27 => 0x0027,    // 	APOSTROPHE
        0x28 => 0x0028,    // 	LEFT PARENTHESIS
        0x29 => 0x0029,    // 	RIGHT PARENTHESIS
        0x2A => 0x002A,    // 	ASTERISK
        0x2B => 0x002B,    // 	PLUS SIGN
        0x2C => 0x002C,    // 	COMMA
        0x2D => 0x002D,    // 	HYPHEN-MINUS
        0x2E => 0x002E,    // 	FULL STOP
        0x2F => 0x002F,    // 	SOLIDUS
        0x30 => 0x0030,    // 	DIGIT ZERO
        0x31 => 0x0031,    // 	DIGIT ONE
        0x32 => 0x0032,    // 	DIGIT TWO
        0x33 => 0x0033,    // 	DIGIT THREE
        0x34 => 0x0034,    // 	DIGIT FOUR
        0x35 => 0x0035,    // 	DIGIT FIVE
        0x36 => 0x0036,    // 	DIGIT SIX
        0x37 => 0x0037,    // 	DIGIT SEVEN
        0x38 => 0x0038,    // 	DIGIT EIGHT
        0x39 => 0x0039,    // 	DIGIT NINE
        0x3A => 0x003A,    // 	COLON
        0x3B => 0x003B,    // 	SEMICOLON
        0x3C => 0x003C,    // 	LESS-THAN SIGN
        0x3D => 0x003D,    // 	EQUALS SIGN
        0x3E => 0x003E,    // 	GREATER-THAN SIGN
        0x3F => 0x003F,    // 	QUESTION MARK
        0x40 => 0x0040,    // 	COMMERCIAL AT
        0x41 => 0x0041,    // 	LATIN CAPITAL LETTER A
        0x42 => 0x0042,    // 	LATIN CAPITAL LETTER B
        0x43 => 0x0043,    // 	LATIN CAPITAL LETTER C
        0x44 => 0x0044,    // 	LATIN CAPITAL LETTER D
        0x45 => 0x0045,    // 	LATIN CAPITAL LETTER E
        0x46 => 0x0046,    // 	LATIN CAPITAL LETTER F
        0x47 => 0x0047,    // 	LATIN CAPITAL LETTER G
        0x48 => 0x0048,    // 	LATIN CAPITAL LETTER H
        0x49 => 0x0049,    // 	LATIN CAPITAL LETTER I
        0x4A => 0x004A,    // 	LATIN CAPITAL LETTER J
        0x4B => 0x004B,    // 	LATIN CAPITAL LETTER K
        0x4C => 0x004C,    // 	LATIN CAPITAL LETTER L
        0x4D => 0x004D,    // 	LATIN CAPITAL LETTER M
        0x4E => 0x004E,    // 	LATIN CAPITAL LETTER N
        0x4F => 0x004F,    // 	LATIN CAPITAL LETTER O
        0x50 => 0x0050,    // 	LATIN CAPITAL LETTER P
        0x51 => 0x0051,    // 	LATIN CAPITAL LETTER Q
        0x52 => 0x0052,    // 	LATIN CAPITAL LETTER R
        0x53 => 0x0053,    // 	LATIN CAPITAL LETTER S
        0x54 => 0x0054,    // 	LATIN CAPITAL LETTER T
        0x55 => 0x0055,    // 	LATIN CAPITAL LETTER U
        0x56 => 0x0056,    // 	LATIN CAPITAL LETTER V
        0x57 => 0x0057,    // 	LATIN CAPITAL LETTER W
        0x58 => 0x0058,    // 	LATIN CAPITAL LETTER X
        0x59 => 0x0059,    // 	LATIN CAPITAL LETTER Y
        0x5A => 0x005A,    // 	LATIN CAPITAL LETTER Z
        0x5B => 0x005B,    // 	LEFT SQUARE BRACKET
        0x5C => 0x005C,    // 	REVERSE SOLIDUS
        0x5D => 0x005D,    // 	RIGHT SQUARE BRACKET
        0x5E => 0x005E,    // 	CIRCUMFLEX ACCENT
        0x5F => 0x005F,    // 	LOW LINE
        0x60 => 0x0060,    // 	GRAVE ACCENT
        0x61 => 0x0061,    // 	LATIN SMALL LETTER A
        0x62 => 0x0062,    // 	LATIN SMALL LETTER B
        0x63 => 0x0063,    // 	LATIN SMALL LETTER C
        0x64 => 0x0064,    // 	LATIN SMALL LETTER D
        0x65 => 0x0065,    // 	LATIN SMALL LETTER E
        0x66 => 0x0066,    // 	LATIN SMALL LETTER F
        0x67 => 0x0067,    // 	LATIN SMALL LETTER G
        0x68 => 0x0068,    // 	LATIN SMALL LETTER H
        0x69 => 0x0069,    // 	LATIN SMALL LETTER I
        0x6A => 0x006A,    // 	LATIN SMALL LETTER J
        0x6B => 0x006B,    // 	LATIN SMALL LETTER K
        0x6C => 0x006C,    // 	LATIN SMALL LETTER L
        0x6D => 0x006D,    // 	LATIN SMALL LETTER M
        0x6E => 0x006E,    // 	LATIN SMALL LETTER N
        0x6F => 0x006F,    // 	LATIN SMALL LETTER O
        0x70 => 0x0070,    // 	LATIN SMALL LETTER P
        0x71 => 0x0071,    // 	LATIN SMALL LETTER Q
        0x72 => 0x0072,    // 	LATIN SMALL LETTER R
        0x73 => 0x0073,    // 	LATIN SMALL LETTER S
        0x74 => 0x0074,    // 	LATIN SMALL LETTER T
        0x75 => 0x0075,    // 	LATIN SMALL LETTER U
        0x76 => 0x0076,    // 	LATIN SMALL LETTER V
        0x77 => 0x0077,    // 	LATIN SMALL LETTER W
        0x78 => 0x0078,    // 	LATIN SMALL LETTER X
        0x79 => 0x0079,    // 	LATIN SMALL LETTER Y
        0x7A => 0x007A,    // 	LATIN SMALL LETTER Z
        0x7B => 0x007B,    // 	LEFT CURLY BRACKET
        0x7C => 0x007C,    // 	VERTICAL LINE
        0x7D => 0x007D,    // 	RIGHT CURLY BRACKET
        0x7E => 0x007E,    // 	TILDE
        0x7F => 0x007F,    // 	DELETE
        0x80 => 0x0080,    // 	<control>
        0x81 => 0x0081,    // 	<control>
        0x82 => 0x0082,    // 	<control>
        0x83 => 0x0083,    // 	<control>
        0x84 => 0x0084,    // 	<control>
        0x85 => 0x0085,    // 	<control>
        0x86 => 0x0086,    // 	<control>
        0x87 => 0x0087,    // 	<control>
        0x88 => 0x0088,    // 	<control>
        0x89 => 0x0089,    // 	<control>
        0x8A => 0x008A,    // 	<control>
        0x8B => 0x008B,    // 	<control>
        0x8C => 0x008C,    // 	<control>
        0x8D => 0x008D,    // 	<control>
        0x8E => 0x008E,    // 	<control>
        0x8F => 0x008F,    // 	<control>
        0x90 => 0x0090,    // 	<control>
        0x91 => 0x0091,    // 	<control>
        0x92 => 0x0092,    // 	<control>
        0x93 => 0x0093,    // 	<control>
        0x94 => 0x0094,    // 	<control>
        0x95 => 0x0095,    // 	<control>
        0x96 => 0x0096,    // 	<control>
        0x97 => 0x0097,    // 	<control>
        0x98 => 0x0098,    // 	<control>
        0x99 => 0x0099,    // 	<control>
        0x9A => 0x009A,    // 	<control>
        0x9B => 0x009B,    // 	<control>
        0x9C => 0x009C,    // 	<control>
        0x9D => 0x009D,    // 	<control>
        0x9E => 0x009E,    // 	<control>
        0x9F => 0x009F,    // 	<control>
        0xA0 => 0x00A0,    // 	NO-BREAK SPACE
        0xA1 => 0x0E01,    // 	THAI CHARACTER KO KAI
        0xA2 => 0x0E02,    // 	THAI CHARACTER KHO KHAI
        0xA3 => 0x0E03,    // 	THAI CHARACTER KHO KHUAT
        0xA4 => 0x0E04,    // 	THAI CHARACTER KHO KHWAI
        0xA5 => 0x0E05,    // 	THAI CHARACTER KHO KHON
        0xA6 => 0x0E06,    // 	THAI CHARACTER KHO RAKHANG
        0xA7 => 0x0E07,    // 	THAI CHARACTER NGO NGU
        0xA8 => 0x0E08,    // 	THAI CHARACTER CHO CHAN
        0xA9 => 0x0E09,    // 	THAI CHARACTER CHO CHING
        0xAA => 0x0E0A,    // 	THAI CHARACTER CHO CHANG
        0xAB => 0x0E0B,    // 	THAI CHARACTER SO SO
        0xAC => 0x0E0C,    // 	THAI CHARACTER CHO CHOE
        0xAD => 0x0E0D,    // 	THAI CHARACTER YO YING
        0xAE => 0x0E0E,    // 	THAI CHARACTER DO CHADA
        0xAF => 0x0E0F,    // 	THAI CHARACTER TO PATAK
        0xB0 => 0x0E10,    // 	THAI CHARACTER THO THAN
        0xB1 => 0x0E11,    // 	THAI CHARACTER THO NANGMONTHO
        0xB2 => 0x0E12,    // 	THAI CHARACTER THO PHUTHAO
        0xB3 => 0x0E13,    // 	THAI CHARACTER NO NEN
        0xB4 => 0x0E14,    // 	THAI CHARACTER DO DEK
        0xB5 => 0x0E15,    // 	THAI CHARACTER TO TAO
        0xB6 => 0x0E16,    // 	THAI CHARACTER THO THUNG
        0xB7 => 0x0E17,    // 	THAI CHARACTER THO THAHAN
        0xB8 => 0x0E18,    // 	THAI CHARACTER THO THONG
        0xB9 => 0x0E19,    // 	THAI CHARACTER NO NU
        0xBA => 0x0E1A,    // 	THAI CHARACTER BO BAIMAI
        0xBB => 0x0E1B,    // 	THAI CHARACTER PO PLA
        0xBC => 0x0E1C,    // 	THAI CHARACTER PHO PHUNG
        0xBD => 0x0E1D,    // 	THAI CHARACTER FO FA
        0xBE => 0x0E1E,    // 	THAI CHARACTER PHO PHAN
        0xBF => 0x0E1F,    // 	THAI CHARACTER FO FAN
        0xC0 => 0x0E20,    // 	THAI CHARACTER PHO SAMPHAO
        0xC1 => 0x0E21,    // 	THAI CHARACTER MO MA
        0xC2 => 0x0E22,    // 	THAI CHARACTER YO YAK
        0xC3 => 0x0E23,    // 	THAI CHARACTER RO RUA
        0xC4 => 0x0E24,    // 	THAI CHARACTER RU
        0xC5 => 0x0E25,    // 	THAI CHARACTER LO LING
        0xC6 => 0x0E26,    // 	THAI CHARACTER LU
        0xC7 => 0x0E27,    // 	THAI CHARACTER WO WAEN
        0xC8 => 0x0E28,    // 	THAI CHARACTER SO SALA
        0xC9 => 0x0E29,    // 	THAI CHARACTER SO RUSI
        0xCA => 0x0E2A,    // 	THAI CHARACTER SO SUA
        0xCB => 0x0E2B,    // 	THAI CHARACTER HO HIP
        0xCC => 0x0E2C,    // 	THAI CHARACTER LO CHULA
        0xCD => 0x0E2D,    // 	THAI CHARACTER O ANG
        0xCE => 0x0E2E,    // 	THAI CHARACTER HO NOKHUK
        0xCF => 0x0E2F,    // 	THAI CHARACTER PAIYANNOI
        0xD0 => 0x0E30,    // 	THAI CHARACTER SARA A
        0xD1 => 0x0E31,    // 	THAI CHARACTER MAI HAN-AKAT
        0xD2 => 0x0E32,    // 	THAI CHARACTER SARA AA
        0xD3 => 0x0E33,    // 	THAI CHARACTER SARA AM
        0xD4 => 0x0E34,    // 	THAI CHARACTER SARA I
        0xD5 => 0x0E35,    // 	THAI CHARACTER SARA II
        0xD6 => 0x0E36,    // 	THAI CHARACTER SARA UE
        0xD7 => 0x0E37,    // 	THAI CHARACTER SARA UEE
        0xD8 => 0x0E38,    // 	THAI CHARACTER SARA U
        0xD9 => 0x0E39,    // 	THAI CHARACTER SARA UU
        0xDA => 0x0E3A,    // 	THAI CHARACTER PHINTHU
        0xDF => 0x0E3F,    // 	THAI CURRENCY SYMBOL BAHT
        0xE0 => 0x0E40,    // 	THAI CHARACTER SARA E
        0xE1 => 0x0E41,    // 	THAI CHARACTER SARA AE
        0xE2 => 0x0E42,    // 	THAI CHARACTER SARA O
        0xE3 => 0x0E43,    // 	THAI CHARACTER SARA AI MAIMUAN
        0xE4 => 0x0E44,    // 	THAI CHARACTER SARA AI MAIMALAI
        0xE5 => 0x0E45,    // 	THAI CHARACTER LAKKHANGYAO
        0xE6 => 0x0E46,    // 	THAI CHARACTER MAIYAMOK
        0xE7 => 0x0E47,    // 	THAI CHARACTER MAITAIKHU
        0xE8 => 0x0E48,    // 	THAI CHARACTER MAI EK
        0xE9 => 0x0E49,    // 	THAI CHARACTER MAI THO
        0xEA => 0x0E4A,    // 	THAI CHARACTER MAI TRI
        0xEB => 0x0E4B,    // 	THAI CHARACTER MAI CHATTAWA
        0xEC => 0x0E4C,    // 	THAI CHARACTER THANTHAKHAT
        0xED => 0x0E4D,    // 	THAI CHARACTER NIKHAHIT
        0xEE => 0x0E4E,    // 	THAI CHARACTER YAMAKKAN
        0xEF => 0x0E4F,    // 	THAI CHARACTER FONGMAN
        0xF0 => 0x0E50,    // 	THAI DIGIT ZERO
        0xF1 => 0x0E51,    // 	THAI DIGIT ONE
        0xF2 => 0x0E52,    // 	THAI DIGIT TWO
        0xF3 => 0x0E53,    // 	THAI DIGIT THREE
        0xF4 => 0x0E54,    // 	THAI DIGIT FOUR
        0xF5 => 0x0E55,    // 	THAI DIGIT FIVE
        0xF6 => 0x0E56,    // 	THAI DIGIT SIX
        0xF7 => 0x0E57,    // 	THAI DIGIT SEVEN
        0xF8 => 0x0E58,    // 	THAI DIGIT EIGHT
        0xF9 => 0x0E59,    // 	THAI DIGIT NINE
        0xFA => 0x0E5A,    // 	THAI CHARACTER ANGKHANKHU
        0xFB => 0x0E5B,    // 	THAI CHARACTER KHOMUT
    ],
    "width" => 8,
];
