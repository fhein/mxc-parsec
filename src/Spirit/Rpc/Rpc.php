<?php

namespace Mxc\Parsec\Rpc;

use Mxc\Parsec\Service\ParserManager;
use Mxc\Parsec\Service\ParserBuilder;
use Mxc\Parsec\Qi\Char\SpaceParser;

/**
 * Rpc - class being exposed by RPC-server
 */
class Rpc
{
    /**
     * Parse input using parser and skipper
     *
     * @param  array $parser
     * @param  array $skipper
     * @param  string $input
     * @return array $log
     */
    public function parse($parser, $skipper, $input)
    {
        $pm = new ParserManager();
        $pb = $pm->get('parser_builder');
        $pb->setDefinitions($parser);
        $rule = $pb->getRule('rule1');
        $rule->setSource($input);
        var_dump($rule);
        $result = $rule->parse($pm->get('space'));
        return $result;
    }
}
