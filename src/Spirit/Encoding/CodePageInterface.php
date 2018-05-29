<?php

namespace Mxc\Parsec\Encoding;

interface ClassificationInterface
{

    public function getName();

    public function isvalid(int $codepoint);
    public function isalnum(int $codepoint);
    public function isalpha(int $codepoint);
    public function isdigit(int $codepoint);
    public function isxdigit(int $codepoint);
    public function iscntrl(int $codepoint);
    public function isgraph(int $codepoint);
    public function islower(int $codepoint);
    public function isupper(int $codepoint);
    public function isprint(int $codepoint);
    public function ispunct(int $codepoint);
    public function isspace(int $codepoint);
    public function isblank(int $codepoint);
    public function tolower(int $codepoint);
    public function toupper(int $codepoint);
    public function toUtf32(int $codepoint);
    public function fromUtf32(int $codepoint);
}
