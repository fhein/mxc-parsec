<?php

namespace Mxc\Parsec\Encoding;

class CodePageGenerator extends CodePage
{
    protected $encoding;
    protected $codepage;
    protected $bits = 8;
    protected static $indent = 4;

    protected static function indent($indent = 1)
    {
        return str_repeat(' ', $indent * self::$indent);
    }

    public static function fromSource(string $encoding, $filename = '')
    {
        if ($filename != '' && ! file_exists($filename)) {
            $filename = self::getSourceFilename($encoding);
            if (! file_exists($filename)) {
                    return false;
            }
        }

        // We are here neither for beauty nor performance
        $source = file_get_contents($filename);
        $comments = preg_replace('/^#(.*)[\r\n]*/m', "// \\1\n", $source);
        $comments = preg_replace('/^0x.*[\r\n]*/m', '', $comments);
        $source = preg_replace('/^#.*[\r\n]*/m', '', $source);
        $p = pathinfo($filename);
        $comments = strlen($comments) > 0
            ? '// Heading notes from source file '. $p['basename'] . ':'. PHP_EOL . '//' . PHP_EOL . $comments
            : '';
        // convert key -> value mappings to php
        $source = preg_replace(
            '/(0x[0-9A-F]*).*(0x[0-9A-F]*).*#(.*)[\r\n]*/',
            self::indent(2)."\\1 => \\2,    // \\3\n",
            $source
        );
        $source = "return [\n"
            . self::indent() . '"name" => "'. $encoding . "\",\n"
            . self::indent() . "\"codepage_to_unicode\" => [\n"
            . preg_replace(
                '/(0x[0-9A-F]*).*#(.*)[\r\n]*/',
                self::indent()." // \\1 =>       ,    // \\2\n",
                $source
            )
            . "    ],\n";
        // cover result php like
        $file = self::getEncodingFilename($encoding);
        $settings = eval($source . '];');
        $charMap = $settings['codepage_to_unicode'];
        $width = ceil(log(max(array_keys($charMap)) + 1, 2));
        $source .= self::indent() . "\"width\" => ${width}" . ","  ;
        $source = "<?php\n" . $comments . $source .PHP_EOL;
         $source .= "];\n";
        file_put_contents($file, $source);
    }

    public static function fromSourceDirectory($dir, $filter = '')
    {

        if (false === realPath($dir)) {
            $dir = self::getSourceRoot() . '/' . $dir;
            if (false === realPath($dir)) {
                return false;
            }
        }
        if (! is_dir($dir)) {
            return false;
        }
        $it = new \DirectoryIterator($dir);
        foreach ($it as $fi) {
            if (! ($fi->isFile() && $fi->isReadable())) {
                continue;
            }
            $b = $fi->getBasename();
            $encoding = substr($b, 0, strrpos($b, '.'));
            self::fromSource($encoding, $fi->getPathname());
        }
    }

    public static function fromMbString(string $encoding)
    {
        $config = [];
        $config['name'] = $encoding;
        $bits = $encoding == 'ASCII' ? 7 : 8;
        $config['width'] = $bits;
        $charMap = [];
        for ($i = 0; $i < 2 ** $bits; $i++) {
            $char = mb_convert_encoding(chr($i), 'UTF-8', $encoding);
            // maps ordinal numbers as of codepage to UTF-8 chars
            $charMap[$i] = $char;
        }
        // maps ordinals as of codepage to UTF-8 characters
        $config['charMap'] = $charMap;
        // maps UTF-8 characters to ordinal as of codepage
        $config['ordMap'] = array_flip($charMap);
        $config['charClass'] = self::setupCharClass($charMap);
        return $config;
    }
}
