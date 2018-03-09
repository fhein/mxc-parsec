<?php

namespace Mxc\Parsec\Encoding;

class CodePage extends CharacterClassification
{
    protected static $encodingRoot = __DIR__ . '/../config/encoding';

    protected $encoding;
    protected $ubound;
    protected $cpToUc;
    protected $ucToCp;
    protected $name;

    public function __construct($encoding)
    {
        $settings = include($this->getEncodingFilename($encoding));
        $this->name = $settings['name'];
        $this->ubound = 2 ** $settings['width'];
        $this->cpToUc = $settings['codepage_to_unicode'];
        $this->ucToCp = array_flip($this->cpToUc);
    }

    public function getName()
    {
        return $this->name;
    }

    public function toUcs4(int $codepoint)
    {
        return isset($this->cpToUc[$codepoint]) ? $this->cpToUc[$codepoint] : null;
    }

    public function fromUcs4(int $codepoint)
    {
        return (isset($this->ucToCp[$codepoint])) ? $this->ucToCp[$codepoint] : null;
    }

    // retrieve UTF-8 encoded character from codepage's codepoint
    public function chr(int $codepoint)
    {
        return (isset($this->cpToUc[$codepoint])) ? \IntlChar::chr($this->cpToUc[$codepoint]) : null;
    }

    // retrieve codepage's codepoint from UTF-8 encoded character
    public function ord(string $string)
    {
        $codepoint = \IntlChar::ord($string);
        return (isset($this->ucToCp[$codepoint])) ? $this->ucToCp[$codepoint] : null;
    }

    protected static function getSourceRoot()
    {
        return self::$encodingRoot . '/source';
    }

    protected static function getEncodingRoot()
    {
        return self::$encodingRoot;
    }

    protected function getEncodingFilename($encoding)
    {
        return self::$encodingRoot . '/' . strtolower($encoding) . '.encoding.php';
    }

    protected function getSourceFilename($encoding)
    {
        return self::$encodingRoot . '/source/' . strtolower($encoding) . '.txt';
    }
}
