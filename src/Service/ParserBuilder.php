<?php

namespace Mxc\Parsec\Service;

use Zend\ServiceManager\ServiceManager;

class ParserBuilder extends ServiceManager
{
    protected $abstractFactories = [
        ComplexParserFactory::class
    ];

    public function __construct(ParserManager $pm, array $options = [])
    {
        if (! empty($options)) {
            $this->services['parser_definitions'] = $options;
        }
        $this->services['parser_manager'] = $pm;
        parent::__construct();
    }
}
