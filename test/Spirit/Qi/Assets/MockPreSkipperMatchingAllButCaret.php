<?php
namespace Mxc\Test\Parsec\Qi\Assets;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Operator\KleenePlusOperator;
use Mxc\Parsec\Qi\PreSkipper;

class MockPreSkipperMatchingAllButCaret extends PreSkipper
{
    public function __construct(Domain $domain)
    {
        parent::__construct($domain, 'test');

        $this->parser = new KleenePlusOperator(
            $domain,
            'test',
            new DifferenceOperator(
                $domain,
                'test',
                new CharParser(
                    $domain,
                    'test'
                ),
                new CharParser($domain, 'test', '^')
            )
        );
    }

    public function doParse($skipper)
    {
        if ($this->parser->doParse($skipper)) {
            $attr = $this->parser->getAttribute();
            foreach ($attr as $char) {
                $this->attribute .= $char;
            }
            return true;
        }
        return false;
    }
}
