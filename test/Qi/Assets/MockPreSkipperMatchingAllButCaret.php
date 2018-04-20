<?php
namespace Mxc\Test\Parsec\Qi\Assets;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Qi\Char\CharParser;
use Mxc\Parsec\Qi\Operator\DifferenceOperator;
use Mxc\Parsec\Qi\Operator\PlusOperator;
use Mxc\Parsec\Qi\PreSkipper;

class MockPreSkipperMatchingAllButCaret extends PreSkipper
{
    public function __construct(Domain $domain)
    {
        parent::__construct($domain);

        $this->defaultType = 'string';

        $this->parser = new PlusOperator(
            $domain,
            new DifferenceOperator(
                $domain,
                new CharParser(
                    $domain
                ),
                new CharParser($domain, '^')
            )
        );
    }

    public function doParse($iterator, $expectedValue, $attributeType, $skipper)
    {
        if ($this->parser->doParse($iterator, $expectedValue, 'string', $skipper)) {
            $c = $this->parser->getAttribute();
            if ($iterator->compareChar($c, $expectedValue)) {
                $this->assignTo($c, $attributeType);
                return true;
            }
        }
        return false;
    }
}
